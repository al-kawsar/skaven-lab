/**
 * Form Validation Utility
 * Provides reusable validation functions for forms across the application
 */
(function () {
    "use strict";

    // Base validation class
    class FormValidator {
        constructor(formId, options = {}) {
            this.form = document.getElementById(formId);
            this.formTouched = false;
            this.validators = {};
            this.fieldValues = {};
            this.options = Object.assign(
                {
                    validateOnKeyup: true,
                    validateOnBlur: true,
                    disableSubmitOnInvalid: true,
                    showToastOnSubmit: true,
                    successCallback: null,
                    errorCallback: null,
                },
                options
            );

            this.submitButton = this.form
                ? this.form.querySelector('[type="submit"]')
                : null;

            if (this.form) {
                this.initialize();
            } else {
                console.error(`Form with ID "${formId}" not found.`);
            }
        }

        initialize() {
            // Initialize Toastr if available
            if (window.toastr) {
                toastr.options = {
                    closeButton: true,
                    newestOnTop: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    preventDuplicates: true,
                    showDuration: "300",
                    hideDuration: "500",
                    timeOut: "3000",
                    extendedTimeOut: "1000",
                };
            }

            // Add form submission handler
            this.form.addEventListener("submit", (e) => this.handleSubmit(e));

            // Add pristine class to form
            this.form.classList.add("pristine");
        }

        /**
         * Add a field to be validated
         * @param {string} fieldId - ID of the field
         * @param {Array} rules - Array of validation rules
         * @param {Object} options - Options for this field
         */
        addField(fieldId, rules, options = {}) {
            const field = document.getElementById(fieldId);
            if (!field) {
                console.error(`Field with ID "${fieldId}" not found.`);
                return;
            }

            this.validators[fieldId] = {
                field: field,
                rules: rules,
                options: options,
                errorElement:
                    document.querySelector(`.${fieldId}-error`) ||
                    this.createErrorElement(field, fieldId),
            };

            this.fieldValues[fieldId] = "";

            // Add event listeners for the field
            if (this.options.validateOnKeyup) {
                field.addEventListener("keyup", () => {
                    this.markTouched();
                    this.validateField(fieldId);
                    this.updateFormState();
                });
            }

            if (this.options.validateOnBlur) {
                field.addEventListener("blur", () => {
                    this.markTouched();
                    this.validateField(fieldId);
                    this.updateFormState();
                });
            }

            return this;
        }

        /**
         * Create error element for a field if it doesn't exist
         */
        createErrorElement(field, fieldId) {
            const errorElement = document.createElement("div");
            errorElement.className = `invalid-feedback ${fieldId}-error`;
            field.parentNode.appendChild(errorElement);
            return errorElement;
        }

        /**
         * Mark the form as touched (user has interacted with it)
         */
        markTouched() {
            if (!this.formTouched) {
                this.formTouched = true;
                this.form.classList.remove("pristine");
            }
        }

        /**
         * Validate a specific field
         * @param {string} fieldId - ID of the field to validate
         * @returns {boolean} - Whether the field is valid
         */
        validateField(fieldId) {
            const validator = this.validators[fieldId];
            if (!validator) return true;

            const field = validator.field;
            const value = field.value.trim();

            // Skip validation if value hasn't changed and form is already touched
            if (value === this.fieldValues[fieldId] && this.formTouched) {
                return !field.classList.contains("is-invalid");
            }

            this.fieldValues[fieldId] = value;

            // Don't validate until form is touched
            if (!this.formTouched) return true;

            // Run through all validation rules
            for (const rule of validator.rules) {
                const result = rule.validate(value, field);
                if (!result.valid) {
                    field.classList.add("is-invalid");
                    field.classList.remove("is-valid");
                    validator.errorElement.textContent = result.message;
                    return false;
                }
            }

            // If all rules pass
            field.classList.remove("is-invalid");
            field.classList.add("is-valid");
            validator.errorElement.textContent = "";
            return true;
        }

        /**
         * Validate all fields in the form
         * @returns {boolean} - Whether all fields are valid
         */
        validateAll() {
            let isValid = true;

            for (const fieldId in this.validators) {
                if (!this.validateField(fieldId)) {
                    isValid = false;
                }
            }

            return isValid;
        }

        /**
         * Update form state based on validation results
         */
        updateFormState() {
            if (!this.options.disableSubmitOnInvalid || !this.submitButton)
                return;

            const isValid = this.validateAll();

            if (this.formTouched && isValid) {
                this.submitButton.disabled = false;
            } else if (this.formTouched) {
                this.submitButton.disabled = true;
            }
        }

        /**
         * Handle form submission
         * @param {Event} e - Submit event
         */
        handleSubmit(e) {
            e.preventDefault();

            // Mark form as touched
            this.markTouched();

            // Validate all fields
            const isValid = this.validateAll();

            if (!isValid) {
                if (this.options.showToastOnSubmit && window.toastr) {
                    // Collect error messages
                    let errorMessage = "Please correct the following errors:";

                    for (const fieldId in this.validators) {
                        const validator = this.validators[fieldId];
                        if (validator.field.classList.contains("is-invalid")) {
                            errorMessage +=
                                "<br>• " + validator.errorElement.textContent;
                        }
                    }

                    toastr.error(errorMessage);
                }

                if (this.options.errorCallback) {
                    this.options.errorCallback();
                }

                return false;
            }

            // If valid, proceed with form submission
            if (this.submitButton) {
                this.submitButton.disabled = true;
                this.submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            }

            if (this.options.successCallback) {
                this.options.successCallback(new FormData(this.form));
            } else {
                // Default AJAX form submission
                this.submitFormAjax();
            }
        }

        /**
         * Submit form via AJAX
         */
        submitFormAjax() {
            const formData = new FormData(this.form);

            $.ajax({
                url: this.form.action,
                type: this.form.method || "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: (response) => {
                    if (response.success) {
                        // Show success message
                        if (window.toastr) {
                            toastr.success(response.message);
                        }

                        // Redirect after success
                        setTimeout(() => {
                            window.location.href =
                                response.redirect || "/dashboard";
                        }, 1500);
                    } else {
                        // Show error message
                        if (window.toastr) {
                            toastr.error(response.message);
                        }
                        this.resetSubmitButton();
                    }
                },
                error: (xhr) => {
                    const response = xhr.responseJSON;

                    if (response && response.errors) {
                        // Handle validation errors
                        this.handleServerValidationErrors(response.errors);
                    } else if (response && response.message) {
                        // Handle other errors
                        if (window.toastr) {
                            toastr.error(response.message);
                        }
                    } else {
                        // Handle unknown errors
                        if (window.toastr) {
                            toastr.error(
                                "An error occurred while processing your request."
                            );
                        }
                    }

                    this.resetSubmitButton();
                },
            });
        }

        /**
         * Handle server-side validation errors
         * @param {Object} errors - Validation errors from server
         */
        handleServerValidationErrors(errors) {
            // Clear previous validation states
            for (const fieldId in this.validators) {
                const validator = this.validators[fieldId];
                validator.field.classList.remove("is-invalid");
                validator.errorElement.textContent = "";
            }

            // Build error message
            let errorMessage = "Please correct the following errors:";

            // Handle each error
            for (const fieldName in errors) {
                const fieldError = errors[fieldName][0];
                const fieldId = fieldName; // Assuming field name matches ID

                if (this.validators[fieldId]) {
                    const validator = this.validators[fieldId];
                    validator.field.classList.add("is-invalid");
                    validator.errorElement.textContent = fieldError;
                }

                errorMessage += "<br>• " + fieldError;
            }

            // Show toast with all errors
            if (window.toastr) {
                toastr.error(errorMessage);
            }
        }

        /**
         * Reset submit button to its original state
         */
        resetSubmitButton() {
            if (this.submitButton) {
                this.submitButton.disabled = false;
                this.submitButton.innerHTML = "Login";
            }
        }
    }

    // Common validation rules
    const ValidationRules = {
        required: {
            validate: (value) => ({
                valid: value.length > 0,
                message: "This field is required",
            }),
        },
        minLength: (length) => ({
            validate: (value) => ({
                valid: value.length >= length,
                message: `Must be at least ${length} characters`,
            }),
        }),
        maxLength: (length) => ({
            validate: (value) => ({
                valid: value.length <= length,
                message: `Cannot exceed ${length} characters`,
            }),
        }),
        email: {
            validate: (value) => ({
                valid: /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
                message: "Please enter a valid email address",
            }),
        },
        numeric: {
            validate: (value) => ({
                valid: /^\d+$/.test(value),
                message: "Please enter numbers only",
            }),
        },
        match: (otherFieldId, fieldName) => ({
            validate: (value) => {
                const otherField = document.getElementById(otherFieldId);
                return {
                    valid: otherField && value === otherField.value,
                    message: `Does not match ${fieldName || "the other field"}`,
                };
            },
        }),
    };

    // Make available globally
    window.FormValidator = FormValidator;
    window.ValidationRules = ValidationRules;
})();

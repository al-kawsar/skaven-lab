/**
 * FormValidator - A comprehensive form validation and error display system
 * For use with SweetAlert2 and Bootstrap forms
 */

class FormValidator {
    constructor(options = {}) {
        this.options = {
            // Default categories for grouping errors
            categories: {
                keperluan: {
                    icon: 'exclamation-circle',
                    title: 'Keperluan'
                },
                tanggal: {
                    icon: 'calendar-alt',
                    title: 'Tanggal'
                },
                waktu: {
                    icon: 'clock',
                    title: 'Waktu'
                },
                lainnya: {
                    icon: 'info-circle',
                    title: 'Informasi Lainnya'
                }
            },
            // Map form fields to categories
            fieldCategories: {
                // Define field to category mappings
                event: 'keperluan',
                borrow_date: 'tanggal',
                start_time: 'waktu',
                end_time: 'waktu',
                notes: 'lainnya'
                // Add more field mappings as needed
            },
            // SweetAlert configuration
            sweetAlert: {
                width: 600,
                confirmButtonText: 'Perbaiki',
                confirmButtonColor: '#3085d6'
            },
            ...options
        };

        // Initialize errors object
        this.resetErrors();
    }

    /**
     * Reset all errors
     */
    resetErrors() {
        this.errors = {};

        // Initialize error categories
        for (const category in this.options.categories) {
            this.errors[category] = [];
        }

        return this;
    }

    /**
     * Add an error to a specific category
     * @param {string} message - The error message
     * @param {string} category - The category to add to
     */
    addError(message, category = 'lainnya') {
        if (!this.errors[category]) {
            this.errors[category] = [];
        }

        this.errors[category].push(message);
        return this;
    }

    /**
     * Add multiple errors at once
     * @param {Object} errors - Object with category keys and array values
     */
    addErrors(errors) {
        for (const category in errors) {
            if (Array.isArray(errors[category])) {
                errors[category].forEach(message => {
                    this.addError(message, category);
                });
            }
        }
        return this;
    }

    /**
     * Process server-side errors from Laravel
     * @param {Object} responseJSON - The JSON response from server
     */
    processServerErrors(responseJSON) {
        if (!responseJSON || !responseJSON.errors) return this;

        for (const field in responseJSON.errors) {
            const errorMsg = responseJSON.errors[field][0];
            const category = this.options.fieldCategories[field] || 'lainnya';

            this.addError(errorMsg, category);
        }

        return this;
    }

    /**
     * Check if there are any errors
     * @returns {boolean} True if there are errors
     */
    hasErrors() {
        let totalErrors = 0;

        for (const category in this.errors) {
            totalErrors += this.errors[category].length;
        }

        return totalErrors > 0;
    }

    /**
     * Generate HTML for displaying errors
     * @returns {string} HTML for displaying in SweetAlert
     */
    generateErrorHTML() {
        let errorHTML = `
        <div style="text-align: left; max-width: 500px; margin: 0 auto;">
            <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <strong>Mohon perbaiki kesalahan berikut untuk melanjutkan:</strong>
            </div>
        `;

        // Build HTML for each category that has errors
        for (const category in this.errors) {
            if (this.errors[category].length > 0) {
                const categoryInfo = this.options.categories[category] || {
                    icon: 'exclamation-circle',
                    title: category.charAt(0).toUpperCase() + category.slice(1)
                };

                errorHTML += `
                <div style="margin-bottom: 15px;">
                    <h6 style="color: #495057; border-bottom: 1px solid #dee2e6; padding-bottom: 5px; margin-bottom: 10px; font-weight: 600;">
                        <i class="fas fa-${categoryInfo.icon} text-danger"></i> ${categoryInfo.title}
                    </h6>
                    <ul style="list-style-type: none; padding-left: 10px; margin: 0;">
                        ${this.errors[category].map(err =>
                            `<li style="padding: 3px 0; color: #dc3545;"><i class="fas fa-times-circle"></i> ${err}</li>`
                        ).join('')}
                    </ul>
                </div>`;
            }
        }

        errorHTML += `</div>`;
        return errorHTML;
    }

    /**
     * Display errors in a SweetAlert
     */
    showErrors() {
        if (!this.hasErrors()) return false;

        Swal.fire({
            title: 'Validasi Gagal',
            html: this.generateErrorHTML(),
            icon: 'error',
            ...this.options.sweetAlert
        });

        return true;
    }

    /**
     * Set up a standard form submission with validation
     * @param {string} formSelector - The form selector
     * @param {function} validateFunc - Function to perform validation
     * @param {Object} ajaxOptions - Options for the AJAX request
     */
    setupFormSubmission(formSelector, validateFunc, ajaxOptions) {
        const self = this;
        const form = $(formSelector);

        if (!form.length) {
            console.error(`Form with selector "${formSelector}" not found.`);
            return;
        }

        form.on('submit', function(event) {
            event.preventDefault();

            // Reset previous errors
            self.resetErrors();

            // Perform validation
            validateFunc(self);

            // Show errors if any
            if (self.hasErrors()) {
                self.showErrors();
                return;
            }

            // Proceed with form submission
            self.submitForm(this, ajaxOptions);
        });
    }

    /**
     * Submit form with AJAX
     * @param {HTMLElement} formElement - The form element
     * @param {Object} options - AJAX options
     */
    submitForm(formElement, options = {}) {
        // Default loading button
        const submitBtn = options.submitButton || '#btn-submit';
        const loadingText = options.loadingText || 'Loading...';
        const originalText = $(submitBtn).text();

        // Set loading state
        $(submitBtn).text(loadingText).prop('disabled', true);

        // Prepare form data
        let formData;
        if (options.prepareFormData) {
            formData = options.prepareFormData(formElement);
        } else {
            formData = new FormData(formElement);
        }

        // Default AJAX options
        const ajaxOptions = {
            url: $(formElement).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: response.message || 'Data berhasil disimpan',
                    icon: 'success'
                }).then(() => {
                    if (options.redirectUrl) {
                        window.location.href = options.redirectUrl;
                    } else if (options.onSuccess) {
                        options.onSuccess(response);
                    }
                });
            },
            error: (xhr, status, error) => {
                console.error("Error response:", xhr.responseText);

                // Process server validation errors
                if (xhr.responseJSON) {
                    this.processServerErrors(xhr.responseJSON);

                    if (this.hasErrors()) {
                        this.showErrors();
                        return;
                    }

                    // If no validation errors but has message
                    if (xhr.responseJSON.message) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON.message,
                            icon: 'error'
                        });
                        return;
                    }
                }

                // Fallback error
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan pada server',
                    icon: 'error'
                });
            },
            complete: function() {
                $(submitBtn).text(originalText).prop('disabled', false);

                if (options.onComplete) {
                    options.onComplete();
                }
            },
            ...options.ajax
        };

        // Perform AJAX request
        $.ajax(ajaxOptions);
    }

    /**
     * Common field validations
     */
    validateRequired(value, fieldName, category) {
        if (!value || value.trim() === '') {
            this.addError(`${fieldName} harus diisi`, category);
            return false;
        }
        return true;
    }

    validateMinLength(value, minLength, fieldName, category) {
        if (value && value.length < minLength) {
            this.addError(`${fieldName} minimal ${minLength} karakter`, category);
            return false;
        }
        return true;
    }

    validateDate(dateStr, fieldName, category) {
        if (!moment(dateStr, 'DD-MM-YYYY').isValid()) {
            this.addError(`${fieldName} tidak valid`, category);
            return false;
        }
        return true;
    }

    validateTime(timeStr, fieldName, category) {
        if (!moment(timeStr, 'HH:mm').isValid()) {
            this.addError(`${fieldName} tidak valid`, category);
            return false;
        }
        return true;
    }

    validateTimeRange(startTime, endTime, category = 'waktu') {
        if (startTime && endTime && startTime >= endTime) {
            this.addError('Waktu mulai harus lebih awal dari waktu selesai', category);
            return false;
        }
        return true;
    }

    validateFutureDate(dateStr, fieldName, category = 'tanggal') {
        const selectedDate = moment(dateStr, 'DD-MM-YYYY');
        const today = moment().startOf('day');

        if (selectedDate.isBefore(today)) {
            this.addError(`${fieldName} tidak boleh di masa lalu`, category);
            return false;
        }
        return true;
    }

    validateFutureTimeIfToday(dateStr, timeStr, fieldName, category = 'waktu') {
        const selectedDate = moment(dateStr, 'DD-MM-YYYY');
        const today = moment().startOf('day');

        if (selectedDate.isSame(today)) {
            const selectedTime = moment(timeStr, 'HH:mm');
            const now = moment();

            if (selectedTime.isBefore(now)) {
                this.addError(`${fieldName} tidak boleh lebih awal dari sekarang jika tanggal adalah hari ini`, category);
                return false;
            }
        }
        return true;
    }
}

// Create global instance
window.FormValidator = FormValidator;
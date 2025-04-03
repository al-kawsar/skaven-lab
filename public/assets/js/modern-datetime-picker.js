/**
 * Modern Date and Time Picker
 * A clean, modern datepicker and timepicker for web applications
 * Enhanced with time period selection and dynamic time restrictions
 */

class ModernDateTimePicker {
    constructor(options = {}) {
        this.options = {
            dateFormat: "DD-MM-YYYY",
            friendlyDateFormat: true, // Format date in friendly format for display
            timeFormat: "HH:mm",
            timeInterval: 15, // in minutes
            startHour: 7, // start time options from 07:00
            endHour: 22, // end time options until 22:00
            showTimePeriods: true, // Show time period selector (morning, afternoon, etc.)
            restrictCurrentDay: true, // Restrict time selection on current day
            ...options,
        };

        // Indonesian day and month names
        this.dayNames = [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
        ];
        this.monthNames = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        // Time periods definition
        this.timePeriods = {
            pagi: { label: "Pagi", start: "07:00", end: "11:45" },
            siang: { label: "Siang", start: "12:00", end: "14:45" },
            sore: { label: "Sore", start: "15:00", end: "17:45" },
            malam: { label: "Malam", start: "18:00", end: "22:00" },
        };

        this.init();
    }

    init() {
        // Initialize pickers when document is ready
        $(document).ready(() => {
            this.initDatePicker();
            this.initTimePicker();
            this.setupEvents();
        });
    }

    // Format date in a friendly way (e.g., "Senin, 10 Januari 2023")
    formatFriendlyDate(date) {
        const momentDate = moment(date);
        const dayName = this.dayNames[momentDate.day()];
        const day = momentDate.date();
        const monthName = this.monthNames[momentDate.month()];
        const year = momentDate.year();

        return `${dayName}, ${day} ${monthName} ${year}`;
    }

    // Get nearest valid time option based on current time
    getNearestTimeOption(now) {
        const currentHour = now.hour();
        const currentMinute = now.minute();
        const interval = this.options.timeInterval;

        // Calculate nearest time interval
        const remainderMinutes = currentMinute % interval;
        let adjustedMinute = currentMinute;

        // Round up to the next interval
        if (remainderMinutes > 0) {
            adjustedMinute = currentMinute + (interval - remainderMinutes);

            // If we've gone past 59 minutes, increment the hour
            if (adjustedMinute >= 60) {
                return moment()
                    .hour(currentHour + 1)
                    .minute(adjustedMinute - 60)
                    .format(this.options.timeFormat);
            }
        }

        return moment()
            .hour(currentHour)
            .minute(adjustedMinute)
            .format(this.options.timeFormat);
    }

    initDatePicker() {
        // Date picker functionality
        const dateInputs = $(".date-input");
        if (!dateInputs.length) return;

        dateInputs.each((index, element) => {
            const dateInput = $(element);
            const datePickerContainer = $(`#${dateInput.attr("id")}-container`);
            if (!datePickerContainer.length) return;

            const today = moment();

            // Store data for this picker
            dateInput.data("picker", {
                selectedDate: today,
                currentMonth: today.month(),
                currentYear: today.year(),
            });

            // Create hidden input to store the actual date value
            const hiddenInputId = `${dateInput.attr("id")}_hidden`;
            const hiddenInput = $(
                `<input type="hidden" id="${hiddenInputId}" name="${dateInput.attr(
                    "name"
                )}" value="${today.format(this.options.dateFormat)}">`
            );
            dateInput.after(hiddenInput);

            // Change the name of the visible input to avoid duplication in form submission
            dateInput.attr("name", `${dateInput.attr("name")}_display`);

            // Initialize with today's date in friendly format
            if (this.options.friendlyDateFormat) {
                dateInput.val(this.formatFriendlyDate(today));
            } else {
                dateInput.val(today.format(this.options.dateFormat));
            }

            // Use defaultValue if provided as data attribute
            const defaultValue = dateInput.data("default-value");
            if (defaultValue && defaultValue.length > 0) {
                const defaultDate = moment(
                    defaultValue,
                    this.options.dateFormat
                );
                if (defaultDate.isValid()) {
                    // Update picker data
                    const data = dateInput.data("picker");
                    data.selectedDate = defaultDate;
                    data.currentMonth = defaultDate.month();
                    data.currentYear = defaultDate.year();
                    dateInput.data("picker", data);

                    // Update visible and hidden inputs
                    if (this.options.friendlyDateFormat) {
                        dateInput.val(this.formatFriendlyDate(defaultDate));
                    } else {
                        dateInput.val(
                            defaultDate.format(this.options.dateFormat)
                        );
                    }

                    hiddenInput.val(
                        defaultDate.format(this.options.dateFormat)
                    );
                }
            }

            // Setup click handler for the date picker wrapper
            dateInput.closest(".date-picker-wrapper").on("click", (e) => {
                e.stopPropagation();
                datePickerContainer.toggleClass("show");
                // Hide all time pickers when date picker is shown
                $(".modern-timepicker-container").removeClass("show");

                if (datePickerContainer.hasClass("show")) {
                    const data = dateInput.data("picker");
                    this.renderCalendar(dateInput, datePickerContainer);
                }
            });

            // Add change event to update time picker restrictions if needed
            dateInput.on("change", () => {
                if (this.options.restrictCurrentDay) {
                    this.updateTimeRestrictions();
                }
            });
        });
    }

    renderCalendar(dateInput, datePickerContainer) {
        const data = dateInput.data("picker");
        const today = moment();
        const firstDay = moment([data.currentYear, data.currentMonth, 1]);
        const daysInMonth = firstDay.daysInMonth();
        const firstDayOfWeek = firstDay.day(); // 0 = Sunday, 1 = Monday, etc.

        // Create calendar header
        let calendarHtml = `
            <div class="calendar-header">
                <h4>${this.monthNames[data.currentMonth]} ${
            data.currentYear
        }</h4>
                <div class="calendar-nav">
                    <button type="button" class="prev-month"><i class="fas fa-chevron-left"></i></button>
                    <button type="button" class="next-month"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="calendar-grid">
        `;

        // Day headers (Min, Sen, etc)
        const dayNameAbbr = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
        for (let i = 0; i < 7; i++) {
            calendarHtml += `<div class="calendar-day-header">${dayNameAbbr[i]}</div>`;
        }

        // Empty days before the first day of month
        for (let i = 0; i < firstDayOfWeek; i++) {
            calendarHtml += `<div></div>`;
        }

        // Days of the month
        const minDate = moment().startOf("day");
        const selectedDate = moment(dateInput.data("picker").selectedDate);

        for (let day = 1; day <= daysInMonth; day++) {
            const date = moment([data.currentYear, data.currentMonth, day]);
            const isToday = date.isSame(today, "day");
            const isSelected = date.isSame(selectedDate, "day");
            const isPast = date.isBefore(minDate, "day");

            let classes = "calendar-day";
            if (isSelected) classes += " active";
            if (isToday) classes += " today";
            if (isPast) classes += " disabled";

            calendarHtml += `<div class="${classes}" data-date="${date.format(
                "YYYY-MM-DD"
            )}">${day}</div>`;
        }

        calendarHtml += `</div>`;
        datePickerContainer.html(calendarHtml);

        // Attach event handlers
        const picker = this;
        datePickerContainer.find(".prev-month").click(function () {
            const data = dateInput.data("picker");
            if (data.currentMonth === 0) {
                data.currentMonth = 11;
                data.currentYear--;
            } else {
                data.currentMonth--;
            }
            dateInput.data("picker", data);
            picker.renderCalendar(dateInput, datePickerContainer);
        });

        datePickerContainer.find(".next-month").click(function () {
            const data = dateInput.data("picker");
            if (data.currentMonth === 11) {
                data.currentMonth = 0;
                data.currentYear++;
            } else {
                data.currentMonth++;
            }
            dateInput.data("picker", data);
            picker.renderCalendar(dateInput, datePickerContainer);
        });

        datePickerContainer
            .find(".calendar-day")
            .not(".disabled")
            .click(function () {
                const dateStr = $(this).data("date");
                const newSelectedDate = moment(dateStr);

                // Update picker data
                const data = dateInput.data("picker");
                data.selectedDate = newSelectedDate;
                dateInput.data("picker", data);

                // Update visible input with friendly format
                if (picker.options.friendlyDateFormat) {
                    dateInput.val(picker.formatFriendlyDate(newSelectedDate));
                } else {
                    dateInput.val(
                        newSelectedDate.format(picker.options.dateFormat)
                    );
                }

                // Update hidden input with standard format for form submission
                const hiddenInputId = `${dateInput.attr("id")}_hidden`;
                $(`#${hiddenInputId}`).val(
                    newSelectedDate.format(picker.options.dateFormat)
                );

                // Update time restrictions if this is current day
                if (picker.options.restrictCurrentDay) {
                    picker.updateTimeRestrictions();
                }

                // Re-render to update active state
                picker.renderCalendar(dateInput, datePickerContainer);

                // Hide picker
                datePickerContainer.removeClass("show");
            });
    }

    initTimePicker() {
        // Time picker functionality
        const timeInputs = $(".time-input");
        if (!timeInputs.length) return;

        // Get current time
        const now = moment();
        const currentTime = this.getNearestTimeOption(now);

        timeInputs.each((index, element) => {
            const timeInput = $(element);
            const timePickerContainer = $(`#${timeInput.attr("id")}-container`);
            if (!timePickerContainer.length) return;

            // Generate time picker HTML
            timePickerContainer.html(this.generateTimePickerContent());

            // Set current time as default if empty
            if (!timeInput.val()) {
                let defaultTime;

                // For start time, use current time
                if (index === 0) {
                    defaultTime = currentTime;
                }
                // For end time, use current time + 2 hours
                else {
                    const endTime = moment(
                        currentTime,
                        this.options.timeFormat
                    ).add(2, "hours");
                    // Ensure end time doesn't exceed the endHour
                    if (endTime.hour() > this.options.endHour) {
                        endTime.hour(this.options.endHour).minute(0);
                    }
                    defaultTime = endTime.format(this.options.timeFormat);
                }

                timeInput.val(defaultTime);
            }

            // Setup click handler for the time picker wrapper
            timeInput.closest(".time-picker-wrapper").on("click", (e) => {
                e.stopPropagation();

                // Hide all other pickers
                $(".modern-datepicker-container, .modern-timepicker-container")
                    .not(timePickerContainer)
                    .removeClass("show");

                // Toggle this picker
                timePickerContainer.toggleClass("show");

                if (timePickerContainer.hasClass("show")) {
                    this.updateActivePeriod(timeInput, timePickerContainer);

                    // Mark the selected time
                    timePickerContainer
                        .find(".time-option")
                        .removeClass("active");
                    const selectedOption = timePickerContainer.find(
                        `.time-option[data-time="${timeInput.val()}"]`
                    );

                    if (selectedOption.length) {
                        selectedOption.addClass("active");
                        // Scroll to the selected time
                        selectedOption[0].scrollIntoView({ block: "center" });
                    }
                }
            });

            // Handle time selection
            timePickerContainer.on("click", ".time-option", function () {
                // Don't proceed if the option is disabled
                if ($(this).hasClass("disabled")) return;

                const time = $(this).data("time");
                timeInput.val(time);
                timePickerContainer.find(".time-option").removeClass("active");
                $(this).addClass("active");
                timePickerContainer.removeClass("show");

                // Trigger change event
                timeInput.trigger("change");
            });

            // Handle time period selection
            timePickerContainer.on("click", ".time-period", (e) => {
                // Don't proceed if the period is disabled
                if ($(e.currentTarget).hasClass("disabled")) return;

                const period = $(e.currentTarget).data("period");
                timePickerContainer.find(".time-period").removeClass("active");
                $(e.currentTarget).addClass("active");

                // Show times for this period
                this.displayTimePeriod(timePickerContainer, period);
            });
        });

        // Initial time restrictions if needed
        if (this.options.restrictCurrentDay) {
            this.updateTimeRestrictions();
        }
    }

    generateTimePickerContent() {
        let html = "";

        // Time period selections
        if (this.options.showTimePeriods) {
            html += '<div class="time-periods-container">';
            for (const [key, period] of Object.entries(this.timePeriods)) {
                html += `<div class="time-period" data-period="${key}">${period.label}</div>`;
            }
            html += "</div>";
        }

        // Time options container
        html += '<div class="time-picker-grid">';

        // Generate time options in intervals - these will be filtered by period selection
        for (
            let hour = this.options.startHour;
            hour <= this.options.endHour;
            hour++
        ) {
            for (
                let minute = 0;
                minute < 60;
                minute += this.options.timeInterval
            ) {
                // Stop at endHour:00
                if (hour === this.options.endHour && minute > 0) continue;

                const formattedHour = hour.toString().padStart(2, "0");
                const formattedMinute = minute.toString().padStart(2, "0");
                const timeStr = `${formattedHour}:${formattedMinute}`;

                html += `<div class="time-option" data-time="${timeStr}">${timeStr}</div>`;
            }
        }

        html += "</div>";
        return html;
    }

    displayTimePeriod(container, period) {
        if (!this.timePeriods[period]) return;

        const periodStart = this.timePeriods[period].start;
        const periodEnd = this.timePeriods[period].end;

        // Hide all time options
        container.find(".time-option").hide();

        // Show only the times in this period
        container.find(".time-option").each(function () {
            const timeStr = $(this).data("time");
            if (timeStr >= periodStart && timeStr <= periodEnd) {
                $(this).show();
            }
        });
    }

    updateActivePeriod(timeInput, container) {
        const currentTime = timeInput.val();
        let activePeriod = null;

        // Find which period the current time belongs to
        for (const [key, period] of Object.entries(this.timePeriods)) {
            if (currentTime >= period.start && currentTime <= period.end) {
                activePeriod = key;
                break;
            }
        }

        // Reset period selection
        container.find(".time-period").removeClass("active");

        // If found, activate the period and display its times
        if (activePeriod) {
            container
                .find(`.time-period[data-period="${activePeriod}"]`)
                .addClass("active");
            this.displayTimePeriod(container, activePeriod);
        } else {
            // If no period matches, show all times
            container.find(".time-option").show();
        }
    }

    updateTimeRestrictions() {
        const dateInput = $("#date");
        if (!dateInput.length) return;

        const timeInputs = $(".time-input");
        if (!timeInputs.length) return;

        const selectedDateStr = $(`#${dateInput.attr("id")}_hidden`).val();
        const selectedDate = moment(selectedDateStr, this.options.dateFormat);
        const today = moment().startOf("day");
        const now = moment();

        // Only apply restrictions for the current day
        const isToday = selectedDate.isSame(today, "day");

        timeInputs.each((i, el) => {
            const container = $(`#${$(el).attr("id")}-container`);

            // First, remove all disabled states
            container
                .find(".time-option")
                .removeClass("disabled")
                .css("cursor", "pointer");
            container
                .find(".time-period")
                .removeClass("disabled")
                .css("cursor", "pointer");

            // Then apply restrictions only if it's today
            if (isToday) {
                // Disable past times for today
                container.find(".time-option").each(function () {
                    const timeStr = $(this).data("time");
                    const timeObj = moment(timeStr, "HH:mm");
                    const combinedDateTime = moment(selectedDate)
                        .hour(timeObj.hour())
                        .minute(timeObj.minute());

                    if (combinedDateTime.isBefore(now)) {
                        $(this)
                            .addClass("disabled")
                            .css("cursor", "not-allowed");
                    }
                });

                // Also update available periods
                if (this.options.showTimePeriods) {
                    for (const [key, period] of Object.entries(
                        this.timePeriods
                    )) {
                        const periodEndTime = moment(period.end, "HH:mm");
                        const combinedEndTime = moment(selectedDate)
                            .hour(periodEndTime.hour())
                            .minute(periodEndTime.minute());

                        const periodStartTime = moment(period.start, "HH:mm");
                        const combinedStartTime = moment(selectedDate)
                            .hour(periodStartTime.hour())
                            .minute(periodStartTime.minute());

                        // If the entire period is in the past, disable it
                        if (combinedEndTime.isBefore(now)) {
                            container
                                .find(`.time-period[data-period="${key}"]`)
                                .addClass("disabled")
                                .css("cursor", "not-allowed");
                        }
                        // If some times in the period are still available
                        else if (combinedStartTime.isBefore(now)) {
                            // Mark the period as partially available
                            const periodElement = container.find(
                                `.time-period[data-period="${key}"]`
                            );
                            periodElement.addClass("partial");
                        }
                    }
                }
            }
        });
    }

    setupEvents() {
        // Close pickers when clicking outside
        $(document).click(function () {
            $(
                ".modern-datepicker-container, .modern-timepicker-container"
            ).removeClass("show");
        });

        // Prevent clicks inside the picker from closing it
        $(".modern-datepicker-container, .modern-timepicker-container").click(
            function (e) {
                e.stopPropagation();
            }
        );

        // Listen for date changes to update time restrictions
        $(document).on("change", ".date-input", () => {
            if (this.options.restrictCurrentDay) {
                this.updateTimeRestrictions();
            }
        });
    }
}

// Initialize when the script loads
const modernDateTimePicker = new ModernDateTimePicker();

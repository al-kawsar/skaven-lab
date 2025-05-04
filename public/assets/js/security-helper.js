/**
 * Security Helper
 * Provides security-related functionality for client-side
 */
(function () {
    "use strict";

    class SecurityHelper {
        /**
         * Initialize security features
         */
        static initialize() {
            // Prevent CSRF by checking if token exists
            SecurityHelper.setupCSRFProtection();

            // Add extra security headers via meta tags
            SecurityHelper.addSecurityMetaTags();

            // Implement Session Timeout Detection
            SecurityHelper.setupSessionTimeoutDetection();

            // Add Protection against XSS
            SecurityHelper.sanitizeFormInputs();

            // Check if site is using HTTPS
            SecurityHelper.checkHttpsUsage();

            // Check and fix mixed content
            SecurityHelper.checkAndFixMixedContent();
        }

        /**
         * Setup CSRF protection for AJAX requests
         */
        static setupCSRFProtection() {
            // Get the CSRF token from the meta tag
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content");

            if (csrfToken) {
                // For jQuery AJAX requests
                if (window.jQuery) {
                    $.ajaxSetup({
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                    });
                }

                // For fetch API
                const originalFetch = window.fetch;
                window.fetch = function (url, options = {}) {
                    if (!(options.headers instanceof Headers)) {
                        options.headers = new Headers(options.headers || {});
                    }

                    if (
                        !options.headers.has("X-CSRF-TOKEN") &&
                        !url.includes("http")
                    ) {
                        options.headers.append("X-CSRF-TOKEN", csrfToken);
                    }

                    return originalFetch(url, options);
                };
            }
        }

        /**
         * Add security meta tags to prevent common attacks
         * Note: Some security headers can only be properly set server-side
         */
        static addSecurityMetaTags() {
            const head = document.querySelector("head");

            // Only add if not already present
            if (!document.querySelector('meta[name="referrer"]')) {
                const metaTags = [
                    // Referrer-Policy (can be set via meta tag)
                    {
                        name: "referrer",
                        content: "strict-origin-when-cross-origin",
                    },
                ];

                metaTags.forEach((metaInfo) => {
                    const meta = document.createElement("meta");

                    if (metaInfo.name) {
                        meta.name = metaInfo.name;
                    }

                    meta.content = metaInfo.content;
                    head.appendChild(meta);
                });
            }

            // Log a warning about headers that should be set server-side
            console.info(
                "Security notice: Content-Security-Policy, X-Frame-Options, X-XSS-Protection, and X-Content-Type-Options " +
                    "should be set via HTTP headers on the server, not via meta tags."
            );
        }

        /**
         * Setup session timeout detection
         * @param {number} idleTime - Idle time in minutes (default: 30)
         */
        static setupSessionTimeoutDetection(idleTime = 30) {
            let idleTimer;
            const idleTimeoutMs = idleTime * 60 * 1000;
            let lastActivityTime = Date.now();

            // Reset timer on user activity
            const resetTimer = () => {
                lastActivityTime = Date.now();
                clearTimeout(idleTimer);

                // Set new timeout
                idleTimer = setTimeout(() => {
                    // Calculate time since last activity
                    const timeSinceLastActivity = Date.now() - lastActivityTime;

                    if (timeSinceLastActivity >= idleTimeoutMs) {
                        // User has been idle for too long
                        if (window.toastr) {
                            toastr.warning(
                                "Your session is about to expire due to inactivity. Please save your work and refresh the page.",
                                "Session Timeout",
                                {
                                    timeOut: 0,
                                    extendedTimeOut: 0,
                                    closeButton: true,
                                    tapToDismiss: false,
                                }
                            );
                        } else {
                            alert(
                                "Your session is about to expire due to inactivity. Please save your work and refresh the page."
                            );
                        }
                    }
                }, idleTimeoutMs);
            };

            // Watch for user activity
            const activityEvents = [
                "mousedown",
                "mousemove",
                "keypress",
                "scroll",
                "touchstart",
            ];
            activityEvents.forEach((event) => {
                document.addEventListener(event, resetTimer, true);
            });

            // Initial timer setup
            resetTimer();
        }

        /**
         * Sanitize form inputs to prevent XSS
         */
        static sanitizeFormInputs() {
            // Sanitize form inputs before submission
            document.addEventListener("submit", function (e) {
                const form = e.target;

                // Skip forms with data-no-sanitize attribute
                if (form.hasAttribute("data-no-sanitize")) {
                    return;
                }

                // Get all inputs
                const inputs = form.querySelectorAll(
                    'input[type="text"], input[type="email"], input[type="search"], textarea'
                );

                inputs.forEach((input) => {
                    // Skip inputs with data-no-sanitize attribute
                    if (input.hasAttribute("data-no-sanitize")) {
                        return;
                    }

                    // Basic XSS protection by encoding HTML entities
                    input.value = SecurityHelper.escapeHtml(input.value);
                });
            });
        }

        /**
         * Check if the site is using HTTPS and warn if not
         */
        static checkHttpsUsage() {
            if (
                window.location.protocol !== "https:" &&
                window.location.hostname !== "localhost" &&
                !window.location.hostname.includes("127.0.0.1")
            ) {
                console.warn(
                    "This site should be accessed over HTTPS for better security."
                );
            }
        }

        /**
         * Escape HTML entities to prevent XSS
         * @param {string} unsafe - Unsafe string
         * @returns {string} - Safe string
         */
        static escapeHtml(unsafe) {
            if (typeof unsafe !== "string") return unsafe;

            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        /**
         * Parse JWT token (for client-side information only, not for auth)
         * @param {string} token - JWT token
         * @returns {Object|null} - Decoded token or null if invalid
         */
        static parseJwt(token) {
            try {
                const base64Url = token.split(".")[1];
                const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
                const jsonPayload = decodeURIComponent(
                    atob(base64)
                        .split("")
                        .map(function (c) {
                            return (
                                "%" +
                                ("00" + c.charCodeAt(0).toString(16)).slice(-2)
                            );
                        })
                        .join("")
                );

                return JSON.parse(jsonPayload);
            } catch (e) {
                console.error("Error parsing JWT token:", e);
                return null;
            }
        }

        /**
         * Generates a random string for security purposes
         * @param {number} length - Length of string
         * @returns {string} - Random string
         */
        static generateRandomString(length = 32) {
            const charset =
                "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            let result = "";
            const values = new Uint32Array(length);
            window.crypto.getRandomValues(values);

            for (let i = 0; i < length; i++) {
                result += charset[values[i] % charset.length];
            }

            return result;
        }

        /**
         * Get device fingerprint information
         * (For logging purposes only, not for tracking)
         * @returns {Object} - Device fingerprint
         */
        static getDeviceFingerprint() {
            return {
                userAgent: navigator.userAgent,
                language: navigator.language,
                platform: navigator.platform,
                screenWidth: window.screen.width,
                screenHeight: window.screen.height,
                timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                colorDepth: window.screen.colorDepth,
                devicePixelRatio: window.devicePixelRatio || 1,
            };
        }

        /**
         * Check and fix mixed content
         */
        static checkAndFixMixedContent() {
            // Force all AJAX requests to use https if the page is loaded over https
            if (window.location.protocol === "https:" && window.jQuery) {
                $.ajaxPrefilter(function (options) {
                    if (options.url.startsWith("http:")) {
                        options.url = options.url.replace("http:", "https:");
                    }
                });
            }
        }
    }

    // Make available globally
    window.SecurityHelper = SecurityHelper;

    // Automatically initialize when loaded
    document.addEventListener("DOMContentLoaded", SecurityHelper.initialize);
})();

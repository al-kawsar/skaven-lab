document.addEventListener("DOMContentLoaded", function () {
    // Handle drag and drop highlighting
    const uploadZones = document.querySelectorAll(".upload-placeholder");
    uploadZones.forEach((zone) => {
        ["dragenter", "dragover"].forEach((eventName) => {
            zone.addEventListener(eventName, function (e) {
                e.preventDefault();
                this.classList.add("upload-active");
            });
        });

        ["dragleave", "drop"].forEach((eventName) => {
            zone.addEventListener(eventName, function (e) {
                e.preventDefault();
                this.classList.remove("upload-active");
            });
        });

        zone.addEventListener("drop", function (e) {
            e.preventDefault();
            const input =
                this.closest(".upload-label").querySelector(
                    'input[type="file"]'
                );
            if (input) {
                input.files = e.dataTransfer.files;
                // Trigger change event
                const event = new Event("change", { bubbles: true });
                input.dispatchEvent(event);
            }
        });
    });

    // Handle single image preview
    const singleImageInputs = document.querySelectorAll(
        ".single-uploader .image-input"
    );
    singleImageInputs.forEach((input) => {
        input.addEventListener("change", function (event) {
            const container = this.closest(".image-uploader-body");
            const previewCard = container.querySelector(".single-preview-card");
            const noImageMessage = container.querySelector(".no-image-message");
            const previewImg = container.querySelector(".preview-img");
            const filenameEl = container.querySelector(".preview-filename");

            if (this.files && this.files[0]) {
                const file = this.files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    filenameEl.textContent = file.name;
                    previewCard.style.display = "block";
                    if (noImageMessage) noImageMessage.style.display = "none";
                };

                reader.readAsDataURL(file);
            } else {
                previewCard.style.display = "none";
                if (noImageMessage) noImageMessage.style.display = "block";
            }
        });
    });

    // Handle multiple image previews
    const multipleImageInputs = document.querySelectorAll(
        ".multiple-uploader .image-input"
    );
    multipleImageInputs.forEach((input) => {
        input.addEventListener("change", function (event) {
            const container = this.closest(".image-uploader-body");
            const previewGrid = container.querySelector(".preview-grid");
            const noImagesMessage =
                container.querySelector(".no-images-message");

            if (this.files && this.files.length > 0) {
                // Create previews for each file
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const previewItem = document.createElement("div");
                        previewItem.className = "preview-item";
                        previewItem.innerHTML = `
                            <div class="preview-card">
                                <div class="preview-img-container">
                                    <img src="${e.target.result}" class="preview-img" alt="Image Preview">
                                </div>
                                <div class="preview-card-footer">
                                    <span class="preview-filename">${file.name}</span>
                                    <button type="button" class="preview-delete-btn remove-preview">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        previewGrid.appendChild(previewItem);

                        // Hide the "no images" message when there are previews
                        if (noImagesMessage)
                            noImagesMessage.style.display = "none";

                        // Add event listener to remove button
                        const removeButton =
                            previewItem.querySelector(".remove-preview");
                        removeButton.addEventListener("click", function () {
                            previewItem.remove();

                            // Show the "no images" message if no previews left
                            if (
                                previewGrid.children.length === 0 &&
                                noImagesMessage
                            ) {
                                noImagesMessage.style.display = "block";
                            }
                        });
                    };

                    reader.readAsDataURL(file);
                }
            }
        });
    });

    // Handle delete button for existing images
    document.addEventListener("click", function (event) {
        if (
            event.target.classList.contains("delete-image") ||
            event.target.closest(".delete-image")
        ) {
            const button = event.target.classList.contains("delete-image")
                ? event.target
                : event.target.closest(".delete-image");
            const imageId = button.dataset.id;
            const previewItem = button.closest(".preview-item");
            const previewGrid = previewItem.closest(".preview-grid");
            const noImagesMessage = previewItem
                .closest(".preview-gallery")
                .querySelector(".no-images-message");

            if (confirm("Are you sure you want to delete this image?")) {
                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;

                // Send AJAX request to delete the image
                fetch(`/admin/lab-slider/${imageId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        // Success feedback
                        const notification = document.createElement("div");
                        notification.className = "image-delete-notification";
                        notification.textContent = data.message;
                        document.body.appendChild(notification);

                        // Remove after animation
                        setTimeout(() => {
                            notification.classList.add("show");

                            // Fade out and remove
                            setTimeout(() => {
                                notification.classList.remove("show");
                                setTimeout(() => notification.remove(), 300);
                            }, 2000);
                        }, 10);

                        // Remove the preview item with animation
                        previewItem.classList.add("fade-out");
                        setTimeout(() => {
                            previewItem.remove();

                            // Show "no images" message if grid is empty
                            if (
                                previewGrid.children.length === 0 &&
                                noImagesMessage
                            ) {
                                noImagesMessage.style.display = "block";
                            }
                        }, 300);
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        button.innerHTML = '<i class="fas fa-trash-alt"></i>';
                        button.disabled = false;

                        // Error notification
                        const notification = document.createElement("div");
                        notification.className =
                            "image-delete-notification error";
                        notification.textContent =
                            "An error occurred while deleting the image";
                        document.body.appendChild(notification);

                        // Show and remove notification
                        setTimeout(() => {
                            notification.classList.add("show");
                            setTimeout(() => {
                                notification.classList.remove("show");
                                setTimeout(() => notification.remove(), 300);
                            }, 3000);
                        }, 10);
                    });
            }
        }

        // Handle remove button for new image previews
        if (
            event.target.classList.contains("remove-preview") ||
            event.target.closest(".remove-preview")
        ) {
            const button = event.target.classList.contains("remove-preview")
                ? event.target
                : event.target.closest(".remove-preview");
            const previewItem = button.closest(".preview-item");

            if (previewItem) {
                const previewGrid = previewItem.closest(".preview-grid");
                const noImagesMessage = previewItem
                    .closest(".preview-gallery")
                    .querySelector(".no-images-message");

                // Animate removal
                previewItem.classList.add("fade-out");
                setTimeout(() => {
                    previewItem.remove();

                    // Show "no images" message if grid is empty
                    if (previewGrid.children.length === 0 && noImagesMessage) {
                        noImagesMessage.style.display = "block";
                    }
                }, 300);
            }
        }
    });

    // Add animation CSS
    const style = document.createElement("style");
    style.textContent = `
        .upload-active {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .fade-out {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.3s, transform 0.3s;
        }
        
        .image-delete-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 4px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.16);
            z-index: 1050;
            transform: translateY(30px);
            opacity: 0;
            transition: transform 0.3s, opacity 0.3s;
        }
        
        .image-delete-notification.error {
            background-color: #dc3545;
        }
        
        .image-delete-notification.show {
            transform: translateY(0);
            opacity: 1;
        }
    `;
    document.head.appendChild(style);
});

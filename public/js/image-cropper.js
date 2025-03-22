document.addEventListener("DOMContentLoaded", function () {
    // Initialize cropper objects
    let thumbnailCropper = null;
    let sliderCropper = null;
    let currentSliderFile = null;
    let sliderFiles = [];
    let currentSliderIndex = 0;
    let croppedSliderImages = [];

    // Set up thumbnail cropper
    const thumbnailInput = document.getElementById("thumbnail");
    if (thumbnailInput) {
        thumbnailInput.addEventListener("change", function (e) {
            const file = this.files[0];
            if (!file) return;

            // Show the cropper container
            document.getElementById(
                "thumbnail-cropper-container"
            ).style.display = "block";

            // Create file reader to display the image
            const reader = new FileReader();
            reader.onload = function (event) {
                const img = document.getElementById("thumbnail-cropper-img");
                img.src = event.target.result;

                // Destroy previous cropper if exists
                if (thumbnailCropper) {
                    thumbnailCropper.destroy();
                }

                // Initialize cropper
                thumbnailCropper = new Cropper(img, {
                    aspectRatio: 16 / 9, // Fixed aspect ratio for thumbnails
                    viewMode: 1,
                    zoomable: true,
                    scalable: true,
                    minCropBoxWidth: 100,
                    minCropBoxHeight: 100,
                });
            };

            reader.readAsDataURL(file);
        });
    }

    // Set up slider images cropper
    const sliderInput = document.getElementById("slider_images");
    if (sliderInput) {
        sliderInput.addEventListener("change", function (e) {
            // Reset slider files and indexes
            sliderFiles = Array.from(this.files);
            currentSliderIndex = 0;
            croppedSliderImages = [];

            if (sliderFiles.length > 0) {
                // Process first file
                processNextSliderImage();
            }
        });
    }

    // Function to process next slider image
    function processNextSliderImage() {
        if (currentSliderIndex < sliderFiles.length) {
            currentSliderFile = sliderFiles[currentSliderIndex];

            // Show the cropper container
            document.getElementById("slider-cropper-container").style.display =
                "block";
            document.getElementById(
                "slider-cropper-info"
            ).textContent = `Processing image ${currentSliderIndex + 1} of ${
                sliderFiles.length
            }`;

            // Create file reader to display the image
            const reader = new FileReader();
            reader.onload = function (event) {
                const img = document.getElementById("slider-cropper-img");
                img.src = event.target.result;

                // Destroy previous cropper if exists
                if (sliderCropper) {
                    sliderCropper.destroy();
                }

                // Initialize cropper
                sliderCropper = new Cropper(img, {
                    aspectRatio: null, // Free aspect ratio for slider images
                    viewMode: 1,
                    zoomable: true,
                    scalable: true,
                    minCropBoxWidth: 100,
                    minCropBoxHeight: 100,
                });
            };

            reader.readAsDataURL(currentSliderFile);
        } else {
            // All files processed, hide cropper
            document.getElementById("slider-cropper-container").style.display =
                "none";
                
            // Show the slider gallery container if there are any processed images
            if (croppedSliderImages.length > 0) {
                document.getElementById("slider-gallery-container").style.display = "block";
            }
        }
    }

    // Thumbnail crop button handler
    const thumbnailCropBtn = document.getElementById("thumbnail-crop-btn");
    if (thumbnailCropBtn) {
        thumbnailCropBtn.addEventListener("click", function () {
            if (!thumbnailCropper) return;

            // Get the cropped canvas
            const canvas = thumbnailCropper.getCroppedCanvas({
                minWidth: 256,
                minHeight: 144,
                maxWidth: 4096,
                maxHeight: 2160,
            });

            if (!canvas) return;

            // Convert to data URL and display preview
            const dataUrl = canvas.toDataURL("image/jpeg");
            document.getElementById("thumbnail-preview").src = dataUrl;
            document.getElementById("thumbnail-preview").style.display =
                "block";
            document.getElementById("thumbnail-cropped-data").value = dataUrl;

            // Hide the cropper
            document.getElementById(
                "thumbnail-cropper-container"
            ).style.display = "none";
        });
    }

    // Slider crop button handler
    const sliderCropBtn = document.getElementById("slider-crop-btn");
    if (sliderCropBtn) {
        sliderCropBtn.addEventListener("click", function () {
            if (!sliderCropper) return;

            // Get the cropped canvas
            const canvas = sliderCropper.getCroppedCanvas({
                minWidth: 100,
                minHeight: 100,
                maxWidth: 4096,
                maxHeight: 4096,
            });

            if (!canvas) return;

            // Convert to data URL
            const dataUrl = canvas.toDataURL("image/jpeg");

            // Create preview item
            addSliderPreview(dataUrl);

            // Store cropped image data
            croppedSliderImages.push(dataUrl);

            // Move to next image
            currentSliderIndex++;
            processNextSliderImage();
        });
    }

    // Skip button for slider images
    const sliderSkipBtn = document.getElementById("slider-skip-btn");
    if (sliderSkipBtn) {
        sliderSkipBtn.addEventListener("click", function () {
            // Skip current image
            currentSliderIndex++;
            processNextSliderImage();
        });
    }

    // Zoom in buttons
    const thumbnailZoomIn = document.getElementById("thumbnail-zoom-in");
    if (thumbnailZoomIn) {
        thumbnailZoomIn.addEventListener("click", function () {
            if (thumbnailCropper) thumbnailCropper.zoom(0.1);
        });
    }

    const sliderZoomIn = document.getElementById("slider-zoom-in");
    if (sliderZoomIn) {
        sliderZoomIn.addEventListener("click", function () {
            if (sliderCropper) sliderCropper.zoom(0.1);
        });
    }

    // Zoom out buttons
    const thumbnailZoomOut = document.getElementById("thumbnail-zoom-out");
    if (thumbnailZoomOut) {
        thumbnailZoomOut.addEventListener("click", function () {
            if (thumbnailCropper) thumbnailCropper.zoom(-0.1);
        });
    }

    const sliderZoomOut = document.getElementById("slider-zoom-out");
    if (sliderZoomOut) {
        sliderZoomOut.addEventListener("click", function () {
            if (sliderCropper) sliderCropper.zoom(-0.1);
        });
    }

    // Function to add slider preview
    function addSliderPreview(dataUrl) {
        const container = document.getElementById("slider-preview-container");
        
        if (!container) return;

        // Check if this preview already exists by comparing data URLs
        const existingPreviews = container.querySelectorAll('img');
        for (let i = 0; i < existingPreviews.length; i++) {
            if (existingPreviews[i].src === dataUrl) {
                // Skip adding duplicate preview
                return;
            }
        }

        // Create preview item
        const preview = document.createElement("div");
        preview.className = "col-md-3 mb-3";
        preview.innerHTML = `
            <div class="card">
                <img src="${dataUrl}" class="card-img-top" alt="Slider Preview" style="height: 150px; object-fit: cover;">
                <div class="card-body p-2">
                    <button type="button" class="btn btn-sm btn-danger remove-preview">Remove</button>
                </div>
            </div>
        `;

        // Add to container
        container.appendChild(preview);

        // Handle remove button
        preview
            .querySelector(".remove-preview")
            .addEventListener("click", function () {
                const index = Array.from(container.children).indexOf(preview);
                if (index !== -1) {
                    croppedSliderImages.splice(index, 1);
                    preview.remove();
                    
                    // Hide the container if there are no more previews
                    if (container.children.length === 0) {
                        const galleryContainer = document.getElementById("slider-gallery-container");
                        if (galleryContainer) {
                            galleryContainer.style.display = "none";
                        }
                    }
                }
            });
    }

    // Handle form submission
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function (e) {
            // Remove previous crop data inputs to avoid duplicates
            form.querySelectorAll('input[name^="slider_cropped_images"]').forEach(input => {
                input.remove();
            });
            
            // For slider images, we need to add the cropped images
            if (croppedSliderImages.length > 0) {
                // Append each cropped slider image to the form
                croppedSliderImages.forEach((dataUrl, index) => {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = `slider_cropped_images[${index}]`;
                    input.value = dataUrl;
                    form.appendChild(input);
                });
            }
            
            // Form will continue submitting normally
        });
    }
});

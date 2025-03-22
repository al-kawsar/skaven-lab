document.addEventListener("DOMContentLoaded", function () {
    // Handle single image preview
    const singleImageInputs = document.querySelectorAll(
        ".image-input:not([multiple])"
    );
    singleImageInputs.forEach((input) => {
        input.addEventListener("change", function (event) {
            const previewId = `${this.id}-preview`;
            const preview = document.getElementById(previewId);

            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };

                reader.readAsDataURL(this.files[0]);
            } else if (preview) {
                preview.style.display = "none";
            }
        });
    });

    // Handle multiple image previews
    const multipleImageInputs = document.querySelectorAll(
        ".image-input[multiple]"
    );
    multipleImageInputs.forEach((input) => {
        input.addEventListener("change", function (event) {
            const containerId = `${this.id}-preview-container`;
            const container = document.getElementById(containerId);

            if (container && this.files && this.files.length > 0) {
                // Create previews for each file
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const previewItem = document.createElement("div");
                        previewItem.className = "col-md-3 mb-3 preview-item";
                        previewItem.innerHTML = `
                            <div class="card h-100">
                                <img src="${e.target.result}" class="card-img-top" alt="Image Preview" 
                                     style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                    <button type="button" class="btn btn-sm btn-danger remove-preview">Remove</button>
                                </div>
                            </div>
                        `;
                        container.appendChild(previewItem);

                        // Add event listener to remove button
                        const removeButton =
                            previewItem.querySelector(".remove-preview");
                        removeButton.addEventListener("click", function () {
                            previewItem.remove();
                        });
                    };

                    reader.readAsDataURL(file);
                }
            }
        });
    });

    // Handle delete button for existing images
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("delete-slider")) {
            const button = event.target;
            const imageId = button.dataset.id;
            const previewItem = button.closest(".preview-item");

            if (confirm("Are you sure you want to delete this image?")) {
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
                        alert(data.message);
                        // Remove the slider image div from the DOM
                        previewItem.remove();
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert("An error occurred while deleting the image");
                    });
            }
        }

        // Handle remove button for new image previews
        if (event.target.classList.contains("remove-preview")) {
            const previewItem = event.target.closest(".preview-item");
            if (previewItem) {
                previewItem.remove();
            }
        }
    });
});

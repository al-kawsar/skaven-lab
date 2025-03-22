@extends('layouts.app-layout')
@section('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/image-cropper.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Add Labs</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.lab.index') }}">Labs</a></li>
                        <li class="breadcrumb-item active">Add Labs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <form id="createLabForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Lab Information <span><a href="javascript:;"><i
                                                class="feather-more-vertical"></i></a></span></h5>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Name <span class="login-danger">*</span></label>
                                    <input required class="form-control" name="name" type="text"
                                        placeholder="Enter Lab Name">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label for="description">Deskripsi <span class="login-danger">*</span></label>
                                    <textarea id="description" class="form-control" rows="5" name="facilities" required
                                        placeholder="Enter lab description or facilities"></textarea>
                                </div>
                            </div>

                            <!-- Thumbnail Upload -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="thumbnail">Thumbnail</label>

                                    <!-- Drop Area for Thumbnail -->
                                    <div id="thumbnail-drop-area" class="drop-area">
                                        <p>Drag & drop gambar di sini atau klik untuk memilih file<br>
                                            <small>Anda juga bisa paste (Ctrl+V) gambar langsung dari clipboard</small>
                                        </p>
                                        <input type="file" name="thumbnail" id="thumbnail"
                                            class="form-control file-input"
                                            accept="image/jpeg,image/png,image/webp,image/jpg">
                                    </div>

                                    <small class="text-muted">Upload thumbnail untuk lab. Anda dapat crop dan zoom gambar
                                        tersebut.</small>

                                    <!-- Hidden input for cropped data -->
                                    <input type="hidden" id="thumbnail-cropped-data" name="thumbnail_cropped_data">
                                </div>

                                <!-- Thumbnail Cropper -->
                                <div id="thumbnail-cropper-container" class="cropper-wrapper">
                                    <div class="crop-info">Crop Thumbnail Image</div>
                                    <div class="btn-group mb-3">
                                        <button type="button" id="thumbnail-zoom-in"
                                            class="btn btn-sm btn-info btn-cropper">
                                            <i class="fas fa-search-plus"></i> Zoom In
                                        </button>
                                        <button type="button" id="thumbnail-zoom-out"
                                            class="btn btn-sm btn-info btn-cropper">
                                            <i class="fas fa-search-minus"></i> Zoom Out
                                        </button>
                                        <button type="button" id="thumbnail-crop-btn"
                                            class="btn btn-sm btn-success btn-cropper">
                                            <i class="fas fa-crop"></i> Crop
                                        </button>
                                    </div>
                                    <div class="cropper-container">
                                        <img id="thumbnail-cropper-img" src="" alt="Thumbnail to crop">
                                    </div>
                                </div>

                                <!-- Thumbnail Preview -->
                                <div class="img-preview-container">
                                    <img id="thumbnail-preview" src="" alt="Thumbnail Preview"
                                        style="object-fit: cover; display: none; width: 100%; height: 250px">
                                </div>
                            </div>

                            <!-- Slider Images Upload -->
                            <div class="col-12 mt-4">
                                <div class="form-group">
                                    <label for="slider_images">Slider Images (Multiple)</label>
                                    <!-- Drop Area for Slider Images -->
                                    <div id="slider-drop-area" class="drop-area">
                                        <p>Drag & drop gambar di sini atau klik untuk memilih file<br>
                                            <small>Anda juga bisa paste (Ctrl+V) gambar langsung dari clipboard</small>
                                        </p>
                                        <input type="file" name="slider_images[]" id="slider_images"
                                            class="form-control file-input"
                                            accept="image/jpeg,image/png,image/webp,image/jpg" multiple>
                                    </div>

                                    <small class="text-muted">Tambahkan gambar slider untuk lab. Format yang didukung: JPG,
                                        JPEG, PNG, WEBP. Maksimal 5MB per file.</small>
                                </div>

                                <!-- Slider Cropper -->
                                <div id="slider-cropper-container" class="cropper-wrapper">
                                    <div id="slider-cropper-info" class="crop-info">Crop Slider Image</div>
                                    <div class="btn-group mb-3">
                                        <button type="button" id="slider-zoom-in"
                                            class="btn btn-sm btn-info btn-cropper">
                                            <i class="fas fa-search-plus"></i> Zoom In
                                        </button>
                                        <button type="button" id="slider-zoom-out"
                                            class="btn btn-sm btn-info btn-cropper">
                                            <i class="fas fa-search-minus"></i> Zoom Out
                                        </button>
                                        <button type="button" id="slider-crop-btn"
                                            class="btn btn-sm btn-success btn-cropper">
                                            <i class="fas fa-crop"></i> Crop
                                        </button>
                                        <button type="button" id="slider-skip-btn"
                                            class="btn btn-sm btn-secondary btn-cropper">
                                            <i class="fas fa-forward"></i> Skip
                                        </button>
                                    </div>
                                    <div class="cropper-container">
                                        <img id="slider-cropper-img" src="" alt="Slider image to crop">
                                    </div>
                                </div>

                                <!-- Slider Preview Container -->
                                <div class="mt-3" id="slider-gallery-container" style="display: none;">
                                    <h6>Slider Images Preview</h6>
                                    <div class="row" id="slider-preview-container">
                                        <!-- Slider previews will be added here dynamically -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="student-submit">
                                    <button type="submit" id="btn-submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="{{ asset('js/image-cropper.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Implementasi Drag & Drop dan Copy Paste untuk Thumbnail
            setupDragDropAndPaste('thumbnail-drop-area', 'thumbnail', processThumbnailImage);

            // Implementasi Drag & Drop dan Copy Paste untuk Slider Images
            setupDragDropAndPaste('slider-drop-area', 'slider_images', processSliderImages);

            // Klik pada drop area akan memicu klik pada input file
            $('#thumbnail-drop-area').on('click', function(e) {
                // Jika klik terjadi pada area drop dan bukan pada input, trigger klik pada input
                if (e.target !== $('#thumbnail')[0]) {
                    $('#thumbnail').click();
                }
            });

            $('#slider-drop-area').on('click', function(e) {
                // Jika klik terjadi pada area drop dan bukan pada input, trigger klik pada input
                if (e.target !== $('#slider_images')[0]) {
                    $('#slider_images').click();
                }
            });

            // Event listener untuk form submission
            $('#createLabForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                $('#btn-submit').text("Loading...");
                const formData = new FormData($(this)[0]); // Get form data
                const url = "{{ route('admin.lab.store') }}";

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Show success message
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message || 'Lab berhasil ditambahkan',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirect to index page after success
                            window.location.href = "{{ route('admin.lab.index') }}";
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat menambahkan lab',
                            icon: 'error'
                        });
                        $('#btn-submit').text("Submit"); // Reset button text
                    },
                    complete: function() {
                        $('#btn-submit').text("Submit"); // Reset button text
                    }
                });
            });

            // Function untuk setup drag & drop dan paste
            function setupDragDropAndPaste(dropAreaId, inputId, processFunction) {
                const dropArea = document.getElementById(dropAreaId);
                const input = document.getElementById(inputId);

                // Prevent default drag behaviors
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, preventDefaults, false);
                    document.body.addEventListener(eventName, preventDefaults, false);
                });

                // Highlight drop area when item is dragged over it
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropArea.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, unhighlight, false);
                });

                // Handle dropped files
                dropArea.addEventListener('drop', handleDrop, false);

                // Handle paste events on the document
                document.addEventListener('paste', handlePaste, false);

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                function highlight() {
                    dropArea.classList.add('highlight');
                }

                function unhighlight() {
                    dropArea.classList.remove('highlight');
                }

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (inputId === 'thumbnail') {
                        // Single file for thumbnail
                        if (files.length > 0) {
                            processFunction(files[0]);
                        }
                    } else {
                        // Multiple files for slider
                        processFunction(files);
                    }
                }

                function handlePaste(e) {
                    // Check if drop area is focused or active
                    if (document.activeElement === dropArea || dropArea.contains(document.activeElement) ||
                        dropArea.matches(':hover')) {
                        const items = e.clipboardData.items;

                        for (let i = 0; i < items.length; i++) {
                            if (items[i].type.indexOf('image') !== -1) {
                                const blob = items[i].getAsFile();

                                if (inputId === 'thumbnail') {
                                    processFunction(blob);
                                    break; // Only process one image for thumbnail
                                } else {
                                    // For slider, create a FileList-like object
                                    const dT = new DataTransfer();
                                    dT.items.add(blob);
                                    processFunction(dT.files);
                                }
                            }
                        }
                    }
                }
            }

            // Process thumbnail image
            function processThumbnailImage(file) {
                // Create a fake event with files
                const dT = new DataTransfer();
                dT.items.add(file);

                // Set the file to the input
                document.getElementById('thumbnail').files = dT.files;

                // Trigger the change event
                const event = new Event('change');
                document.getElementById('thumbnail').dispatchEvent(event);
            }

            // Process slider images
            function processSliderImages(files) {
                // Create a fake event with files
                const dT = new DataTransfer();

                // Add each file to the DataTransfer object
                for (let i = 0; i < files.length; i++) {
                    dT.items.add(files[i]);
                }

                // Set the files to the input
                document.getElementById('slider_images').files = dT.files;

                // Trigger the change event
                const event = new Event('change');
                document.getElementById('slider_images').dispatchEvent(event);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

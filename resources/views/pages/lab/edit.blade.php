@extends('layouts.app-layout')
@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/image-cropper.css') }}">
@endsection

@section('content')
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-sub-header">
                    <h3 class="page-title">Edit Labs</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('labs.index') }}">Labs</a></li>
                        <li class="breadcrumb-item active" id="lab-name">{{ $lab->name }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card comman-shadow">
                <div class="card-body">
                    <form id="editLabForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <h5 class="form-title student-info">Lab Information <span><a href="javascript:;"><i
                                                class="feather-more-vertical"></i></a></span></h5>
                            </div>
                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label>Name <span class="login-danger">*</span></label>
                                    <input required class="form-control" name="name" type="text"
                                        value="{{ old('name', $lab->name) }}" placeholders="Enter First Name">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group local-forms">
                                    <label for="description">Deskripsi <span class="login-danger">*</span></label>
                                    <textarea id="description" class="form-control" rows="5" name="facilities" required>{!! $lab->facilities !!}</textarea>
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
                                            class="form-control file-input">
                                    </div>

                                    <small class="text-muted">Upload gambar baru untuk mengganti thumbnail saat ini. Anda
                                        dapat crop dan zoom gambar tersebut.</small>

                                    <!-- Hidden input for cropped data -->
                                    <input type="hidden" id="thumbnail-cropped-data" name="thumbnail_cropped_data">
                                </div>

                                <!-- Current Thumbnail -->
                                @if ($lab->file)
                                    <div class="current-image" id="current-thumbnail-container">
                                        <h6>Current Thumbnail</h6>
                                        <img src="{{ $lab->file->full_path }}" alt="Current Thumbnail" class="img-thumbnail"
                                            style="max-width: 200px;" id="current-thumbnail">
                                    </div>
                                @endif

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
                                        <p>Drag & drop gambar di sini atau klik untuk memilih file (multiple)<br>
                                            <small>Anda juga bisa paste (Ctrl+V) gambar langsung dari clipboard</small>
                                        </p>
                                        <input type="file" name="slider_images[]" id="slider_images"
                                            class="form-control file-input" multiple>
                                    </div>

                                    <small class="text-muted">Tambahkan lebih banyak gambar ke slider. Setiap gambar dapat
                                        dicrop secara individual.</small>
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

                                <!-- Current Slider Images -->
                                <div class="mt-3" id="slider-gallery-container"
                                    style="{{ $lab->sliderImages->count() > 0 ? '' : 'display: none;' }}">
                                    <h6>Current Slider Images</h6>
                                    <div class="row" id="slider-preview-container">
                                        @foreach ($lab->sliderImages as $slider)
                                            <div class="col-md-3 mb-3 slider-item">
                                                <div class="card">
                                                    <img src="{{ $slider->file->full_path }}" class="card-img-top"
                                                        alt="Slider Image" style="height: 150px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <button type="button" class="btn btn-sm btn-danger delete-slider"
                                                            data-id="{{ $slider->id }}">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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

            // Track form changes to enable/disable submit button
            let formChanged = false;
            const originalFormData = $('#editLabForm').serialize();

            // Monitor form changes
            $('#editLabForm input, #editLabForm textarea').on('change input', function() {
                checkFormChanges();
            });

            // Monitor file inputs separately
            $('#thumbnail, #slider_images').on('change', function() {
                formChanged = true;
                $('#btn-submit').prop('disabled', false);
            });

            // Function to check if form has changed
            function checkFormChanges() {
                const currentFormData = $('#editLabForm').serialize();
                formChanged = currentFormData !== originalFormData ||
                    $('#thumbnail').val() !== '' ||
                    $('#slider_images').val() !== '' ||
                    $('#thumbnail-cropped-data').val() !== '';

                // Disable submit button if no changes
                $('#btn-submit').prop('disabled', !formChanged);
            }

            // Initial check
            $('#btn-submit').prop('disabled', true);

            // Event listener untuk form submission
            $('#editLabForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                // Only proceed if form has changes
                if (!formChanged) {
                    return false;
                }

                $('#btn-submit').text("Loading...");
                const formData = new FormData($(this)[0]); // Get form data
                const url = "{{ route('labs.update', $lab->id) }}";

                formData.append('_method', 'PUT'); // Add method for PUT request

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
                            text: response.message || 'Lab berhasil diubah',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Update the page elements with new data
                        updatePageElements(response.data);

                        // Reset form elements and previews
                        resetFormElements();

                        // Reset form changed status
                        formChanged = false;
                        $('#btn-submit').prop('disabled', true);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON?.message ||
                                'Terjadi kesalahan saat memperbarui lab',
                            icon: 'error'
                        });
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
                    // Perbaikan agar paste berfungsi lebih baik - cek jika area aktif atau cursor berada di atasnya
                    if (document.activeElement === dropArea ||
                        dropArea.contains(document.activeElement) ||
                        dropArea.matches(':hover') ||
                        document.activeElement === document.body) { // Tambahan untuk paste saat fokus di body

                        const items = e.clipboardData.items;
                        let foundImage = false;

                        for (let i = 0; i < items.length; i++) {
                            if (items[i].type.indexOf('image') !== -1) {
                                foundImage = true;
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

                        // Jika gambar ditemukan dan diproses, mencegah default paste
                        if (foundImage) {
                            e.preventDefault();
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

            // Function to update page elements with new data
            function updatePageElements(data) {
                if (!data) return;

                // Update lab name and description
                if (data.name) {
                    $('input[name="name"]').val(data.name);
                    $('#lab-name').text(data.name);
                }

                if (data.facilities) {
                    $('#description').val(data.facilities);
                }

                // Update thumbnail if new one was uploaded
                if (data.thumbnail_url) {
                    $('#current-thumbnail').attr('src', data.thumbnail_url);
                    $('#current-thumbnail-container').show();
                }

                // Clear cropper containers
                $('#thumbnail-cropper-container').hide();
                $('#slider-cropper-container').hide();

                // Update slider images if new ones were added
                if (data.slider_images && data.slider_images.length > 0) {
                    // Show the slider gallery container
                    $('#slider-gallery-container').show();

                    // JANGAN menghapus slider yang sudah ada
                    // Hanya tambahkan slider baru yang belum ada
                }
            }

            function resetFormElements() {
                // Reset file inputs
                $('#thumbnail').val('');
                $('#slider_images').val('');

                // Reset cropped data
                $('#thumbnail-cropped-data').val('');

                // Hide previews
                $('#thumbnail-preview').hide();

                // Hapus visual "highlight" dari drop area jika masih ada
                $('.drop-area').removeClass('highlight');
            }

            $(document).on('click', '.delete-slider', function() {
                const sliderId = $(this).data('id');
                const slideItem = $(this).closest('.slider-item');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus gambar slider ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('labs.slider.destroy', '') }}/" + sliderId,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            success: function(response) {
                                slideItem.fadeOut(300, function() {
                                    $(this).remove();

                                    // Check if there are any remaining slider images
                                    if ($(
                                            '#slider-preview-container .slider-item'
                                        )
                                        .length === 0) {
                                        $('#slider-gallery-container').hide();
                                    }
                                });

                                // Show small notification
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            },
                            error: function(error) {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat menghapus gambar slider'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

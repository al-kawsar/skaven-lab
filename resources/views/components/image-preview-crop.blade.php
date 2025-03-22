<!-- resources/views/components/image-preview-crop.blade.php -->
@props([
    'inputName' => 'image',
    'label' => 'Upload Image',
    'multiple' => false,
    'required' => false,
    'accept' => '.png,.jpeg,.jpg,.webp',
    'currentImage' => null,
    'aspectRatio' => null,
    'previewHeight' => '250px',
    'minWidth' => 100,
    'minHeight' => 100,
])

<div class="image-preview-crop-component">
    <div class="form-group">
        <label for="{{ $inputName }}">{{ $label }} {!! $required ? '<span class="text-danger">*</span>' : '' !!}</label>

        <div class="custom-file">
            <input type="file" class="custom-file-input image-input" id="{{ $inputName }}"
                name="{{ $inputName }}{{ $multiple ? '[]' : '' }}" accept="{{ $accept }}"
                {{ $multiple ? 'multiple' : '' }} {{ $required ? 'required' : '' }}>
            <label class="custom-file-label" for="{{ $inputName }}">Choose file</label>
        </div>

        @if ($multiple)
            <small class="text-muted">You can select multiple images</small>
        @endif
    </div>

    @if (!$multiple)
        <!-- Single Image Preview and Crop -->
        <div class="single-image-preview-area mt-3" style="display: none;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Image</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-info zoom-in-btn">
                            <i class="fas fa-search-plus"></i> Zoom In
                        </button>
                        <button type="button" class="btn btn-sm btn-info zoom-out-btn">
                            <i class="fas fa-search-minus"></i> Zoom Out
                        </button>
                        <button type="button" class="btn btn-sm btn-success crop-btn">
                            <i class="fas fa-crop"></i> Crop
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="img-container">
                        <img id="{{ $inputName }}-cropper" src="" alt="Cropper Image">
                    </div>
                </div>
            </div>
        </div>

        <!-- Cropped Image Result -->
        <div class="cropped-preview mt-3" id="{{ $inputName }}-preview-container">
            @if (isset($currentImage) && !empty($currentImage))
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Current Image</h5>
                    </div>
                    <div class="card-body">
                        <img src="{{ $currentImage['url'] }}" alt="Current Image" class="img-fluid"
                            style="max-height: {{ $previewHeight }};">
                    </div>
                </div>
            @else
                <div class="card" style="display: none;" id="{{ $inputName }}-result-card">
                    <div class="card-header">
                        <h5 class="mb-0">Final Image</h5>
                    </div>
                    <div class="card-body">
                        <img id="{{ $inputName }}-result" src="" alt="Cropped Image" class="img-fluid"
                            style="max-height: {{ $previewHeight }};">
                    </div>
                </div>
            @endif
            <!-- Hidden input to store the cropped image data -->
            <input type="hidden" id="{{ $inputName }}-cropped-data" name="{{ $inputName }}_cropped">
        </div>
    @else
        <!-- Multiple Images Preview and Crop -->
        <div class="multiple-image-preview-area mt-3" style="display: none;">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Image</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-info zoom-in-btn">
                            <i class="fas fa-search-plus"></i> Zoom In
                        </button>
                        <button type="button" class="btn btn-sm btn-info zoom-out-btn">
                            <i class="fas fa-search-minus"></i> Zoom Out
                        </button>
                        <button type="button" class="btn btn-sm btn-success crop-btn">
                            <i class="fas fa-crop"></i> Crop
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="img-container">
                        <img id="{{ $inputName }}-cropper" src="" alt="Cropper Image">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <h5>Image Gallery</h5>
            <div class="row" id="{{ $inputName }}-preview-container">
                @if (isset($currentImage) && !empty($currentImage))
                    @foreach ($currentImage as $image)
                        <div class="col-md-3 mb-3 preview-item">
                            <div class="card h-100">
                                <img src="{{ $image['url'] }}" class="card-img-top" alt="Image Preview"
                                    style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                    @if (isset($image['id']))
                                        <button type="button" class="btn btn-sm btn-danger delete-slider"
                                            data-id="{{ $image['id'] }}">Delete</button>
                                    @else
                                        <button type="button"
                                            class="btn btn-sm btn-danger remove-preview">Remove</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</div>

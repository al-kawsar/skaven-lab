@props([
    'name' => 'image',
    'label' => 'Upload Image',
    'accept' => '.png,.jpeg,.jpg,.webp',
    'multiple' => false,
    'required' => false,
    'currentImage' => null,
    'previewHeight' => '250px',
    'maxFileSize' => '2048', // 2MB default
])

<div class="image-uploader-container">
    <div class="image-uploader-header">
        <h4 class="image-uploader-title">{{ $label }}
            {{ $required ? '<span class="required-indicator">*</span>' : '' }}</h4>
        <div class="image-uploader-info">
            <span class="file-types">Allowed: {{ str_replace(',', ', ', $accept) }}</span>
            <span class="file-size">Max: {{ $maxFileSize / 1024 }}MB</span>
        </div>
    </div>

    <div class="image-uploader-body {{ $multiple ? 'multiple-uploader' : 'single-uploader' }}">
        <div class="upload-zone">
            <label for="{{ $name }}" class="upload-label">
                <div class="upload-placeholder">
                    <i class="upload-icon fas fa-cloud-upload-alt"></i>
                    <p class="upload-text">
                        {{ $multiple ? 'Drop files here or click to browse' : 'Drop file here or click to browse' }}</p>
                    <p class="upload-hint text-muted">
                        {{ $multiple ? 'You can select multiple files' : 'Select one file to upload' }}</p>
                </div>
                <input type="file" id="{{ $name }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                    class="image-input" accept="{{ $accept }}" {{ $multiple ? 'multiple' : '' }}
                    {{ $required ? 'required' : '' }}>
            </label>
        </div>

        @if ($multiple)
            <div class="preview-gallery mt-3" id="{{ $name }}-preview-container">
                <h5 class="preview-title">Image Gallery</h5>
                <div class="preview-grid">
                    @if ($currentImage && is_array($currentImage))
                        @foreach ($currentImage as $image)
                            <div class="preview-item" data-id="{{ $image['id'] ?? '' }}">
                                <div class="preview-card">
                                    <div class="preview-img-container">
                                        <img src="{{ $image['url'] }}" class="preview-img" alt="Image Preview">
                                    </div>
                                    <div class="preview-card-footer">
                                        @if (isset($image['id']))
                                            <span class="preview-filename">{{ basename($image['url']) }}</span>
                                            <button type="button" class="preview-delete-btn delete-image"
                                                data-id="{{ $image['id'] }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @else
                                            <span class="preview-filename">New Upload</span>
                                            <button type="button" class="preview-delete-btn remove-preview">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="no-images-message"
                    style="{{ $currentImage && is_array($currentImage) && count($currentImage) > 0 ? 'display: none;' : '' }}">
                    No images selected yet
                </div>
            </div>
        @else
            <div class="single-preview-container mt-3" id="{{ $name }}-preview-container">
                <h5 class="preview-title">Image Preview</h5>
                <div class="single-preview-wrapper">
                    @if ($currentImage && !is_array($currentImage))
                        <div class="single-preview-card">
                            <div class="preview-img-container">
                                <img src="{{ $currentImage['url'] }}" alt="Image Preview" class="preview-img"
                                    style="max-height: {{ $previewHeight }}">
                            </div>
                            <div class="preview-card-footer">
                                <span class="preview-filename">{{ basename($currentImage['url']) }}</span>
                            </div>
                        </div>
                    @else
                        <div class="single-preview-card" style="display: none;">
                            <div class="preview-img-container">
                                <img id="{{ $name }}-preview" src="" alt="Image Preview"
                                    class="preview-img" style="max-height: {{ $previewHeight }}">
                            </div>
                            <div class="preview-card-footer">
                                <span class="preview-filename" id="{{ $name }}-filename"></span>
                            </div>
                        </div>
                        <div class="no-image-message">
                            No image selected yet
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

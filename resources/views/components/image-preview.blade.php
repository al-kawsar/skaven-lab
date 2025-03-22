@props([
    'inputName' => 'image',
    'label' => 'Upload Image',
    'multiple' => false,
    'required' => false,
    'accept' => '.png,.jpeg,.jpg,.webp',
    'currentImage' => null,
    'previewHeight' => '250px',
])

<div class="image-preview-component">
    <!-- Label Section -->
    <div class="form-group">
        <label for="{{ $inputName }}">{{ $label }} {!! $required ? '<span class="text-danger">*</span>' : '' !!}</label>

        <!-- Input Field -->
        <input type="file" name="{{ $inputName }}{{ $multiple ? '[]' : '' }}" id="{{ $inputName }}"
            class="form-control image-input" accept="{{ $accept }}" {{ $multiple ? 'multiple' : '' }}
            {{ $required ? 'required' : '' }}>

        @if ($multiple)
            <small class="text-muted">You can select multiple images</small>
        @endif
    </div>

    <!-- Preview Area -->
    <div class="preview-area mt-2">
        @if ($multiple)
            <!-- Multiple Images Preview Area -->
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
        @else
            <!-- Single Image Preview Area -->
            <div id="{{ $inputName }}-preview-container">
                @if (isset($currentImage) && !empty($currentImage))
                    <img src="{{ $currentImage['url'] }}" alt="Image Preview" class="img-thumbnail"
                        style="max-height: {{ $previewHeight }};">
                @else
                    <img id="{{ $inputName }}-preview" src="" alt="Image Preview" class="img-thumbnail"
                        style="display: none; max-height: {{ $previewHeight }};">
                @endif
            </div>
        @endif
    </div>
</div>

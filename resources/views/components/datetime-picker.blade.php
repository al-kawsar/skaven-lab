@props([
    'type' => 'date', // Either 'date' or 'time'
    'name' => '', // Input name attribute
    'id' => '', // Input id attribute
    'label' => '', // Label text
    'placeholder' => 'Pilih ' . ($type == 'date' ? 'Tanggal' : 'Waktu'),
    'required' => false,
    'defaultValue' => null,
])

<div class="form-group local-forms {{ $type }}-input-container">
    @if ($label)
        <label class="text-dark" for="{{ $id }}">{{ $label }}
            @if ($required)
                <span class="login-danger">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $type }}-picker-wrapper">
        <input id="{{ $id }}" {{ $required ? 'required' : '' }} class="form-control {{ $type }}-input"
            name="{{ $name }}" type="text" placeholder="{{ $placeholder }}" readonly autocomplete="off"
            aria-label="{{ $label }}" {{ $attributes }}>
        <div class="{{ $type }}-picker-icon" aria-hidden="true">
            <i class="fas fa-{{ $type == 'date' ? 'calendar-alt' : 'clock' }}"></i>
        </div>
    </div>
    <div id="{{ $id }}-container" class="modern-{{ $type == 'date' ? 'date' : 'time' }}picker-container"
        aria-hidden="true"></div>
</div>

<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImagePreview extends Component
{
    public $inputName;
    public $label;
    public $multiple;
    public $required;
    public $accept;
    public $currentImage;
    public $previewHeight;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $inputName = 'image',
        $label = 'Upload Image',
        $multiple = false,
        $required = false,
        $accept = '.png,.jpeg,.jpg,.webp',
        $currentImage = null,
        $previewHeight = '250px'
    ) {
        $this->inputName = $inputName;
        $this->label = $label;
        $this->multiple = $multiple;
        $this->required = $required;
        $this->accept = $accept;
        $this->currentImage = $currentImage;
        $this->previewHeight = $previewHeight;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.image-preview');
    }
}
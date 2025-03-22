<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImageUploader extends Component
{
    public $name;
    public $label;
    public $accept;
    public $multiple;
    public $required;
    public $currentImage;
    public $previewHeight;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $name = 'image',
        $label = 'Upload Image',
        $accept = '.png,.jpeg,.jpg,.webp',
        $multiple = false,
        $required = false,
        $currentImage = null,
        $previewHeight = '250px'
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->accept = $accept;
        $this->multiple = $multiple;
        $this->required = $required;
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
        return view('components.image-uploader');
    }
}
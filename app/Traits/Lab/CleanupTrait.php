<?php

namespace App\Traits\Lab;

use App\Models\Lab;
use App\Models\Borrowing;
use App\Models\LabSlider;

trait CleanupTrait
{
    /**
     * Clean up all data for a single lab
     */
    private function cleanupLabData(Lab $lab): void
    {
        // Delete thumbnail
        if ($lab->file) {
            $this->fileService->deleteFileById($lab->file->id);
        }

        // Delete all slider images
        foreach ($lab->sliderImages as $slider) {
            if ($slider->file) {
                $this->fileService->deleteFileById($slider->file->id);
            }
            $slider->delete();
        }
    }

    /**
     * Clean up all data for all labs
     */
    private function cleanupAllLabsData(): void
    {
        // Get all labs with needed relations
        $labs = Lab::with(['file', 'sliderImages.file'])->get();

        foreach ($labs as $lab) {
            // Delete thumbnail
            if ($lab->file) {
                $this->fileService->deleteFileById($lab->file->id);
            }

            // Delete all slider images
            foreach ($lab->sliderImages as $slider) {
                if ($slider->file) {
                    $this->fileService->deleteFileById($slider->file->id);
                }
            }
        }

        // Delete all slider records first
        LabSlider::query()->delete();

        // Delete all labs
        Lab::query()->delete();
    }
}
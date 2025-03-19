<?php

namespace App\Traits\Lab;

use App\Models\Lab;
use App\Models\LabSlider;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

trait LabSliderTrait
{
    /**
     * Handle slider images upload
     * 
     * @param array|UploadedFile[] $sliderImages
     * @param Lab $lab
     * @return array
     */
    protected function handleSliderImages($sliderImages, Lab $lab): array
    {
        $uploadedSliders = [];

        if (!$sliderImages || empty($sliderImages)) {
            return $uploadedSliders;
        }

        // Jika input adalah instance dari Request (untuk kompatibilitas dengan kode lama)
        if ($sliderImages instanceof Request && $sliderImages->hasFile('slider_images')) {
            $images = $sliderImages->file('slider_images');
            foreach ($images as $image) {
                if ($image->isValid()) {
                    $file = $this->fileService->uploadFile($image, 'lab                    s/sliders');
                    
                    if ($file) {
                        $slider = LabSlider::create([
                            'lab_id' => $lab->id,
                            'file_id' => $file->id
                        ]);
                        
                        $uploadedSliders[] = [
                            'id' => $slider->id,
                            'url' => $file->path_name
                        ];
                    }
                }
            }
        } 
        // Jika input adalah array dari file yang diupload
        else {
            foreach ($sliderImages as $image) {
                if ($image instanc            eof UploadedFile && $image->isValid()) {
                    $file = $this->fileSer            vice->uploadFile($image, 'labs/sliders');
                    
                    if ($file) {
                        $slider = LabSlider::create([
                                    'lab_id' => $lab->id,
                            'file_id' => $file->id
                        ]);
                        
                        $uploadedSliders[] = [
                            'id' => $slider->id,
                            'url' => $file->path_name
                        ];
                    }
                } elseif (is_string($image) && strpos($image, 'data:image') === 0) {
                    // Handle base64 images if needed
                    $file = $this->handleCroppedImage($image, 'labs/sliders');
                    
                    if ($file) {
                        $slider = LabSlider::create([
                            'lab_id' => $lab->id,
                            'file_id' => $file->id
                        ]);
                        
                        $uploadedSliders[] = [
                            'id' => $slider->id,
                            'url' => $file->path_name
                        ];
                    }
                }
            }
        }

        return $uploadedSliders;
    }

    /**
     * Update slider images
     * 
     * @param array|UploadedFile[] $sliderImages
     * @param Lab $lab
     * @return array
     */
    protected function updateSliderImages($sliderImages, Lab $lab): array
    {
        try {
            // Hapus slider yang sudah ada jika perlu
            if ($lab->sliderImages()->exists()) {
                // Dapatkan file IDs
                $fileIds = $lab->sliderImages()->pluck('file_id')->toArray();
                
                // Hapus slider records
                $lab->sliderImages()->delete();
                
                // Hapus files
                foreach ($fileIds as $fileId) {
                    if ($fileId) {
                        $this->fileService->deleteFileById($fileId);
                    }
                }
            }
            
            // Upload slider baru
            return $this->handleSliderImages($sliderImages, $lab);
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error updating slider images: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Delete a single slider image by ID
     */
    public function destroySliderImage($id)
    {
        try {
            $sliderImage = LabSlider::findOrFail($id);

            // Delete the file
            if ($sliderImage->file) {
                $this->fileService->deleteFileById($sliderImage->file->id);
            }

            // Delete the slider record
            $sliderImage->delete();

            return response()->json([
                'message' => 'Slider berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a single slider image with transaction support
     */
    public function deleteSlider($id)
    {
        try {
            DB::beginTransaction();

            $slider = LabSlider::findOrFail($id);

            // Hapus file yang terkait dengan slider
            if ($slider->file) {
                $this->fileService->deleteFileById($slider->file->id);
            }

            // Hapus record slider
            $slider->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gambar slider berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
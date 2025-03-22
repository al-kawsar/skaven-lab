<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Services\FileService;
use App\Http\Requests\Lab\StoreRequest;
use App\Http\Requests\Lab\UpdateRequest;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LabSlider;

class LabController extends Controller
{
    private $fileService;

    /**
     * Initialize controller with file service dependency
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Display lab dashboard with statistics
     */
    public function index(Request $request)
    {
        $data = Lab::selectRaw("
            COUNT(*) as totalData,
            SUM(status = 'tersedia') as totalAvailable,
            SUM(status = 'tidak tersedia') as totalUnavailable
        ")->first()->toArray();

        return view('pages.labs.index', compact('data'));
    }

    /**
     * Fetch labs data for AJAX requests
     */
    public function getData(Request $request)
    {
        $data = Lab::with('users')->orderBy('created_at', 'desc')->get();

        $counter = 0;
        $transformedData = $data->map(function ($lab) use (&$counter): array {
            return [
                'id' => $lab->id,
                'name' => $lab->name,
                'status' => $lab->status,
                'facilities' => substr($lab->facilities, 0, 80) . (strlen($lab->facilities) > 80 ? '...' : ''),
                'number' => ++$counter,
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ]);
    }

    /**
     * Show lab creation form
     */
    public function create()
    {
        $users = User::orderBy('name', 'asc')->limit(5)->get();
        return view('pages.labs.create', compact('users'));
    }

    /**
     * Search users for select2 dropdown
     */
    public function getUser(Request $request)
    {
        $term = trim($request->q);

        if (empty($term))
            return response()->json([], 200);
        $data = [];
        // $users = User::search($term)->take(10)->get();
        $users = User::where('name', 'LIKE', "%{$term}%")->orWhere('email', 'LIKE', "%{$term}%")->take(10)->get();
        foreach ($users as $user) {
            $data[] = ['id' => $user->id, 'text' => $user->name];
        }
        return response()->json($data, 200);
    }

    /**
     * Process base64 cropped image and save to storage
     */
    private function handleCroppedImage($croppedData, $directory)
    {
        // Decode the base64 image data
        list($type, $data) = explode(';', $croppedData);
        list(, $data) = explode(',', $data);
        $imageData = base64_decode($data);

        // Generate a unique filename
        $filename = uniqid() . '.jpg';

        // Save to storage
        $path = storage_path('app/public/' . $directory . '/' . $filename);
        file_put_contents($path, $imageData);

        // Create a file record
        $file = $this->fileService->createFileRecord(
            $filename,
            'image/jpeg',
            filesize($path),
            $directory . '/' . $filename
        );

        return $file;
    }

    /**
     * Create new lab with thumbnail and slider images
     */
    public function store(StoreRequest $request)
    {
        try {
            $payload = $request->validated();
            $payload['user_id'] = auth()->id();

            // Handle thumbnail upload (cropped or regular)
            if ($request->has('thumbnail_cropped_data') && !empty($request->thumbnail_cropped_data)) {
                $file = $this->handleCroppedImage($request->thumbnail_cropped_data, 'lab');
                $payload['thumbnail'] = $file->id;
            } elseif ($request->hasFile('thumbnail')) {
                $file = $this->fileService->uploadFile($request->file('thumbnail'), 'lab');
                $payload['thumbnail'] = $file->id;
            }

            $lab = Lab::create($payload);

            // Handle slider images (cropped or regular)
            if ($request->has('slider_cropped_images') && count($request->slider_cropped_images) > 0) {
                $order = 0;
                foreach ($request->slider_cropped_images as $croppedImage) {
                    $file = $this->handleCroppedImage($croppedImage, 'lab-slider');
                    $lab->sliderImages()->create([
                        'file_id' => $file->id,
                        'order' => ++$order
                    ]);
                }
            } elseif ($request->hasFile('slider_images')) {
                $this->storeLabSliderImages($lab, $request->file('slider_images'));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Lab berhasil ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display lab details
     */
    public function show(string $id)
    {
        return 'show jancuk';
    }

    /**
     * Show lab edit form
     */
    public function edit(Lab $lab)
    {
        return view('pages.labs.edit', compact('lab'));
    }

    /**
     * Update lab with thumbnail and slider images
     * Handles both cropped and regular image uploads
     */
    public function update(UpdateRequest $request, Lab $lab)
    {
        try {
            DB::beginTransaction();

            $payload = $request->validated();

            // Handle thumbnail with cropping
            $thumbnailUrl = null;
            if ($request->has('thumbnail_cropped_data') && !empty($request->thumbnail_cropped_data)) {
                // Delete old thumbnail if exists
                if ($lab->file) {
                    $this->fileService->deleteFileById($lab->file->id);
                }

                // Handle cropped thumbnail
                $file = $this->handleCroppedImage($request->thumbnail_cropped_data, 'lab');
                $payload['thumbnail'] = $file->id;
                $thumbnailUrl = $file->full_path;
            } elseif ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($lab->file) {
                    $this->fileService->deleteFileById($lab->file->id);
                }

                // Upload new thumbnail
                $file = $this->fileService->uploadFile($request->file('thumbnail'), 'lab');
                $payload['thumbnail'] = $file->id;
                $thumbnailUrl = $file->full_path;
            } else {
                // Keep existing thumbnail
                $thumbnailUrl = $lab->file ? $lab->file->full_path : null;
            }

            // Update lab data
            $lab->update($payload);

            $newSliderImages = [];

            // Handle slider images with cropping
            if ($request->has('slider_cropped_images') && count($request->slider_cropped_images) > 0) {
                // Process cropped slider images
                $order = $lab->sliderImages()->max('order') ?? 0;

                foreach ($request->slider_cropped_images as $croppedImage) {
                    // Upload the cropped image
                    $file = $this->handleCroppedImage($croppedImage, 'lab-slider');

                    // Create slider record
                    $slider = $lab->sliderImages()->create([
                        'file_id' => $file->id,
                        'order' => ++$order
                    ]);

                    $newSliderImages[] = [
                        'id' => $slider->id,
                        'image_url' => $file->full_path
                    ];
                }
            } elseif ($request->hasFile('slider_images')) {
                // Process regular slider images
                $newSliders = $this->storeLabSliderImages($lab, $request->file('slider_images'));

                foreach ($newSliders as $slider) {
                    $newSliderImages[] = [
                        'id' => $slider->id,
                        'image_url' => $slider->file->full_path
                    ];
                }
            }

            DB::commit();

            // Return updated data
            return response()->json([
                'status' => 'success',
                'message' => 'Lab berhasil diubah',
                'data' => [
                    'name' => $lab->name,
                    'facilities' => $lab->facilities,
                    'thumbnail_url' => $thumbnailUrl,
                    'slider_images' => $newSliderImages // Hanya mengembalikan slider baru yang ditambahkan
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiple slider images and associate with lab
     * Returns array of created slider objects with file relations
     */
    private function storeLabSliderImages(Lab $lab, array $images)
    {
        $order = $lab->sliderImages()->max('order') ?? 0;
        $sliders = [];

        foreach ($images as $image) {
            // Upload the file
            $file = $this->fileService->uploadFile($image, 'lab-slider');

            // Create the slider record
            $slider = $lab->sliderImages()->create([
                'file_id' => $file->id,
                'order' => ++$order
            ]);

            // Eager load file relationship
            $slider->load('file');

            $sliders[] = $slider;
        }

        return $sliders;
    }

    /**
     * Delete a single slider image by ID
     * Removes both database record and file
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
     * Delete lab with all associated files and sliders
     * Uses transaction to ensure data integrity
     */
    public function destroy(Lab $lab)
    {
        try {
            DB::beginTransaction();

            // Hapus thumbnail utama
            if ($lab->file) {
                $this->fileService->deleteFileById($lab->file->id);
            }

            // Hapus semua slider images yang terkait
            foreach ($lab->sliderImages as $slider) {
                if ($slider->file) {
                    $this->fileService->deleteFileById($slider->file->id);
                }
                $slider->delete();
            }

            // Hapus lab
            $lab->delete();

            DB::commit();

            return response()->json([
                'message' => 'Data lab berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete all labs and associated data
     * Cleans up files, sliders, and borrowings
     */
    public function destroyAll()
    {
        try {
            DB::beginTransaction();

            // Ambil semua lab dengan relasi yang dibutuhkan
            $labs = Lab::with(['file', 'sliderImages.file'])->get();

            foreach ($labs as $lab) {
                // Hapus thumbnail utama
                if ($lab->file) {
                    $this->fileService->deleteFileById($lab->file->id);
                }

                // Hapus semua slider images terkait
                foreach ($lab->sliderImages as $slider) {
                    if ($slider->file) {
                        $this->fileService->deleteFileById($slider->file->id);
                    }
                }
            }

            // Hapus semua data slider terlebih dahulu
            LabSlider::query()->delete();

            // Hapus semua peminjaman terkait
            Borrowing::query()->delete();

            // Hapus semua lab
            Lab::query()->delete();

            DB::commit();

            return response()->json(['message' => 'Semua data lab berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a single slider image
     * Alternative to destroySliderImage with transaction support
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

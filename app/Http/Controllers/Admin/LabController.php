<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Services\FileService;
use App\Http\Requests\LabStoreRequest;
use App\Http\Requests\LabUpdateRequest;
use App\Models\Lab;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LabController extends Controller
{

    public function index(Request $request)
    {
        $data = Lab::selectRaw("
            COUNT(*) as totalData,
            SUM(status = 'tersedia') as totalAvailable,
            SUM(status = 'tidak tersedia') as totalUnavailable
        ")->first()->toArray();

        return view('pages.admin.lab.index', compact('data'));
    }

    public function getData(Request $request)
    {

        $data = Lab::orderBy('created_at', 'desc')->get();

        $transformedData = $data->map(function ($lab) use (&$counter) {
            return [
                'id' => $lab->id,
                'name' => $lab->name,
                'capacity' => $lab->capacity,
                'status' => $lab->status,
                'location' => strlen($lab->location) > 15 ? substr($lab->location, 0, 15) . '...' : $lab->location,
                'number' => ++$counter, // Increment counter for each iteration
            ];
        });

        return response()->json([
            'data' => $transformedData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.lab.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabStoreRequest $request)
    {
        try {
            $payload = $request->validated();

            $fileService = new FileService();

            $file = $fileService->uploadFile($request->file('thumbnail'), 'lab');

            $payload['thumbnail'] = $file->id;

            Lab::create($payload);


        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Lab berhasil ditambahkan'
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return 'show jancuk';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lab $lab)
    {
        return view('pages.admin.lab.edit', compact('lab'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabUpdateRequest $request, Lab $lab)
    {
        try {
            $payload = $request->validated();

            $payload['status'] = $payload['status'] == 1 ? 'tersedia' : 'tidak tersedia';

            $lab->update($payload);

        } catch (\Exception $e) {
            return to_route('admin.lab.edit', $lab->id)->with([
                'status' => 'error',
                'type' => 'toast',
                'message' => $e->getMessage()
            ], 500);
        }

        return to_route('admin.lab.index')->with([
            'status' => 'success',
            'type' => 'toast',
            'message' => 'Lab berhasil diubah'
        ]);
    }


    public function destroy(Lab $id)
    {
        try {
            $id->delete();
            return response()->json([
                'message' => 'Data lab berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function destroyAll()
    {
        try {

            $labs = Lab::with('file')->get();

            // Delete associated image files
            foreach ($labs as $lab) {
                if ($lab->file && Storage::exists($lab->file->path_name)) {
                    Storage::delete($lab->file->path_name);
                }
            }
            Borrowing::query()->delete();
            Lab::query()->delete();

            return response()->json(['message' => 'Data lab berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

}

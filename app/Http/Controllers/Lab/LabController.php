<?php

namespace App\Http\Controllers\Lab;

use App\DTOs\Lab\LabData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lab\StoreRequest;
use App\Http\Requests\Lab\UpdateRequest;
use App\Models\Lab;
use App\Models\User;
use App\Services\Lab\LabService;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function __construct(private LabService $service)
    {
    }

    public function index()
    {
        $data = $this->service->getStatistics();
        return view('pages.lab.index', compact('data'));
    }

    public function getData(Request $request)
    {
        return response()->json($this->service->getDataTableData($request));
    }

    public function create()
    {
        return view('pages.lab.create');
    }

    public function store(StoreRequest $request)
    {
        try {
            $result = $this->service->store(LabData::fromRequest($request));
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Lab $lab)
    {
        return view('pages.lab.show', compact('lab'));
    }

    public function edit(Lab $lab)
    {
        return view('pages.lab.edit', compact('lab'));
    }

    public function update(UpdateRequest $request, Lab $lab)
    {
        try {
            $result = $this->service->update($lab, LabData::fromRequest($request, $lab->id));
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Lab $lab)
    {
        try {
            $result = $this->service->delete($lab);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyAll()
    {
        try {
            $result = $this->service->deleteAll();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

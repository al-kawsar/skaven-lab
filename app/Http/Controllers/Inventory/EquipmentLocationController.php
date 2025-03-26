<?php

namespace App\Http\Controllers\Inventory;

use App\DTOs\Inventory\LocationData;
use App\Http\Controllers\Controller;
use App\Services\Inventory\EquipmentLocationService;
use Illuminate\Http\Request;
use App\Models\EquipmentLocation;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LocationExport;
use App\Imports\LocationImport;

class EquipmentLocationController extends Controller
{
    public function __construct(private EquipmentLocationService $service)
    {
    }

    public function index()
    {
        return view('pages.inventory.location.index');
    }

    public function getData(Request $request)
    {
        return response()->json($this->service->getDataTableData($request));
    }

    public function store(Request $request)
    {
        try {
            $result = $this->service->store(LocationData::fromRequest($request));
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show($id)
    {
        try {
            $result = $this->service->findById($id);
            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = $this->service->update($id, LocationData::fromRequest($request, $id));
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->service->delete($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete multiple location records
     */
    public function bulkDestroy(Request $request)
    {
        try {
            $result = $this->service->bulkDelete($request->ids);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}

<?php

namespace App\Http\Controllers\Inventory;

use App\DTOs\Inventory\EquipmentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\EquipmentStoreRequest;
use App\Http\Requests\Inventory\EquipmentUpdateRequest;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentLocation;
use App\Services\Inventory\EquipmentService;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function __construct(private EquipmentService $service)
    {
    }

    public function index()
    {
        return view('pages.inventory.equipment.index');
    }

    public function getData(Request $request)
    {
        return $this->service->getDataTableData($request);
    }

    public function show(Equipment $equipment)
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getEquipmentDetail($equipment)
        ]);
    }

    public function detail(Equipment $equipment)
    {
        sleep(5);
        $data = $this->service->getEquipmentDetail($equipment);
        return view('pages.inventory.equipment.show', compact('data'));
    }

    public function store(EquipmentStoreRequest $request)
    {
        try {
            $equipment = $this->service->store(EquipmentData::fromRequest($request));
            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil ditambahkan',
                'data' => $equipment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(EquipmentUpdateRequest $request, Equipment $equipment)
    {
        try {
            $updated = $this->service->update($equipment, EquipmentData::fromRequest($request));
            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil diperbarui',
                'data' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Equipment $equipment)
    {
        try {
            $this->service->delete($equipment);
            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function bulkDestroy(Request $request)
    {
        try {
            $count = $this->service->bulkDelete($request->ids);
            return response()->json([
                'success' => true,
                'message' => "{$count} barang berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroyAll()
    {
        try {
            $result = $this->service->deleteAll();

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Semua data barang berhasil dihapus'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dapat dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function export($type)
    {
        return $this->service->export($type);
    }

    public function downloadTemplate()
    {
        return $this->service->downloadTemplate();
    }

    public function import(Request $request)
    {
        try {
            $result = $this->service->import($request);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diimport',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function getCategories()
    {
        $categories = EquipmentCategory::orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function getLocations()
    {
        $locations = EquipmentLocation::orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }
}

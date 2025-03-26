<?php

namespace App\Http\Controllers\Inventory;

use App\DTOs\Inventory\CategoryData;
use App\Http\Controllers\Controller;
use App\Services\Inventory\EquipmentCategoryService;
use Illuminate\Http\Request;

class EquipmentCategoryController extends Controller
{
    public function __construct(private EquipmentCategoryService $service)
    {
    }

    public function index()
    {
        return view('pages.inventory.category.index');
    }

    public function getData(Request $request)
    {
        return response()->json($this->service->getDataTableData($request));
    }

    public function store(Request $request)
    {
        try {
            $result = $this->service->store(CategoryData::fromRequest($request));
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
            $category = $this->service->findById($id);
            return response()->json([
                'success' => true,
                'data' => $category
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
            $result = $this->service->update($id, CategoryData::fromRequest($request, $id));
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
}

<?php

namespace App\Services\Inventory;

use App\Models\Equipment;
use App\Models\EquipmentCategory;

class InventoryCodeGenerator
{
    /**
     * Generate equipment code
     *
     * @param int|null $categoryId
     * @return string
     */
    public function generateEquipmentCode($categoryId = null)
    {
        try {
            $categoryCode = $this->generateCategoryCode($categoryId);
            $year = $this->getCurrentYear();
            $sequence = $this->generateSequenceNumber($categoryId);

            return $categoryCode . $year . $sequence;
        } catch (\Exception $e) {
            \Log::error('Error generating equipment code: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate 3 character category code
     */
    private function generateCategoryCode($categoryId)
    {
        $defaultCode = 'BRG';

        if (!$categoryId) {
            return $defaultCode;
        }

        $category = EquipmentCategory::find($categoryId);
        if (!$category) {
            return $defaultCode;
        }

        $code = $this->extractCategoryCode($category);
        $code = $this->cleanAndPadCode($code);

        return $code;
    }

    /**
     * Extract initial code from category
     */
    private function extractCategoryCode($category)
    {
        if ($category->code) {
            return strtoupper(substr($category->code, 0, 3));
        }

        $words = explode(' ', $category->name);
        if (count($words) > 1) {
            return $this->getInitialsFromWords($words);
        }

        return strtoupper(substr($category->name, 0, 3));
    }

    /**
     * Get initials from multiple words
     */
    private function getInitialsFromWords($words)
    {
        $initials = '';
        foreach (array_slice($words, 0, 3) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    /**
     * Clean code and ensure 3 characters
     */
    private function cleanAndPadCode($code)
    {
        $code = preg_replace('/[^A-Z0-9]/', '', $code);
        return str_pad(substr($code, 0, 3), 3, 'X');
    }

    /**
     * Get last 2 digits of current year
     */
    private function getCurrentYear()
    {
        return substr(date('Y'), -2);
    }

    /**
     * Generate 4 digit sequence number
     */
    private function generateSequenceNumber($categoryId)
    {
        $query = Equipment::query();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $lastCode = $query->orderBy('id', 'desc')->value('code');

        if (!$lastCode) {
            return '0001';
        }

        $lastNumber = intval(substr($lastCode, -4));
        return str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
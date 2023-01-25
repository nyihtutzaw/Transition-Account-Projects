<?php

namespace App\Http\Controllers;

use App\Models\DamageItem;
use App\Models\OutStock;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getReport()
    {
        $totalStock = new Stock();
        $totalStockOut = new OutStock();
        $totalDamageItems = new DamageItem();
        if (request()->start_date && request()->end_date) {
            $totalStock = $totalStock->whereBetween('created_at', [request()->start_date . ' 00:00:00', request()->end_date . ' 23:59:59']);
            $totalStockOut = $totalStockOut->whereBetween('created_at', [request()->start_date . ' 00:00:00', request()->end_date . ' 23:59:59']);
            $totalDamageItems = $totalDamageItems->whereBetween('created_at', [request()->start_date . ' 00:00:00', request()->end_date . ' 23:59:59']);

            $totalStock = $totalStock->get();
            $totalStockAll = $totalStock->sum('quantity');

            $totalStockOut = $totalStockOut->get();
            $totalStockOutAll = $totalStockOut->sum('quantity');


            $totalDamageItems = $totalDamageItems->get();
            $totalDamageItemsAll = $totalDamageItems->sum('quantity');

            return [
                "inStock" => $totalStock,
                "inStockTotal" => $totalStockAll,
                "OutStock" => $totalStockOut,
                "OutStockAll" => $totalStockOutAll,
                "damageItems" => $totalDamageItems,
                "damageItemsAll" => $totalDamageItemsAll,
            ];
            
        } else {
            $totalStock = $totalStock->get();
            $totalStockAll = $totalStock->sum('quantity');

            $totalStockOut = $totalStockOut->get();
            $totalStockOutAll = $totalStockOut->sum('quantity');

            $totalDamageItems = $totalDamageItems->get();
            $totalDamageItemsAll = $totalDamageItems->sum('quantity');

            // $perPage = request()->input('limit', 10);
            // $currentPage = request()->input('page', 1);
            // $total = ceil(count($out_stocks) / $perPage);
            // $currentPageItems = $data->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

            // return response()->json(["status" => "success", 
            // "data" => $currentPageItems, "total" => count($out_stocks), 
            // 'current_page' => $currentPage, 'items_per_page' => $perPage,
            //  'total_pages' => $total]);

            
            return [
                "inStock" => $totalStock,
                "inStockTotal" => $totalStockAll,
                "OutStock" => $totalStockOut,
                "OutStockAll" => $totalStockOutAll,
                "damageItems" => $totalDamageItems,
                "damageItemsAll" => $totalDamageItemsAll,
            ];
        }

        // $totalStock = $totalStock->select(DB::raw('sum(quantity) as quantity'))
    }
}

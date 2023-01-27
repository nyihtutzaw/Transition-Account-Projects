<?php

namespace App\Http\Controllers;

use App\Http\Resources\DamageItemResource;
use App\Http\Resources\InStockReportResource;
use App\Http\Resources\OutStockResource;
use App\Models\DamageItem;
use App\Models\OutStock;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getInStockReport()
    {

        $startDate = request()->input('start_date');
        $endDate = request()->input('end_date');
        $totalStock = new Stock();

        if (!is_null($startDate)  && !is_null($endDate)) {
            
            $Stocks = Stock::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->get();
            $totalStockAll = $Stocks->sum('quantity');


            $totalStock = InStockReportResource::collection($Stocks);
            $perPage = request()->input('limit', 10);
            $currentPage = request()->input('page', 1);
            $total = ceil(count($totalStock) / $perPage);
            $currentPageItems = $totalStock->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

            return response()->json([
                "status" => "success",
                "data" => $currentPageItems, "total" => count($totalStock),
                'current_page' => $currentPage, 'items_per_page' => $perPage,
                'total_pages' => $total, 'totalInStockQuantity' => $totalStockAll
            ]);

            
        } else {
            $totalStock = $totalStock->get();
            $totalStockAll = $totalStock->sum('quantity');
            $totalStock = InStockReportResource::collection($totalStock);

            $perPage = request()->input('limit', 10);
            $currentPage = request()->input('page', 1);
            $total = ceil(count($totalStock) / $perPage);
            $currentPageItems = $totalStock->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

            return response()->json([
                "status" => "success",
                "data" => $currentPageItems, "total" => count($totalStock),
                'current_page' => $currentPage, 'items_per_page' => $perPage,
                'total_pages' => $total, 'totalInStockQuantity' => $totalStockAll
            ]);
        }
    }

    public function getOutStockReport()
    {
        $totalStockOut = new OutStock();
        if (request()->start_date && request()->end_date) {
            $totalStockOut = $totalStockOut->whereBetween('created_at', [request()->start_date . ' 00:00:00', request()->end_date . ' 23:59:59']);
            $totalStockOut = $totalStockOut->get();
            $totalStockOutAll = $totalStockOut->sum('quantity');

            $totalStock = OutStockResource::collection($totalStockOut);

            $perPage = request()->input('limit', 10);
            $currentPage = request()->input('page', 1);
            $total = ceil(count($totalStock) / $perPage);
            $currentPageItems = $totalStock->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

            return response()->json([
                "status" => "success",
                "data" => $currentPageItems, "total" => count($totalStock),
                'current_page' => $currentPage, 'items_per_page' => $perPage,
                'total_pages' => $total, 'totalInStockQuantity' => $totalStockOutAll
            ]);

        } else {
            $totalStockOut = $totalStockOut->get();
            $totalStockOutAll = $totalStockOut->sum('quantity');
            $perPage = request()->input('limit', 10);
            $currentPage = request()->input('page', 1);
            $total = ceil(count($totalStockOut) / $perPage);
            $currentPageItems = $totalStockOut->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

            return response()->json([
                "status" => "success",
                "data" => $currentPageItems, "total" => count($totalStockOut),
                'current_page' => $currentPage, 'items_per_page' => $perPage,
                'total_pages' => $total, 'totalOutStockQuantity' => $totalStockOutAll
            ]);
        }
    }

    public function getDamageReport()
    {
        $totalDamageItems = new DamageItem();
        if (request()->start_date && request()->end_date) {
            $totalDamageItems = $totalDamageItems->whereBetween('created_at', [request()->start_date . ' 00:00:00', request()->end_date . ' 23:59:59']);
            $totalDamageItems = $totalDamageItems->get();
            $totalDamageItemsAll = $totalDamageItems->sum('quantity');

            $totalStock = DamageItemResource::collection($totalDamageItems);
            $perPage = request()->input('limit', 10);
            $currentPage = request()->input('page', 1);
            $total = ceil(count($totalStock) / $perPage);
            $currentPageItems = $totalStock->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

            return response()->json([
                "status" => "success",
                "data" => $currentPageItems, "total" => count($totalStock),
                'current_page' => $currentPage, 'items_per_page' => $perPage,
                'total_pages' => $total, 'totalInStockQuantity' => $totalDamageItemsAll
            ]);

        } else {

            $totalDamageItems = $totalDamageItems->get();
            $totalDamageItemsAll = $totalDamageItems->sum('quantity');

            $perPage = request()->input('limit', 10);
            $currentPage = request()->input('page', 1);
            $total = ceil(count($totalDamageItems) / $perPage);
            $currentPageItems = $totalDamageItems->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

            return response()->json([
                "status" => "success",
                "data" => $currentPageItems, "total" => count($totalDamageItems),
                'current_page' => $currentPage, 'items_per_page' => $perPage,
                'total_pages' => $total, 'totalDamageQuantity' => $totalDamageItemsAll
            ]);
        }
    }
}

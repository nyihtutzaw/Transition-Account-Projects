<?php

namespace App\Http\Controllers;

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
        if (request()->start_date && request()->end_date) {
            $totalStock = $totalStock->whereBetween('created_at', [request()->start_date . ' 00:00:00', request()->end_date . ' 23:59:59']);
            $totalStockOut = $totalStockOut->whereBetween('created_at', [request()->start_date . ' 00:00:00', request()->end_date . ' 23:59:59']);
            $totalStock = $totalStock->get();
            $totalStockOut = $totalStockOut->get();
            return [
                "stockIn" => $totalStock,
                "stockOut" => $totalStockOut,
            ];
        } else {
            $totalStock = $totalStock->get();
            $totalStockOut = $totalStockOut->get();
            return [
                "stockIn" => $totalStock,
                "stockOut" => $totalStockOut,
            ];
        }
        // $totalStock = $totalStock->select(DB::raw('sum(quantity) as quantity'))
    }
}

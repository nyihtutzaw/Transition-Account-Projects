<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OutStockResource;
use App\Models\OutStock;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutStockController extends Controller
{
    const CATEGORY_ID = 'category_id';
    const QUANTITY = 'quantity';
    const ACCEPTOR = 'acceptor';
    const ITEM_ID   = 'item_id';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $out_stocks = $user->outStocks->sortByDesc('created_at');
        $data = OutStockResource::collection($out_stocks);
        $perPage = request()->input('limit', 10);
        $currentPage = request()->input('page', 1);
        $total = ceil(count($out_stocks) / $perPage);
        $currentPageItems = $data->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

        return response()->json(["status" => "success", "data" => $currentPageItems, "total" => count($out_stocks), 'current_page' => $currentPage, 'items_per_page' => $perPage, 'total_pages' => $total]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $user = Auth::user();
        $category_id = trim($request->get(self::CATEGORY_ID));
        $quantity = trim($request->get(self::QUANTITY));
        $acceptor = trim($request->get(self::ACCEPTOR));
        $item_id = trim($request->get(self::ITEM_ID));

        try {
            $stock = OutStock::where('item_id', '=',  $item_id)->first();
            if ($stock === null) {
                $stock = new OutStock();
                $stock->category_id = $category_id;

                $stock->item_id = $item_id;
                $stock->quantity += $quantity;
                $stock->acceptor = $acceptor;
                $stock->user_id = $user->id;
                $stock->save();

                $old_stock = Stock::find($item_id);
                $old_stock->quantity -= $quantity;
                $old_stock->save();

                $data = new OutStockResource($stock);
                DB::commit();

                return success('Successfully Created', $data);
            } else {

                $stock->category_id = $category_id;
                $stock->item_id = $item_id;
                $stock->quantity += $quantity;
                $stock->acceptor = $acceptor;
                $stock->user_id = $user->id;
                $stock->save();

                $old_stock = Stock::find($item_id);
                $old_stock->quantity -= $quantity;
                $old_stock->save();

                $data = new OutStockResource($stock);
                DB::commit();

                return success('Successfull Updated', $data);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return fail("Please try again!", null);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OutStock $stock)
    {
        $data = new OutStockResource($stock);
        return success('Success', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $category_id = trim($request->get(self::CATEGORY_ID));
        $quantity = trim($request->get(self::QUANTITY));
        $acceptor = trim($request->get(self::ACCEPTOR));
        $item_id = trim($request->get(self::ITEM_ID));

        try {
            $stock = OutStock::findOrfail($id);
            $stock->category_id = $category_id;
            $stock->item_id = $item_id;
            $stock->quantity = $quantity;
            $stock->acceptor = $acceptor;
            $stock->user_id = $user->id;

            $stock->save();
            $data = new OutStockResource($stock);
            return success('Success', $data);
        } catch (Exception $ex) {
            return fail("Please try again!", null);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $item = OutStock::findOrFail($id);
            $item->delete();
            return success('Success deleted', null);
        } catch (Exception $ex) {
            return fail('Please try again!', null);
        }
    }
}
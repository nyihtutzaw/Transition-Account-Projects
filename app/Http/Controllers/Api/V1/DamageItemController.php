<?php

namespace App\Http\Controllers\Api\V1;

use App\Utils\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DamageItemResource;
use App\Models\DamageItem;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DamageItemController extends Controller
{
    const QUANTITY = 'quantity';
    // const ACCEPTOR = 'acceptor';
    // const ITEM_ID   = 'item_id';
    const STOCK_ID   = 'stock_id';


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $items = $user->damageItems->sortByDesc('created_at');
        $data = DamageItemResource::collection($items);
        $perPage = request()->input('limit', 10);
        $currentPage = request()->input('page', 1);
        $total = ceil(count($items) / $perPage);
        $currentPageItems = $data->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

        $keyword = strtolower(request()->input('keyword'));
        if ($keyword) {
            // return $keyword;
            $users = DB::table('items')
                ->Join('stocks', 'items.id', '=', 'stocks.item_id')
                ->Join('damage_items', 'stocks.id', '=', 'damage_items.stock_id')
                ->where('name', 'Like', '%' . $keyword . '%')
                ->get();

            $perPage = request()->input('limit', 10);
            $currentPage = request()->input('page', 1);
            $total = ceil(count($users) / $perPage);
            $currentPageItems = $users->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

            return response()->json([
                "status" => "success", "data" => $currentPageItems,
                "total" => count($users), 'current_page' => $currentPage,
                'items_per_page' => $perPage, 'total_pages' => $total
            ]);
        }


        return response()->json(["status" => "success", "data" => $currentPageItems, "total" => count($items), 'current_page' => $currentPage, 'items_per_page' => $perPage, 'total_pages' => $total]);
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
        $quantity = trim($request->get(self::QUANTITY));
        // $acceptor = trim($request->get(self::ACCEPTOR));
        // $item_id = trim($request->get(self::ITEM_ID));
        $stock_id = trim($request->get(self::STOCK_ID));

        try {
            $stock = DamageItem::where('stock_id', '=',  $stock_id)->first();
            if ($stock === null) {
                $stock = new DamageItem();
                $stock->stock_id = $stock_id;
                $stock->quantity = $quantity;
                $stock->user_id = $user->id;
                $stock->save();

                $old_stock = Stock::where('id', '=', $stock_id)->first();
                if ($old_stock->quantity < $quantity) {
                    return ResponseHelper::fail("Your Quantity is greater than In Stock..!", null);
                }

                $old_stock->quantity -= $quantity;
                $old_stock->save();

                $data = new DamageItemResource($stock);
                DB::commit();

                return ResponseHelper::success('Successfully Created', $data);
            } else {
                $stock->stock_id = $stock_id;
                $stock->quantity += $quantity;
                $stock->user_id = $user->id;
                $stock->save();

                $old_stock = Stock::where('id', '=', $stock_id)->first();
                if ($old_stock->quantity < $quantity) {
                    return ResponseHelper::fail("Your Quantity is greater than In Stock..!", null);
                }
                $old_stock->quantity -= $quantity;
                $old_stock->save();

                $data = new DamageItemResource($stock);
                DB::commit();

                return ResponseHelper::success('Successfull Updated', $data);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return ResponseHelper::fail("Please try again!", null);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $damage = DamageItem::findOrFail($id);
        $data = new DamageItemResource($damage);
        return ResponseHelper::success('Success', $data);
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
        $quantity = trim($request->get(self::QUANTITY));
        $stock_id = trim($request->get(self::STOCK_ID));

        // return $quantity;

        try {
            $item_new = DamageItem::findOrFail($id);
            $item_new->stock_id = $stock_id;
            // $item_new->quantity =$item_new->quantity + $quantity;
            if ($quantity > 0) {
                $item_new->quantity = $item_new->quantity + $quantity;
            } else {
                $item_new->quantity = $item_new->quantity + $quantity;
            }
            $item_new->user_id = $user->id;
            $item_new->save();

            $old_stock = Stock::where('id', '=', $stock_id)->first();
            if ($old_stock->quantity < $quantity) {
                return ResponseHelper::fail("Your Quantity is greater than In Stock..!", null);
            }
            $old_stock->quantity -= $quantity;
            $old_stock->save();

            $data = new DamageItemResource($item_new);

            return ResponseHelper::success('Success Updated', $data);
        } catch (Exception $ex) {
            return ResponseHelper::fail("Please try again!", null);
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
            $item = DamageItem::findOrFail($id);
            $item->delete();
            return ResponseHelper::success('Success deleted', null);
        } catch (Exception $ex) {
            return ResponseHelper::fail('Please try again!', null);
        }
    }
}

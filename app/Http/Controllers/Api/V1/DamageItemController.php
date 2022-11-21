<?php

namespace App\Http\Controllers\Api\V1;

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
        $items = $user->damageItems->sortByDesc('created_at');
        $data = DamageItemResource::collection($items);
        $perPage = request()->input('limit', 10);
        $currentPage = request()->input('page', 1);
        $total = ceil(count($items) / $perPage);
        $currentPageItems = $data->slice(($currentPage * $perPage) - $perPage, $perPage)->values();

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
        $acceptor = trim($request->get(self::ACCEPTOR));
        $item_id = trim($request->get(self::ITEM_ID));

        try {
            $stock = DamageItem::where('item_id', '=',  $item_id)->first();
            if ($stock === null) {
                $stock = new DamageItem();
                $stock->item_id = $item_id;
                $stock->quantity += $quantity;
                $stock->acceptor = $acceptor;
                $stock->user_id = $user->id;
                $stock->save();

                $old_stock = Stock::where('item_id', '=', $item_id)->first();
                $old_stock->quantity -= $quantity;
                $old_stock->save();

                $data = new DamageItemResource($stock);
                DB::commit();

                return success('Successfully Created', $data);
            } else {

                $stock->item_id = $item_id;
                $stock->quantity += $quantity;
                $stock->acceptor = $acceptor;
                $stock->user_id = $user->id;
                $stock->save();

                $old_stock = Stock::where('item_id', '=', $item_id)->first();
                $old_stock->quantity -= $quantity;
                $old_stock->save();

                $data = new DamageItemResource($stock);
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
    public function show(DamageItem $damage)
    {
        $data = new DamageItemResource($damage);
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
        $quantity = trim($request->get(self::QUANTITY));
        $acceptor = trim($request->get(self::ACCEPTOR));
        $item_id = trim($request->get(self::ITEM_ID));

        try {
            $item_new = DamageItem::findOrFail($id);
            $item_new->item_id = $item_id;

            $item_new->quantity = $quantity;
            $item_new->acceptor = $acceptor;
            $item_new->user_id = $user->id;
            $item_new->save();
            $data = new DamageItemResource($item_new);

            return success('Success Updated', $data);
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
            $item = DamageItem::findOrFail($id);
            $item->delete();
            return success('Success deleted', null);
        } catch (Exception $ex) {
            return fail('Please try again!', null);
        }
    }
}

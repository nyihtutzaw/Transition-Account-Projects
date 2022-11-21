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
        //
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
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    const NAME = 'name';
    const CATEGORY_ID = 'category_id';
    const QUANTITY = 'quantity';
    const ACCEPTOR = 'acceptor';

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
        // return $request->all();

        // DB::beginTransaction();
        try {
            $user = Auth::user();

            $category_id = trim($request->get(self::CATEGORY_ID));
            $name = trim($request->get(self::NAME));
            $quantity = trim($request->get(self::QUANTITY));
            $acceptor = trim($request->get(self::ACCEPTOR));

            $item = new Item();
            $item->category_id = $category_id;
            $item->name = $name;
            $item->quantity = $quantity;
            $item->acceptor = $acceptor;
            $item->user_id = $user->id;

            $item->save();
            $data = new ItemResource($item);
            return success('Success', $data);
            // DB::commit();
            // return jsend_success(new ItemResource($item), JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            return "error";

            // DB::rollBack();
            // Log::error(__('api.saved-failed', ['model' => class_basename(Item::class)]), [
            //     'code' => $ex->getCode(),
            //     'trace' => $ex->getTrace(),
            // ]);

            // return jsend_error(__('api.saved-failed', ['model' => class_basename(Item::class)]), [
            //     $ex->getCode(),
            //     ErrorType::SAVE_ERROR,
            // ]);
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

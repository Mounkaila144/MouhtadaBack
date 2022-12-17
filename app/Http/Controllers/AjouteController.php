<?php

namespace App\Http\Controllers;

use App\Models\Ajoute;
use Illuminate\Http\Request;

class AjouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Ajoute $products)
    {
        return  Ajoute::all();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $save = new Ajoute();
        $save->contenue =json_encode($request->input(["contenue"]));
        $save->user_id =(int)$request->get('user_id');
        $save->save();

        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ajoute  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $ajoute= Ajoute::find($id);
        $data[]=[
            'contenue'=>json_decode($ajoute['contenue']),
            'user_id'=>$ajoute['user_id'],
            'created_at'=>$ajoute['created_at'],
            'updated_at'=>$ajoute['updated_at'],
        ];

        Return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Ajoute $product)
    {
        $input=$request->all();
        $product->update($input);
        $product->save();
            return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ajoute  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $Ajoute = Ajoute::findOrFail($id);
        if($Ajoute)
            $Ajoute->delete();
        else
            return response()->json("eureur");
        return response()->json(null);
    }

}

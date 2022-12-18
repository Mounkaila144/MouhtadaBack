<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Article $products)
    {
        $products=$products->newQuery();
        if ($request->has("nom")){
        return $products->where('nom',$request->get("nom"))->get();
    }
        return  Article::orderBy('id', 'DESC')->get();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $path = $request->file('image')->store('public/Article');
        $url=explode("/",$path);
        $real_path="storage/$url[1]/$url[2]";
        $save = new Article();
        $save->image =$real_path;
        $save->nom =$request->input(["nom"]);
        $save->prixAchat =(int)$request->get('prixAchat');
        $save->prixVente =(int)$request->get('prixVente');
        $save->stock =(int)$request->get('stock');
        $save->vendue =(int)$request->get('vendue');
        $save->save();

        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $Article= Article::find($id);

        Return response()->json($Article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Article $product)
    {
        $input=$request->all();
        $product->update($input);
        $product->save();
            return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $Article = Article::findOrFail($id);
        if($Article)
            $Article->delete();
        else
            return response()->json("eureur");
        return response()->json(null);
    }

}

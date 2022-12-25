<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        return  Article::orderBy('id', 'ASC')->get();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prixAchat' => 'required',
            'prixVente' => 'required',
            'stock' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imageName = time() . '.' . $request->file('image')->extension();
        // $request->image->move(public_path('images'), $imageName);
        $request->file('image')->storeAs('public/Article', $imageName);

        $save = new Article();
        $save->image =$imageName;
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
    public function edit(Article $post)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Article::findOrFail($id);
        $data=[];
        $imageName = '';
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/Article', $imageName);
            if ($product->image) {
                Storage::delete('public/Article/' . $product->image);
            }
            $data["image"]=$imageName;
        }

        $request->has('nom') ? $data["nom"]= $request->input(["nom"]) : null;
        $request->has('vendue') ? $data['vendue']= $request->input(["vendue"]) : null;
        $request->has('stock') ? $data['stock']= $request->input(["stock"]) : null;
        $request->has('prixVente') ? $data['prixVente']= $request->input(["prixVente"]) : null;
        $request->has('prixAchat') ? $data['prixAchat']= $request->input(["prixAchat"]) : null;
        $product->update($data);
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
        if($Article) {
            Storage::delete('public/Article/' . $Article->image);
            $Article->delete();
        }
        else
            return response()->json("eureur");
        return response()->json(null);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAll(Request $request): \Illuminate\Http\JsonResponse
    {
        $data=$request->input(["data"]);

        $d=[];
        foreach ($data as $id){
            $d[]=Article::findOrFail($id);
        }

        foreach ($d as $Article){
            if($Article) {
                Storage::delete('public/Article/' . $Article->image);
                $Article->delete();
            }
        }
            return response()->json("sucess");
    }

}

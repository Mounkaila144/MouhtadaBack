<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Article;
use App\Models\Facture;
use App\Models\Vente;
use Exception;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Facture $products)
    {
        return  Facture::all();


    }
    public function download(Request $request, $id)
    {
        $factures = Facture::find($id);
        $contenue=json_decode($factures->contenue);
        $total=0;
        foreach ($contenue as$value){
            $total+=$value->itemTotal;
        }
        return view('factures.facture',["factures"=>$factures,"contenue"=>$contenue,"total"=>$total]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        if ($request->has("contenue")){
            $contenue =$request->input(["contenue"]);
            $v=[];
            foreach ($contenue as $content){
                $article=Article::findOrFail($content["id"]);
                if ($article->stock-$content["quantity"]<0 or $article->stock<$content["quantity"]){
                    throw new Exception("Database error");
                }
                else{
                    $article->update(["stock"=>$article->stock-$content["quantity"],"vendue"=>$article->vendue+$content["quantity"]]);
                    $vente=new Vente();
                    $vente->nom=$article->nom;
                    $vente->identifiant=$article->id;
                    $vente->prixAchat=$article->prixAchat;
                    $vente->prixVente=$article->prixVente;
                    $vente->quantite=$content["quantity"];
                    $vente->user_id=$request->input(["user_id"]);
                    $vente->save();
                }
            }

        }else{
            Return response()->json("404");
        }
        $save = new Facture();
        $save->nom =$request->input(["nom"]);
        $save->prenom =$request->input(["prenom"]);
        $save->adresse =$request->input(["adresse"]);
        $save->contenue =json_encode($request->input(["contenue"]));
        $save->user_id =(int)$request->get('user_id');
        $save->save();

        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Facture  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $factures = Facture::find($id);
        $contenue=json_decode($factures->contenue);
        $total=0;
        foreach ($contenue as$value){
            $total+=$value->itemTotal;
        }
        return view('factures.facture',["factures"=>$factures,"contenue"=>$contenue,"total"=>$total]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Facture $product)
    {
        $input=$request->all();
        $product->update($input);
        $product->save();
            return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Facture  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $Facture = Facture::findOrFail($id);
        if($Facture)
            $Facture->delete();
        else
            return response()->json("eureur");
        return response()->json(null);
    }

}

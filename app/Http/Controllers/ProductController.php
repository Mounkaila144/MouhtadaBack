<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request, Product $products)
    {
        $products = $products->newQuery();
        if ($request->has("categorie")) {
            return $products
                ->where("categorie", "=", $request->get("categorie"))
                ->get();
        }

        return Product::orderBy('id', 'ASC')
            ->with('categorie','suplier') // Chargement de la relation "categorie" avec uniquement la colonne "name"
            ->get();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $pictureName = time() . '.' . $request->file('picture')->extension();
        // $request->picture->move(public_path('pictures'), $pictureName);
        $request->file('picture')->storeAs('public/product', $pictureName);

        $save = new Product();
        $save->picture = $pictureName;
        $save->name = $request->input(["name"]);
        $save->price = (int)$request->get('price');
        $save->alert = (int)$request->get('alert');
        $save->categorie = (int)$request->get('categorie');
        $save->stock = (int)$request->get('stock');
        $save->suplier = (int)$request->get('suplier');
        $save->vendue = 0;
        $save->save();

        return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $Product = Product::find($id);

        return response()->json($Product);
    }

    public function stocks($id, Request $request)
    {
        $Product = Product::find($id);
        if ($request->has('add')) {
            if ($request->input(["add"]) > 0) {
                $Product->update(["stock" => $Product->stock + $request->input(["add"])]);
                $stock = new Stock();
                $stock->type = "add";
                $stock->identifiant = $Product->id;
                $stock->quantite = $request->input(["add"]);
                $stock->name = $Product->name;
                $stock->user_id = $request->input(["user"]);
                $stock->save();
                return response()->json($Product);
            } else {
                throw new Exception("Database error");
            }
        } else if ($request->has('remove')) {
            if ($request->input(["remove"]) > 0 and $Product->stock >= $request->input(["remove"])) {
                $Product->update(["stock" => $Product->stock - $request->input(["remove"])]);
                $stock = new Stock();
                $stock->type = "remove";
                $stock->identifiant = $Product->id;
                $stock->quantite = $request->input(["remove"]);
                $stock->name = $Product->name;
                $stock->user_id = $request->input(["user"]);
                $stock->save();
                return response()->json($Product);
            } else {
                throw new Exception("Database error");
            }
        } else if ($request->has('price') and $request->has('categorie')) {
            if ($request->input(["price"]) >=0 and $request->input(["categorie"])>=0) {
                $Product->update(["price" => $request->input(["price"]),"categorie" => $request->input(["categorie"])]);
                $stock = new Stock();
                $stock->type = "prix";
                $stock->identifiant = $Product->id;
                $stock->name = $Product->name;
                $stock->price = $request->input(["price"]);
                $stock->categorie = $request->input(["categorie"]);
                $stock->user_id = $request->input(["user"]);
                $stock->save();
                return response()->json($Product);
            } else {
                throw new Exception("Database error");
            }
        } else {
            throw new Exception("Database error");
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = [];
        $pictureName = '';
        if ($request->hasFile('picture')) {
            $pictureName = time() . '.' . $request->file('picture')->extension();
            $request->file('picture')->storeAs('public/product', $pictureName);
            if ($product->picture) {
                Storage::delete('public/product/' . $product->picture);
            }
            $data["picture"] = $pictureName;
        }

        $request->has('name') ? $data["name"] = $request->input(["name"]) : null;
        $request->has('categorie') ? $data['categorie'] = $request->input(["categorie"]) : null;
        $request->has('price') ? $data['price'] = $request->input(["price"]) : null;
        $request->has('suplier') ? $data['suplier'] = $request->input(["suplier"]) : null;
        $request->has('alert') ? $data['alert'] = $request->input(["alert"]) : null;
        $product->update($data);
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {

        $Product = Product::findOrFail($id);
        if ($Product) {
            Storage::delete('public/product/' . $Product->picture);
            $Product->delete();
        } else
            return response()->json("eureur");
        return response()->json(null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAll(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->input(["data"]);

        $d = [];
        foreach ($data as $id) {
            $d[] = Product::findOrFail($id);
        }

        foreach ($d as $Product) {
            if ($Product) {
                $stock = new Stock();
                $stock->type = "delect";
                $stock->name = $Product->name;
                $stock->identifiant = $Product->id;
                $stock->user_id = $request->input(["user"]);
                $stock->save();
                Storage::delete('public/product/' . $Product->picture);
                $Product->delete();
            }
        }
        return response()->json("sucess");
    }

}

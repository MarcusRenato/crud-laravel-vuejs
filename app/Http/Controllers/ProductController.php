<?php

namespace App\Http\Controllers;

use App\Model\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index()
    {
        return response()->json($this->product->all());
    }

    public function store(Request $request)
    {
        // return response()->json($request->all());

        try {
            $product = $this->product->create($request->all());

            return response()->json($product, 200);
        } catch (\Throwable $th) {

            return response()->json(['msg' => $th->getMessage()], 500);
        }

    }

    public function show($id)
    {
        $product = $this->product->find($id);

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = $this->product->find($id);

        $product->update($request->all());

        return response()->json($product, 201);
    }

    public function destroy($id)
    {
        $product = $this->product->find($id);

        $product->delete();

        return response()->json('Excluído com sucesso', 200);
    }
}

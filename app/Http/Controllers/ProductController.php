<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
   
    public function index()
    {
    $products=Product::all();
    if($products->isEmpty()){
        return response()->json(['message' => 'No products found']);
    }
    return response()->json(["data"=>$products]);
    }
   
    public function store()
    {
      $validate=request()->validate([
        "title"=>"required",
        "description"=>"nullable",
        "price"=>"required|numeric|min:0",
        "quantity"=>"nullable|numeric|min:0"
      ]);
        $product=Product::create($validate);
        return response()->json(['message' => 'Product created successfully',"data"=>$product]);
    }

  
   

    
    public function show(Product $product)
    {
        return response()->json(["data"=>$product]);
    }

    public function update(Request $request, Product $product)
    {
        $validate=request()->validate([
            "title"=>"sometimes|required",
            "description"=>"required",
            "price"=>"sometimes|required|numeric|min:0",
            "quantity"=>"nullable|numeric|min:0"
          ]);
        $product->update($validate);
        return response()->json(['message' => 'Product updated successfully',"data"=>$product]);
    }

    
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
    public function attach(Product $product){
        $validate=request()->validate([
            "customer_id"=>"required|exists:customers,id"
        ]);
        $product->customers()->attach($validate['customer_id']);
        return response()->json(['message' => 'Customer attached to product successfully']);
    }
}

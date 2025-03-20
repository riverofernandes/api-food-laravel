<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        return response()->json(Product::paginate(10));
    }

    /**
     * Display the specified resource.
     * @param string $code
     * @return JsonResponse
     */
    public function show($code) : JsonResponse
    {
        $product = Product::where('code', $code)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $code
     * @return JsonResponse
     */
    public function update(Request $request, $code) : JsonResponse
    {
        $product = Product::where('code', $code)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->update($request->all());
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     * @param string $code
     * @return JsonResponse
     */
    public function destroy($code) : JsonResponse
    {
        $product = Product::where('code', $code)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->update(['status' => 'trash']);
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}

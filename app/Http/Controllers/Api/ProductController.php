<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // VALIDATION
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'price' => 'required',
        'image' => 'required|image',
        'video' => 'nullable|mimes:mp4,mov,avi'
    ]);

    // IMAGE
    $imagePath = $request
        ->file('image')
        ->store('products', 'public');

    // VIDEO
    $videoPath = null;

    if ($request->hasFile('video')) {

        $videoPath = $request
            ->file('video')
            ->store('videos', 'public');
    }

    // CREATE PRODUCT
    $product = Product::create([

        'name' => $request->name,

        'description' => $request->description,

        'price' => $request->price,

        'stock' => $request->stock,

        'image' => $imagePath,

        'video' => $videoPath

    ]);

    return response()->json($product);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
          return Product::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        
   $product = Product::findOrFail($id);

    // IMAGE
    if ($request->hasFile('image')) {

        $imagePath = $request
            ->file('image')
            ->store('products', 'public');

        $product->image = $imagePath;
    }

    // VIDEO
    if ($request->hasFile('video')) {

        $videoPath = $request
            ->file('video')
            ->store('videos', 'public');

        $product->video = $videoPath;
    }

    // AUTRES CHAMPS
    $product->name = $request->name;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->stock = $request->stock;

    $product->save();

    return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        Product::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}

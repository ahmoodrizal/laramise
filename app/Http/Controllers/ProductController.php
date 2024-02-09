<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->paginate(6);

        return view('pages.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('pages.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request['slug'] = Str::slug($request['name']);

        $data = $request->validate([
            'name' => ['required'],
            'slug' => ['required', 'unique:products,slug'],
            'description' => ['required'],
            'category_id' => ['required'],
            'price' => ['required', 'integer'],
            'stock' => ['required', 'integer'],
            'image' => ['required'],
        ], [
            'slug.unique' => 'This Product Already Exist'
        ]);

        $image = $request->file('image');
        $image->storeAs('public/images', $image->hashName());
        $data['image'] = $image->hashName();

        Product::create($data);

        return redirect(route('product.index'))->with('success', 'New product has been created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('pages.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request['slug'] = Str::slug($request['name']);

        $data = $request->validate([
            'name' => ['required'],
            'slug' => ['required', 'unique:products,slug,' . $product->id . ',id'],
            'description' => ['required'],
            'category_id' => ['required'],
            'price' => ['required', 'integer'],
            'stock' => ['required', 'integer'],
            'image' => ['nullable'],
        ], [
            'slug.unique' => 'This Product Already Exist'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // delete old image
            Storage::delete('public/images/' . $product->image);
            // Store new image
            $image->storeAs('public/images', $image->hashName());
            $data['image'] = $image->hashName();
        } else {
            $data['image'] = $product->image;
        }

        $product->update($data);

        return redirect(route('product.index'))->with('success', 'Product has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        Storage::delete('public/images/' . $product->image);

        return redirect(route('product.index'));
    }
}

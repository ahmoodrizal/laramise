<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')->paginate(6);

        return view('pages.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request['slug'] = Str::slug($request['name']);

        $data = $request->validate([
            'name' => ['required'],
            'description' => ['nullable'],
            'slug' => ['required', 'unique:categories,slug'],
            'image' => ['nullable'],
        ], [
            'slug.unique' => 'This Category already exist'
        ]);

        // Store Category Image
        $image = $request->file('image');
        $image->storeAs('public/images', $image->hashName());
        $data['image'] = $image->hashName();

        Category::create($data);

        return redirect(route('category.index'))->with('success', 'New category has been created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('pages.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request['slug'] = Str::slug($request['name']);

        $data = $request->validate([
            'name' => ['required'],
            'description' => ['nullable'],
            'slug' => ['required', 'unique:categories,slug,' . $category->id . ',id'],
            'image' => ['nullable'],
        ], [
            'slug.unique' => 'This Category already exist'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Delete old Image
            Storage::delete('public/images/' . $category->image);
            $image->storeAs('public/images', $image->hashName());
            $data['image'] = $image->hashName();
        } else {
            $data['image'] = $category->image;
        }

        $category->update($data);

        return redirect(route('category.index'))->with('success', 'Category has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect(route('category.index'));
    }
}

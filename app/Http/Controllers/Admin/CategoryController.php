<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; 

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('icon')) {
            // Unggah gambar ke Cloudinary dan dapatkan URL-nya
            $path = Cloudinary::upload($request->file('icon')->getRealPath())->getSecurePath();
        }

        Category::create([
            'name' => $request->name,
            'icon' => $path
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Saya standarisasi 'image' menjadi 'icon' agar konsisten
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;

        if ($request->hasFile('icon')) {
            // Hapus gambar lama dari Cloudinary jika ada
            if ($category->icon) {
                $publicId = pathinfo(parse_url($category->icon, PHP_URL_PATH), PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            // Unggah gambar baru dan perbarui path
            $path = Cloudinary::upload($request->file('icon')->getRealPath())->getSecurePath();
            $category->icon = $path;
        }

        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Hapus gambar dari Cloudinary sebelum menghapus data kategori
        if ($category->icon) {
            $publicId = pathinfo(parse_url($category->icon, PHP_URL_PATH), PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
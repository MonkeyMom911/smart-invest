<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Investment;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class InvestmentController extends Controller
{

    public function index(Request $request)
    {
        // Menggunakan with('category') untuk Eager Loading
        $query = Investment::with('category')
            ->withCount([
                'transactions as investors_count' => function ($query) {
                    $query->select(DB::raw('COUNT(DISTINCT user_id)'));
                }
            ])
            ->latest();

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $investments = $query->get();
        $categories = Category::all();

        return view('admin.investments.index', compact('investments', 'categories'));
    }

    // ... (sisa kode tidak berubah)
    public function create()
    {
        $categories = Category::all();
        return view('admin.investments.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:255',
            'market_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
             $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $data['image'] = $uploadedFileUrl;
        }

        Investment::create($data);

        return redirect()->route('admin.investments.index')->with('success', 'Investment created successfully.');
    }

    public function edit(Investment $investment)
    {
        $categories = Category::all();
        return view('admin.investments.edit', compact('investment', 'categories'));
    }

    public function update(Request $request, Investment $investment)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'badge' => 'nullable|string|max:255',
            'market_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
             'minimum_amount' => 'required|numeric|min:1000',
        ]);

         if ($request->hasFile('image')) {
            
            if ($investment->image) {
                
                $publicId = pathinfo(parse_url($investment->image, PHP_URL_PATH), PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }

            
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
            $data['image'] = $uploadedFileUrl;
        }


        $investment->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'badge' => $request->badge,
            'market_price' => $request->market_price, 
            'image' => $data['image'] ?? $investment->image,
        ]);


        return redirect()->route('admin.investments.index')->with('success', 'Investment updated successfully.');
    }

    public function destroy(Investment $investment)
    {
       if ($investment->image) {
            $publicId = pathinfo(parse_url($investment->image, PHP_URL_PATH), PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }
        
        $investment->delete();

        return redirect()->back()->with('success', 'Investment deleted successfully.');
    }

    public function show(Investment $investment)
    {
        $investment->load('category');
        return view('investments.show', compact('investment'));
    }
}

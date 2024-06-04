<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index(){
        $allProducts = Product::with('category')->latest()->paginate(10);
        $storageUrl = asset('storage');
        return Inertia::render('Products/Index',['all_products' => $allProducts, 'storage_url' => $storageUrl]);
    }

    public function create(){
        return Inertia::render('Products/Create');
    }

    public function store(Request $request){
        // validate
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'directions' => 'required|string|max:65535',
            'price' => 'required|numeric|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'in_stock' => 'required|integer',
            'category_id' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $newProduct = new Product();
        // Generate the next ID based on the existing IDs in the database
        $lastId = Product::orderBy('created_at', 'desc')->first();

        $newId = 'P001';

        if(!empty($lastId)){
            $prefix = substr($lastId->id, 0, 3);
            $suffix = substr($lastId->id, 3);
            $newId = $prefix.''.intval($suffix)+1;
        }       

        $newProduct->id = $newId;
        $newProduct->name = $request->name;
        $newProduct->description = $request->description;
        $newProduct->directions = $request->directions;
        $newProduct->price= $request->price;
        $newProduct->in_stock = $request->in_stock;
        $newProduct->category_id = $request->category_id;
        $newProduct->user_id = Auth::id();

        $imagePath = $request->file('image')->store('public/images');
        $newProduct->image = str_replace('public/', '', $imagePath);;
        $newProduct->updated_at = null;
        $newProduct->save();

        return Redirect::back()->with('message', 'Product created successfully!');
    }

    public function edit(Product $product){
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'baseUrl' => asset('storage')
        ]);
    }

    public function update(Product $product, Request $request){
        // validate
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'directions' => 'required|string|max:65535',
            'price' => 'required|numeric|regex:/^\d{1,6}(\.\d{1,2})?$/',
            'in_stock' => 'required|integer',
            'category_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|string|max:2048'
        ]);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->directions = $request->directions;
        $product->price= $request->price;
        $product->in_stock = $request->in_stock;
        $product->category_id = $request->category_id;
        $product->user_id = Auth::id();

        if($request->hasFile('image')){
            // Delete old image if exists
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('public/images');
            $product->image = str_replace('public/', '', $imagePath);            
        }
        
        $product->updated_at = now();
        $product->save();

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
            'baseUrl' => asset('storage')
        ]);
    }

    public function delete(Product $product){
        $product->delete();
        return redirect()->route('products')->with('message', 'Product deleted successfully!');
    }

    public function view(Product $product){
        return Inertia::render('Products/View', [
            'product' => $product,
            'storageUrl' => asset('storage')
        ]);
    }
}

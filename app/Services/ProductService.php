<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getAllProducts()
    {
        return Product::with('category')->latest()->paginate(10);
    }

    public function createProduct($data)
    {
        // Generate the next ID based on the existing IDs in the database
        $lastId = Product::orderBy('created_at', 'desc')->first();
        $newId = 'P001';

        if (!empty($lastId)) {
            $prefix = substr($lastId->id, 0, 3);
            $suffix = substr($lastId->id, 3);
            $newId = $prefix . '' . (intval($suffix) + 1);
        }

        $data['id'] = $newId;
        $data['user_id'] = Auth::id();

        if (isset($data['image'])) {
            $imagePath = $data['image']->store('public/images');
            $data['image'] = str_replace('public/', '', $imagePath);
        }

        $data['updated_at'] = null;

        return Product::create($data);
    }

    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    public function updateProduct($id, $data)
    {
        $product = Product::findOrFail($id);

        $data['user_id'] = Auth::id();

        if (isset($data['image'])) {
            // Delete old image if exists
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            // Store new image
            $imagePath = $data['image']->store('public/images');
            $data['image'] = str_replace('public/', '', $imagePath);
        }

        if (isset($data['image_url'])) {
            unset($data['image_url']);
        }

        $data['updated_at'] = now();
        $product->update($data);

        return $product;
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return $product;
    }
}

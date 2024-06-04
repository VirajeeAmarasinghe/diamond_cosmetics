<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = ['id', 'name', 'description', 'directions', 'price', 'in_stock', 'category_id', 'user_id', 'image', 'created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // ✅ Table name (optional if same as "products")
    protected $table = 'products';

    // ✅ Mass assignable fields
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'image',
        'in_stock'
    ];

    // ✅ Casts (VERY IMPORTANT)
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'in_stock' => 'boolean',
    ];

    // ✅ Accessor (optional - auto full image URL)
    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : null;
    }
}
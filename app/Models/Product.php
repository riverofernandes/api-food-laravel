<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';

    protected $primaryKey = 'code';
    public $incrementing = false; // O código é fornecido pela API, não autoincrementado

    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'url',
        'creator',
        'created_t',
        'last_modified_t',
        'product_name',
        'quantity',
        'brands',
        'categories',
        'labels',
        'cities',
        'purchase_places',
        'stores',
        'ingredients_text',
        'traces',
        'serving_size',
        'serving_quantity',
        'nutriscore_score',
        'nutriscore_grade',
        'main_category',
        'image_url',
        'imported_t',
        'status'
    ];

    protected $casts = [
        'created_t' => 'datetime',
        'last_modified_t' => 'datetime',
        'imported_t' => 'datetime',
        'serving_quantity' => 'float',
        'nutriscore_score' => 'integer',
        'status' => 'string',
    ];

    /**
     * Define o valor padrão para `imported_t` na criação do produto.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->imported_t)) {
                $product->imported_t = now();
            }
        });
    }
}

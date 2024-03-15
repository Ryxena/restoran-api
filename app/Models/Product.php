<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'products';
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'stock',
        'image'
    ];

}

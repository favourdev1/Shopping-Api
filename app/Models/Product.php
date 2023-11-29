<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'brand',
        'image1',
        'image2',
        'image3',
        'image4',
        'image5',
        'weight',
        'quantity_in_stock',
        'tags',
        'refundable',
    ];
}

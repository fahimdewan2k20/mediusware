<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
  protected $fillable = [
    'product_id', 'variant_id', 'variant'
  ];

  // public function productVariantPrice() {
  //   $this->hasMany(ProductVariantPrice::class, ['product_variant_one', 'product_variant_two', 'product_variant_three'], 'id');
  // }

  
}

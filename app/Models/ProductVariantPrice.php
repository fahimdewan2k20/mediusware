<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
  protected $fillable = [
    'product_id', 'product_variant_one', 'product_variant_two', 'product_variant_three', 'price', 'stock'
  ];

  // public function variant_names() {
  //   return $this->belongsToMany(ProductVariant::class, ['id', 'id', 'id'], ['product_variant_one', 'product_variant_two', 'product_variant_three']);
  // }

  public function products()
  {
    return $this->belongsToMany(Product::class);
  }

  public function name1()
  {
    return $this->hasOne(ProductVariant::class, 'id', 'product_variant_one');
  }
  public function name2()
  {
    return $this->hasOne(ProductVariant::class, 'id', 'product_variant_two');
  }
  public function name3()
  {
    return $this->hasOne(ProductVariant::class, 'id', 'product_variant_three');
  }
}

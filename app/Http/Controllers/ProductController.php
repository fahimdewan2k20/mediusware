<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        // dd($products = Product::where('title', 'LIKE', '%' . $_GET['title'] . '%'));
        if (isset($_GET['title'])) {
            $title = $_GET['title'];
            $price_from = $_GET['price_from'] ? $_GET['price_from'] : 0;
            $price_to = $_GET['price_to'] ? $_GET['price_to'] : ProductVariantPrice::max('price');
            $date = $_GET['date'];

            // if ($price_from != null && $price_to != null) {
            //     $products = ProductVariantPrice::where('price', '>', $price_from)->where('price', '<', $price_to)->products;
            // } else if ($price_from != null) {
            //     $products = ProductVariantPrice::where('price', '>', $price_from)->products;
            // } else if ($price_to != null) {
            //     $products = ProductVariantPrice::where('price', '<', $price_from)->products;
            // } else {
            //     $products = Product::get();
            // }

            $products = Product::where('title', 'LIKE', '%' . $_GET['title'] . '%');

            if ($date != null) {
                $products = $products->where('created_at', 'LIKE', '%' . $date . '%');
            }

            return view('products.index')->with('products', $products->paginate(2))->with('title', $title)->with('price_from', $price_from)->with('price_to', $price_to)->with('date', $date);
        } else {
            $products = Product::paginate(2);
            $price_from = 0;
            $price_to = ProductVariantPrice::max('price');
            return view('products.index')->with('products', $products)->with('price_from', $price_from)->with('price_to', $price_to);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $product = Product::where('sku', $request->sku)->first();

        if ($product != null) {
            return ['success' => false];
        }

        // adding product to product table
        $product = Product::create([
            'title' => $request->title,
            'sku' => $request->sku,
            'description' => $request->description
        ]);

        // adding product image to product_images table
        if($request->hasFile('file_name')){
            $file_name = time()."_".$request->file('file_name')->getClientOriginalName();
            $request->file('file_name')->storeAs('uploads', $file_name, 'public');
        }

        $productVariantPrices = $request->product_variant_prices;
        if(count($productVariantPrices) == 0) {
            return ['success' => true];
        }

        // $productVariant = new ProductVariant();
        // $productVariant->product_id = 5;
        // $productVariant->variant_id = 2;
        // $productVariant->variant = 'red';
        // $productVariant->save();

        // adding product variants to product_variant table
        $productVariants_temp = [];
        $productVariants_temp[1] = [];
        $productVariants_temp[2] = [];
        $productVariants_temp[6] = [];
        
        foreach ($request->product_variant as $pv) {
            $arr = [];
            foreach ($pv['tags'] as $tag) {
                $productVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_id' => $pv['option'],
                    'variant' => $tag
                ]);
                
                array_push($arr, $productVariant->id);
            }
            $productVariants_temp[$pv['option']] = $arr;
        }


        // adding product_variant_prices
        function addProductVariantPrice($id, $pv1, $pv2, $pv3, $price, $stock) {
            $productVariantPrice = ProductVariantPrice::create([
                'product_id' => $id,
                'product_variant_one' => $pv1,
                'product_variant_two' => $pv2,
                'product_variant_three' => $pv3,
                'price' => $price,
                'stock' => $stock
            ]);
        }
        
        $productVariants = [];
        foreach ($productVariants_temp as $pv) {
            $variant = $pv;
            if (count($variant) == 0) {
                array_push($variant, null);
            }
            array_push($productVariants, $variant);
        }
        
        $i = 0;
        foreach ($productVariants[0] as $pv1) {
            foreach ($productVariants[1] as $pv2) {
                foreach ($productVariants[2] as $pv3) {
                    addProductVariantPrice($product->id, $pv1, $pv2, $pv3, $productVariantPrices[$i]['price'], $productVariantPrices[$i++]['stock']);
                }
            }
        }

        // if (count($productVariants[1]) > 0 && count($productVariants[2]) > 0 && count($productVariants[6]) > 0) {
        //     foreach ($productVariants[1] as $pv1) {
        //         foreach ($productVariants[2] as $pv2) {
        //             foreach ($productVariants[6] as $pv6) {
        //                 addProductVariantPrice($product->id, $pv1, $pv2, $pv3, );
        //             }
        //         }
        //     }
        // }
        // else if (count($productVariants[1]) > 0 && count($productVariants[2]) > 0) {
    
        // }
        // else if (count($productVariants[1]) > 0 && count($productVariants[6]) > 0) {
    
        // }
        // else if (count($productVariants[2]) > 0 && count($productVariants[6]) > 0) {
    
        // }
        // else if (count($productVariants[1]) > 0) {
    
        // }
        // else if (count($productVariants[2]) > 0) {
    
        // }
        // else if (count($productVariants[6]) > 0) {
    
        // }
        
        return ['success' => true];
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}

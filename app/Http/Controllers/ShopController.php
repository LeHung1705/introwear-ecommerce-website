<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class ShopController extends Controller
{
    public function index(Request $request){
        $sort = $request->get('sort', 'default'); 
        
        $query = Product::query();
        
        // Logic sắp xếp
        switch ($sort) {
            case 'best_selling':
                $query->orderBy('id', 'asc'); 
                break;
                
            case 'price_low_high':
                $query->orderByRaw('CASE WHEN price_sale IS NOT NULL AND price_sale > 0 THEN price_sale ELSE price END ASC');
                break;
                
            case 'price_high_low':
                $query->orderByRaw('CASE WHEN price_sale IS NOT NULL AND price_sale > 0 THEN price_sale ELSE price END DESC');
                break;
                
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
                
            default:
                $query->orderBy('id', 'desc');
                break;
        }
        
        $products = $query->paginate(12)->appends(request()->query());
        
        return view('shop', compact('products', 'sort'));
    }
    
    public function product_details($id)
    {
        $product = Product::where('id', $id)->first();
        $rproducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        $viewed = session()->get('viewed_products', []);

        if (!in_array($id, $viewed)) {
            $viewed[] = $id;

            if (count($viewed) > 20) {
                array_shift($viewed); // bỏ sản phẩm cũ nhất
            }

            session(['viewed_products' => $viewed]);
        }
        $viewedProducts = Product::whereIn('id', array_reverse($viewed))
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        return view('details', compact('product','rproducts', 'viewedProducts'));
    }
}
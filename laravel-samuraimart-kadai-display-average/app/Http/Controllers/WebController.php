<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MajorCategory;
use App\Models\Product;

class WebController extends Controller
{
    public function index(){
        $categories = Category::all();

        $major_categories = MajorCategory::all();

        $recently_products = Product::orderBy('created_at','desc')->take(4)->get();
        $recommend_products = Product::where('recommend_flag',true)->take(3)->get();
        
        foreach ($recently_products as $recently_product) {
            $recently_product->reviews_average = $recently_product->reviews()->avg('score');
        }
        foreach ($recommend_products as $recommend_product) {
            $recommend_product->reviews_average = $recommend_product->reviews()->avg('score');
        }

        return view('web.index',compact('major_categories','categories','recently_products','recommend_products'));
    }
}

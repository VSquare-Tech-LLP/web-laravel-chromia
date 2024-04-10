<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pack;
use Illuminate\Http\Request;

class AppApiController extends Controller
{

    public function packs(Request $request, Category $category = null)
    {
        if ($category) {
            $packs = $category->packs()->with('photos')->get();
        } else {
            $packs = Pack::with('photos')->get();
        }
        return app_json($packs);
    }
    public function packImages(Pack $pack)
    {
        $packs = $pack::with('photos')->get();
        return app_json($packs);
    }

    public function categories()
    {
        $category = Category::with('packs')->get();
        return app_json($category);
    }
}

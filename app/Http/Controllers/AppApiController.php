<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Pack;
use App\Models\Photo;
use Exception;
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

    public function packImages(Request $request, $pack)
    {
        try {
            $pack_eq = Pack::with('photos')->find($pack);
            if ($pack_eq) {
                //$pack_with_images = $pack_eq->with('photos')->first();
                return app_json($pack_eq);
            } else {
                return response()->json(['status' => 'failure', 'message' => "Requested pack not found"], 404);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'failure', 'message' => "Requested pack not found"], 404);
        }
    }

    public function getRandomImages(Request $request)
    {
        try {
            $randomPhotos = Photo::select('id', 'url')->inRandomOrder()->take(10)->get();
            return app_json($randomPhotos);
        } catch (Exception $e) {
            return response()->json(['status' => "failure", 'message' => $e->getMessage()], 500);
        }
    }

    public function categories()
    {
        $category = Category::with('packs')->get();
        return app_json($category);
    }
}

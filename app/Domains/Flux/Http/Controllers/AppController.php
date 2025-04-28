<?php

namespace App\Domains\Flux\Http\Controllers;

use App\Domains\Flux\Models\Photo;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;


class AppController extends Controller
{
    public function home_old(Request $request){

        $catwisedata = [];

        $photos = Photo::select('id','url','prompt','category_id')
        ->with('category')
        ->get()->map(function ($photo) {
            // Transform the response to include only the `category_name`
            return [
                'id' => $photo->id,
                'url' => $photo->url,
                'prompt' => $photo->prompt,
                //'category_id' => $photo->category_id,
                'category_name' => $photo->category->name, // Include only the category name
            ];
        });

        /* foreach($photos as $item){
            if(!in_array($item['category_name'],$catwisedata)){
                $catwisedata[] = $item['category_name'];
            }
                 
        } */
        foreach($photos as $item){
            $catname = $item['category_name'];
            $catwisedata[$catname][] = [
                'id' => $item['id'],
                'url' => $item['url'],
                'prompt' => $item['prompt'],
            ];     
        }

        return app_data(true,$photos);
        //return app_data(true,$catwisedata);
    }

    public function home(Request $request){
        
        $catwisedata = [];

        $categories = DB::table("categories")->get();
        foreach($categories as $item){
            $data = [];
            $data = [
                'id'=>$item->id,
                'category_name'=>$item->name,
            ];

            $photos = DB::table("photos")
                        ->select('photos.id','photos.name','photos.thumbnail','photos.url','photos.prompt','categories.id as category_id','categories.name as category_name')
                        ->leftJoin('categories', 'categories.id', '=', 'photos.category_id')
                        ->where("category_id",$item->id)
                        ->offset(0)->limit(5)->get();
            foreach($photos as $item){
                $item->thumbnail = url("storage/source_images/thumbnails/".$item->thumbnail);
            }

            $data['photos'] = $photos;
            $catwisedata[] = $data;
        }

        return app_data(true,$catwisedata);     

    }

    public function categoryPhotos(Request $request){
        $category_id = $request->category_id;
        $offset=0;
        $offset = $request->offset;

        $data = [];

        $total_photos = DB::table("photos")->where("category_id",$category_id)->count();

        $photos = DB::table("photos")
                    ->select('photos.id','photos.name','photos.url','photos.prompt','categories.id as category_id','categories.name as category_name')
                    ->leftJoin('categories', 'categories.id', '=', 'photos.category_id')
                    ->where("category_id",$category_id)
                    ->offset($offset)->limit(10)->get();
         
        foreach($photos as $item){
            $item->thumbnail = url("storage/source_images/thumbnails/".$item->name);
        }                    

        $next_offset = ($offset + 10);
        if($next_offset > $total_photos){
            $next_offset=$offset;
        }

        $data['next_offset'] = $next_offset;
        $data['photos'] = $photos;

        return app_data(true,$data);     

    }
}

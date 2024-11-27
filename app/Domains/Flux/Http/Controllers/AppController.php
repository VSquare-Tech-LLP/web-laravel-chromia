<?php

namespace App\Domains\Flux\Http\Controllers;

use App\Domains\Flux\Models\Photo;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;


class AppController extends Controller
{
    public function home(Request $request){
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
        return app_data(true,$photos);
    }
}

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
        $photos = Photo::simplePaginate(10);
        return app_data(true,$photos);
    }
}

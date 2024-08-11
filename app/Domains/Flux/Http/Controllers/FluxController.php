<?php

namespace App\Domains\Flux\Http\Controllers;

use App\Domains\Flux\Services\ReplicateApi;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FluxController extends Controller
{
    public function generate(Request $request){
        $defauts = [];
        $defauts['prompt'] = $request->prompt;
        $defauts['output_quality'] = $request->output_quality ?? 90;
        $defauts['num_outputs'] = $request->num_outputs ?? 4;
        $defauts['aspect_ratio'] = $request->aspect_ratio ?? '1:1';
        $defauts['output_format'] = $request->output_format ?? 'jpeg';
        $body = $defauts;
        try{
            $raplicate = new ReplicateApi();
            $response = $raplicate->sendRequest($body);
            if($response && $response->id){
                return app_data(true,['id'=>$response->id]);
            }else{
                return app_data(false,null,200);
            }
        }catch(Exception $e){
            return app_data(false,['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
        }
    }

    public function getresults(Request $request){
        $id = $request->id;
        $raplicate = new ReplicateApi();
        $response = $raplicate->getResults($id);
        ds($response);
        if($response && $response->output){
            $localimages = $this->storeLocaly($response->output);
            return app_data(true,$localimages);
        }else{
            return app_data(false,null,200);
        }
    }
    
    public function storeLocaly($imageUrls, $addwatermark = false)
    {
        $dir = 'results'; // Define your storage path
        $publicUrls = [];
        foreach ($imageUrls as $image_result) {
            $imageData = file_get_contents($image_result);
            if ($imageData !== false || $imageData != "") {
                $fileName = time()."_".str_random(4). basename($image_result);
                Storage::disk('public')->put($dir . '/' . $fileName, $imageData);
                $publicUrl = asset("storage/$dir/$fileName");
                $publicUrls[] = $publicUrl;
            } 
        }
        return $publicUrls;
    }
}

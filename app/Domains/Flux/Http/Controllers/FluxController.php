<?php

namespace App\Domains\Flux\Http\Controllers;

use App\Domains\Flux\Services\LogService;
use App\Domains\Flux\Services\ReplicateApi;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $settings = $defauts;
        unset($settings['prompt']);
        if($request->has('append') && $request->append!==''){
            $defauts['prompt'] = $request->prompt . ', ' . $request->append;
            $settings['append'] = $request->append;
        }
        $body = $defauts;
        try{
            $log = new LogService();
            $raplicate = new ReplicateApi();
            $response = $raplicate->sendRequest($body);
            if($response && $response->id){
                $log->addLog($request->ip(), $request->prompt, json_encode($settings), $response->id, $request->is_paid, $request->device_id);
                return app_data(true,['id'=>$response->id]);
            }else{
                $log->addLog($request->ip(), $request->prompt, $body, null, $request->is_paid, $request->device_id);
                return app_data(false,null,200);
            }
        }catch(Exception $e){
            return app_data(false,['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
        }
    }

    public function getresults(Request $request){
        $id = $request->id;
        $log = new LogService();
        $hasLogged = $log->findLog($id);
        if($hasLogged && $hasLogged->results){
            $results = json_decode($hasLogged->results);
            if(is_array($results)){
                return app_data(true,json_decode($hasLogged->results));
            }elseif (!is_array($results) &&$results->status == 'failed'){
               return app_data(false,$results,200);
           } 
           return app_data(true,json_decode($hasLogged->results));
        }
        $raplicate = new ReplicateApi();
        $response = $raplicate->getResults($id);
        if($response && $response->status){
            if($response->status === 'failed'){
                $log->updateFailedStatus($id,['status'=>'failed','error'=>$response->error]);
                return app_data(false,['status'=>'failed','error'=>$response->error],200);
            }
            if($response->output){
                //Log::info(json_encode($response));
                $localimages = $this->storeLocaly($response->output);
                $log->updateResultLog($id, $localimages);
                return app_data(true,$localimages);
            }else{
                //Log::info(json_encode($response));
                return app_data(false,null,200);
            }
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

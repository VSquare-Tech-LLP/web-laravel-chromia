<?php

namespace App\Domains\Flux\Http\Controllers;

use App\Domains\Flux\Services\IpCheckerService;
use App\Domains\Flux\Services\LogService;
use App\Domains\Flux\Services\ReplicateApi;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
        $defauts['disable_safety_checker'] = env('DISABLE_SAFETY_CHECKER', false);
        $settings = $defauts;

        $sketch_level = $request->level ?? 'medium';

        unset($settings['prompt']);

        $custom_sketch_prompt = env('CUSTOM_SKETCH_PROMPT');

        switch($sketch_level){
            case 'easy':
                $sketch_level_prompt = env('EASY_SKETCH_PROMPT');
                break;  
            case 'hard':
                $sketch_level_prompt = env('HARD_SKETCH_PROMPT');
                break;         
            default:
                $sketch_level_prompt = env('MEDIUM_SKETCH_PROMPT');
                break;      
        }


        if($request->has('append') && trim($request->append)!==''){
            $defauts['prompt'] = $request->prompt . ', ' . $request->append;
            $settings['append'] = $request->append;
        }else{
            //$defauts['prompt'] = $request->prompt . ', high-contrast minimal line art, precise black lines on white background, clean vector-like quality, professional illustration style, sharp detailed outlines, no shading, continuous smooth strokes, minimalist aesthetic, technical drawing precision';    
            
            $defauts['prompt'] = $request->prompt . $custom_sketch_prompt.$sketch_level_prompt;    
        }
    
        $body = $defauts;
        try{
            $ipChecker = new IpCheckerService($request->ip());
            if($ipChecker->isApple){
                $body['disable_safety_checker'] = false;
            }

            $log = new LogService();
            $raplicate = new ReplicateApi();
            $response = $raplicate->sendRequest($body);
            $plan = $request->plan ?? null;
            $purchase_date = ($request->purchase_date)? $this->parseDate($request->purchase_date) : null;
            $device_id = $request->device_id ?? null;
            $app_ver = $request->app_ver ?? null;
            if($response && $response->id){
                $log->addLog($request->ip(), $request->prompt, json_encode($settings), $response->id, $request->is_paid, $device_id, 'flux', $plan, $purchase_date,$app_ver);
                return app_data(true,['id'=>$response->id]);
            }else{
                $log->addLog($request->ip(), $request->prompt, json_encode($settings), null, $request->is_paid, $request->device_id, 'flux', $plan, $purchase_date,$app_ver);
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
            if($response->status == 'processing'){
                return app_data(true,[],200);
            }
            if($response->output){
                //Log::info(json_encode($response));
                $localimages = $this->storeLocaly($response->output);
                $log->updateResultLog($id, $localimages);
                return app_data(true,$localimages);
            }else{
                return app_data(false,null,200);
            }
        }else{
            return app_data(true,[],200);
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

    function parseDate($dateInput) {
        try{
        $date = Carbon::parse($dateInput);
            // Check if the year is out of the reasonable range
            if ($date->year < 1900 || $date->year > 2050) {
                // Set the year to 2035 if out of range
                $date->year = 2035;
            }
            return $date->toDateTimeString();
        }catch(Exception $e){
            return null;
        }
    }
}

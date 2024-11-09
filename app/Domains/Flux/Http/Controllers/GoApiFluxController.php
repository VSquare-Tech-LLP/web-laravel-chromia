<?php

namespace App\Domains\Flux\Http\Controllers;

use App\Domains\Flux\Services\GoApi;
use App\Domains\Flux\Services\IpCheckerService;
use App\Domains\Flux\Services\LogService;
use App\Domains\Flux\Services\ReplicateApi;
use App\Http\Controllers\Controller;
use App\Services\GoApiService;
use App\Services\SlackNotificationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GoApiFluxController extends Controller
{
    public function generate(Request $request){
        $defauts = [];
        $defauts['prompt'] = $request->prompt;
        $defauts['width'] = 1024;
        $defauts['height'] = 1024;
        // $defauts['output_quality'] = $request->output_quality ?? 90;
        // $defauts['num_outputs'] = $request->num_outputs ?? 4;
        // $defauts['aspect_ratio'] = $request->aspect_ratio ?? '1:1';
        // $defauts['output_format'] = $request->output_format ?? 'jpeg';
        // $defauts['disable_safety_checker'] = env('DISABLE_SAFETY_CHECKER', false);
        $settings = $defauts;
        unset($settings['prompt']);
        if($request->has('append') && $request->append!==''){
            $defauts['prompt'] = $request->prompt . ', ' . $request->append;
            $settings['append'] = $request->append;
        }
        $body = $defauts;
        try{
            // $ipChecker = new IpCheckerService($request->ip());
            // if($ipChecker->isApple){
            //     $body['disable_safety_checker'] = false;
            // }

            $log = new LogService();
            $raplicate = new GoApi();
            $response = $raplicate->sendRequest($body);
            $plan = $request->plan ?? null;
            $purchase_date = ($request->purchase_date)? $this->parseDate($request->purchase_date) : null;
            $device_id = $request->device_id ?? null;
            $app_ver = $request->app_ver ?? null;
            
            if($response && $response->data->task_id){
                $log->addLog($request->ip(), $request->prompt, json_encode($settings), $response->data->task_id, $request->is_paid, $device_id, 'goapi-flux', $plan, $purchase_date,$app_ver);
                return app_data(true,['id'=>$response->data->task_id]);
            }else{
                $log->addLog($request->ip(), $request->prompt, json_encode($settings), null, $request->is_paid, $request->device_id, 'goapi-flux', $plan, $purchase_date,$app_ver);
                return app_data(false,null,200);
            }
        }catch(Exception $e){
            if (env('APP_ENV') != "local") {
                $sender = new SlackNotificationService();
                $sender->sendNotification("Ai Flux App", "Error", $e->getMessage());
            }
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
        $raplicate = new GoApi();
        $response = $raplicate->getResults($id);
        if($response && $response->data->status){
            if($response->data->status === 'failed'){
                $log->updateFailedStatus($id,['status'=>'failed','error'=>$response->data->error]);
                return app_data(false,['status'=>'failed','error'=>$response->data->error],200);
            }
            if($response->data->output && $response->data->output->image_url){
                //Log::info(json_encode($response->data));
                $localimages = $this->storeLocaly([$response->data->output->image_url]);
                $log->updateResultLog($id, $localimages);
                return app_data(true,$localimages);
            }else{
                //Log::info(json_encode($response->data));
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

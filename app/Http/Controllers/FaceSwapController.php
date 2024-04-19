<?php

namespace App\Http\Controllers;

use App\Models\FaceSwapTask;
use App\Models\Pack;
use App\Services\GoApiService;
use App\Services\ReplicateApi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FaceSwapController extends Controller
{
    public function goApiFaceSwap(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'source' => 'required|image|mimes:jpeg,png,jpg,gif',
                'target' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            $rq_time = time();
            // Upload images to storage
            $sourceImage = $request->file('source')->storeAs('images', $rq_time . '_ri1.jpg');
            $targetImage = $request->file('target')->storeAs('images', $rq_time . '_ri2.jpg');

            // Perform image analysis (you can use a third-party API or your own logic)
            $sourceImgB64 = "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $sourceImage)));
            $targetImgB64 = "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $targetImage)));

            $goApiService = new GoApiService();
            $task_uuid = $goApiService->requestSwap($sourceImgB64, $targetImgB64);

            // Return the analysis result
            return app_json(['task_uuid' => $task_uuid]);
        } catch (Exception $e) {
            return response()->json(['status' => "failure", 'message' => $e->getMessage()], 500);
        }
    }

    public function goApiFaceSwapResults(Request $request)
    {
        try {
            // Validate the request
            $task_uuid = $request->task_uuid;
            $goApiService = new GoApiService();
            $analysisResult = $goApiService->swapResult($task_uuid);
            if ($analysisResult != "") {
                $localImage = $this->storeLocaly($analysisResult);
            } else {
                return response()->json(['status' => "failure", 'message' => "Check the logs."], 500);
            }
            // Return the analysis result
            return app_json($localImage);
        } catch (Exception $e) {
            return response()->json(['status' => "failure", 'message' => $e->getMessage()], 500);
        }
    }

    public function goApiFaceSwapPack(Request $request)
    {
        //return $this->storeLocaly('https://i.pinimg.com/originals/c0/0d/a7/c00da774552c2a500e4f2f9c17f779fe.jpg');
        try {
            // Validate the request
            $request->validate([
                'source' => 'required|image|mimes:jpeg,png,jpg,gif',
                'pack_id' => 'required',
            ], [
                'source.required' => 'Source field is required',
                'pack_id.required' => 'Pack id is required'
            ]);

            $rq_time = time();
            $selected_pack = Pack::find($request->pack_id);
            // Upload images to storage
            $sourceImage = $request->file('source')->storeAs('images', $rq_time . '_ri1.jpg');
            $targetImages = $selected_pack->photos->pluck('path');
            if ($targetImages->count() == 0) {
                return response()->json([
                    'status' => 'failure',
                    'message' => "Selected pack does not have images"
                ]);
            }
            // Create a new task record in the face_swap_tasks table
            $task = new FaceSwapTask;
            $task->source_image = $sourceImage; // the path to the stored source image
            $task->target_images = json_encode($targetImages); // the paths to the stored target images
            $task->status = 'pending';
            $task->save();

            // Analyze the images
            $analysisResults = $this->analyzePackGoApiBatch($sourceImage, $selected_pack);

            // Update the task with the results
            $task->results = json_encode($analysisResults);
            $task->status = 'processing'; // or 'failed' if any of the analyses did not succeed
            $task->save();

            return response()->json([
                'status' => 'success',
                'data' => ['task_id' => $task->id, 'task_details' => $analysisResults]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // This will return the validation errors.
            return response()->json(['status' => "failure", 'message' => 'Something went wrong.'], 422);
        } catch (Exception $e) {
            return response()->json(['status' => "failure", 'message' => 'Something went wrong', 'error' => [$e->getMessage(), $e->getLine(), $e->getTrace()]], 500);
        }
    }

    private function analyzePackGoApiBatch($sourceImage, $pack)
    {
        $replicateService = new ReplicateApi();
        $analysisResults = [];
        //TODO:remove take(2) when testing is over.
        foreach ($pack->photos->take(2)->pluck('url') as $targetImage) {
            // $body = json_encode([
            //     'version' => env('MODEL_VERSION'),
            //     'input' => [
            //         'swap_image' => "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $sourceImage))),
            //         'target_image' => (app()->isLocal()) ? "data:image/png;base64," . base64_encode(file_get_contents($targetImage)) : $targetImage,
            //     ]
            // ]);
            //$data = $replicateService->getFaceSwap($body);
            $sourceImgB64 = "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $sourceImage)));
            $targetImgB64 = (app()->isLocal()) ? "data:image/png;base64," . base64_encode(file_get_contents($targetImage)) : $targetImage;
            $goApiService = new GoApiService();
            $task_uuid = $goApiService->requestSwap($sourceImgB64, $targetImgB64);
            Log::info("GoApi FaceSwap Response ", ["task_uuid" => $task_uuid]);

            // Here you can decide whether to continue processing if one fails,
            // or maybe you want to collect the successful ones and report any failures separately.
            if ($task_uuid != "") {
                $analysisResults[] = $task_uuid;
            } else {
                // Handle the error accordingly, you might want to log the error or add it to an errors array.
                Log::error("Error processing target image {$targetImage}", ["error" => "Check the logs."]);
                $analysisResults[] = [
                    'status' => 'failed',
                    'target_image' => $targetImage,
                    'error' => 'Failed to process image',
                ];
            }
        }
        return $analysisResults;
    }

    public function goApiFaceSwapPackResult($taskId)
    {
        try {
            $task = FaceSwapTask::find($taskId);
            // Check if the task exists
            if (!$task) {
                return response()->json(['status' => "failure", 'message' => "Task not found"], 404);
            }
            // Check if the task has completed  `
            if ($task->status == 'completed') {
                return app_json(json_decode($task->results));
            }
            // Check if the task has results
            if ($task->results) {
                $goApiService = new GoApiService();
                $final_results = [];
                $final_status = 'success';
                // Loop through the results
                foreach (json_decode($task->results) as $result) {
                    $task_uuid = $result;
                    $data = $goApiService->swapResult($task_uuid);
                    Log::info("GoApi FaceSwap Result ", ["image" => $data]);
                    // Check if the task has completed
                    if ($data) {
                        $final_results[] = $this->storeLocaly($data);
                        //$final_results[] = $data;
                    } else {
                        $final_results[] = "";
                        $final_status = "failure";
                    }
                    // if ($data->status == "succeeded") {
                    //     $data = $data->output;

                    // } elseif ($data->status == "failed") {
                    //     $final_status = "failure";
                    // } else {
                    //     $final_status = "processing";
                    //     $final_results[] = ['status' => $data->status, 'data' => $data];
                    // }
                }
                // Update the task status
                if ($final_status == "success") {
                    $task->results = $final_results;
                    $task->status = 'completed';
                    $task->save();
                } elseif ($final_status == "failure") {
                    $task->results = $final_results;
                    $task->status = 'failed';
                    $task->save();
                }

                return app_json($final_results, $final_status);
            }
            return response()->json(['status' => "failure", 'message' => "Task could not be found."], 404);
        } catch (Exception $e) {
            Log::error("generate image issue " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
            return response()->json(['status' => "failure", 'message' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }

    public function uploadImages(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'source' => 'required|image|mimes:jpeg,png,jpg,gif',
                'target' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            $rq_time = time();
            // Upload images to storage
            $sourceImage = $request->file('source')->storeAs('images', $rq_time . '_ri1.jpg');
            $targetImage = $request->file('target')->storeAs('images', $rq_time . '_ri2.jpg');

            // Perform image analysis (you can use a third-party API or your own logic)
            $analysisResult = $this->analyzeImages($sourceImage, $targetImage);

            // Return the analysis result
            return app_json($analysisResult);
        } catch (Exception $e) {
            return response()->json(['status' => "failure", 'message' => $e->getMessage()], 500);
        }
    }

    public function uploadImageBatch(Request $request)
    {
        try {
            // ... existing validation for the 'source'
            // Validate the 'targets' as an array of images
            $request->validate([
                'source' => 'required|image|mimes:jpeg,png,jpg',
                'targets' => 'required|array|min:1|max:10',
                'targets.*' => 'image|mimes:jpeg,png,jpg|max:50000',
            ], [
                'targets.required' => 'The targets field is required.',
                'targets.array' => 'The targets field must be an array.',
                'targets.min' => 'At least one target image is required.',
                'targets.max' => 'No more than 10 target images are allowed.',
                'targets.*.image' => 'Each target must be an image.',
                'targets.*.mimes' => 'Each target must be a jpeg, png, jpg, or gif file.',
                'targets.*.max' => 'Each target image must not exceed 5000 kilobytes in size.',
            ]);

            $rq_time = time();
            $sourceImage = $request->file('source')->storeAs('images', $rq_time . '_source.jpg');
            $targetImages = [];
            foreach ($request->file('targets') as $index => $file) {
                $targetImages[] = $file->storeAs('images', $rq_time . '_target_' . $index . '.jpg');
            }

            // Create a new task record in the face_swap_tasks table
            $task = new FaceSwapTask;
            $task->source_image = $sourceImage; // the path to the stored source image
            $task->target_images = json_encode($targetImages); // the paths to the stored target images
            $task->status = 'pending';
            $task->save();

            // Analyze the images
            $analysisResults = $this->analyzeImagesBatch($sourceImage, $targetImages);

            // Update the task with the results
            $task->results = json_encode($analysisResults);
            $task->status = 'processing'; // or 'failed' if any of the analyses did not succeed
            $task->save();

            return response()->json([
                'status' => 'success',
                'data' => ['task_id' => $task->id, 'task_details' => $analysisResults]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // This will return the validation errors.
            return response()->json(['status' => "failure", 'message' => 'Something went wrong.'], 422);
        } catch (Exception $e) {
            return response()->json(['status' => "failure", 'data' => $e->getMessage()], 500);
        }
    }
    public function uploadImagePack(Request $request)
    {
        //return $this->storeLocaly('https://i.pinimg.com/originals/c0/0d/a7/c00da774552c2a500e4f2f9c17f779fe.jpg');
        try {
            // Validate the request
            $request->validate([
                'source' => 'required|image|mimes:jpeg,png,jpg,gif',
                'pack_id' => 'required',
            ], [
                'source.required' => 'Source field is required',
                'pack_id.required' => 'Pack id is required'
            ]);

            $rq_time = time();
            $selected_pack = Pack::find($request->pack_id);
            // Upload images to storage
            $sourceImage = $request->file('source')->storeAs('images', $rq_time . '_ri1.jpg');
            $targetImages = $selected_pack->photos->pluck('path');
            if ($targetImages->count() == 0) {
                return response()->json([
                    'status' => 'failure',
                    'message' => "Selected pack does not have images"
                ]);
            }
            // Create a new task record in the face_swap_tasks table
            $task = new FaceSwapTask;
            $task->source_image = $sourceImage; // the path to the stored source image
            $task->target_images = json_encode($targetImages); // the paths to the stored target images
            $task->status = 'pending';
            $task->save();

            // Analyze the images
            $analysisResults = $this->analyzePackBatch($sourceImage, $selected_pack);

            // Update the task with the results
            $task->results = json_encode($analysisResults);
            $task->status = 'processing'; // or 'failed' if any of the analyses did not succeed
            $task->save();

            return response()->json([
                'status' => 'success',
                'data' => ['task_id' => $task->id, 'task_details' => $analysisResults]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // This will return the validation errors.
            return response()->json(['status' => "failure", 'message' => 'Something went wrong.'], 422);
        } catch (Exception $e) {
            return response()->json(['status' => "failure", 'message' => 'Something went wrong', 'error' => [$e->getMessage(), $e->getLine(), $e->getTrace()]], 500);
        }
    }

    private function analyzeImages($sourceImage, $targetImage)
    {

        $replicateService = new ReplicateApi();

        $body = json_encode([
            'version' => env('MODEL_VERSION'),
            'input' => [
                'swap_image' => "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $sourceImage))),
                'target_image' => "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $targetImage))),
            ]
        ]);
        $data = $replicateService->getFaceSwap($body);
        Storage::put('requests/' . $data->id . '_' . time() . '.json', json_encode(['request' => $body]));
        Storage::put('requests/' . $data->id . '_r_' . time() . '.json', json_encode(['result' => $data]));
        Log::info("replicate response", ["response" => $data]);
        return $data;
    }

    private function analyzeImagesBatch($sourceImage, array $targetImages)
    {
        $replicateService = new ReplicateApi();
        $analysisResults = [];

        foreach ($targetImages as $targetImage) {
            $body = json_encode([
                'version' => env('MODEL_VERSION'),
                'input' => [
                    'swap_image' => "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $sourceImage))),
                    'target_image' => "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $targetImage))),
                ]
            ]);
            $data = $replicateService->getFaceSwap($body);
            Log::info("replicate response", ["response" => $data]);

            // Here you can decide whether to continue processing if one fails,
            // or maybe you want to collect the successful ones and report any failures separately.
            if ($data->status == 'starting') {
                $analysisResults[] = $data->urls;
            } else {
                // Handle the error accordingly, you might want to log the error or add it to an errors array.
                Log::error("Error processing target image {$targetImage}", ["error" => $data]);
                $analysisResults[] = [
                    'status' => 'failed',
                    'target_image' => $targetImage,
                    'error' => 'Failed to process image',
                ];
            }
        }

        return $analysisResults;
    }
    private function analyzePackBatch($sourceImage, $pack)
    {
        $replicateService = new ReplicateApi();
        $analysisResults = [];
        //TODO:remove take(2) when testing is over.
        foreach ($pack->photos->take(2)->pluck('url') as $targetImage) {
            $body = json_encode([
                'version' => env('MODEL_VERSION'),
                'input' => [
                    'swap_image' => "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $sourceImage))),
                    'target_image' => (app()->isLocal()) ? "data:image/png;base64," . base64_encode(file_get_contents($targetImage)) : $targetImage,
                ]
            ]);
            $data = $replicateService->getFaceSwap($body);
            Log::info("replicate response EE", ["response" => $data]);

            // Here you can decide whether to continue processing if one fails,
            // or maybe you want to collect the successful ones and report any failures separately.
            if ($data->status == 'starting') {
                $analysisResults[] = $data->urls;
            } else {
                // Handle the error accordingly, you might want to log the error or add it to an errors array.
                Log::error("Error processing target image {$targetImage}", ["error" => $data]);
                $analysisResults[] = [
                    'status' => 'failed',
                    'target_image' => $targetImage,
                    'error' => 'Failed to process image',
                ];
            }
        }
        return $analysisResults;
    }

    public function getResult(Request $request)
    {
        try {
            $url = $request->callback_url;
            $replicateService = new ReplicateApi();
            $data = $replicateService->getResults($url);
            $predictionId = Str::afterLast($url, '/');
            Storage::put('results/' . $predictionId . '_' . time() . '.json', json_encode(['result_data' => $data]));
            if ($data->status == "succeeded") {
                $data = $data->output;
                if ($data) {
                    $format = json_decode(json_encode(['status' => "success", 'data' => $data]));
                    return $format;
                } else {
                    $format = json_decode(json_encode(['status' => "failure", 'message' => "Could not generate proper respose. Please Try again."]));
                    return $format;
                }
            } else {
                $format = json_decode(json_encode(['status' => $data->status, 'data' => $data]));
                return $format;
            }
            // $request = $client->request('GET', $apiEndpoint, ["headers" => $headers]);
            // $data = (string)  $request->getBody();
        } catch (\Exception $e) {
            //$imageToImage->update(['step_status' => 3]);
            Log::error("generate image issue " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
            return response()->json(['status' => "failure", 'message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
        }
        return $data;
    }

    public function getBatchResult($taskId)
    {
        try {
            $task = FaceSwapTask::find($taskId);
            // Check if the task exists
            if (!$task) {
                return response()->json(['status' => "failure", 'message' => "Task not found"], 404);
            }
            // Check if the task has completed  `
            if ($task->status == 'completed') {
                return app_json(json_decode($task->results));
            }
            // Check if the task has results
            if ($task->results) {
                $replicateService = new ReplicateApi();
                $final_results = [];
                $final_status = 'success';
                // Loop through the results
                foreach (json_decode($task->results) as $result) {
                    $url = $result->get;
                    $data = $replicateService->getResults($url);
                    // Check if the task has completed
                    if ($data->status == "succeeded") {
                        $data = $data->output;
                        if ($data) {
                            $final_results[] = $this->storeLocaly($data);
                            //$final_results[] = $data;
                        } else {
                            $final_results[] = "";
                            //$final_status = "processing";
                        }
                    } elseif ($data->status == "failed") {
                        $final_status = "failure";
                    } else {
                        $final_status = "processing";
                        $final_results[] = ['status' => $data->status, 'data' => $data];
                    }
                }
                // Update the task status
                if ($final_status == "success") {
                    $task->results = $final_results;
                    $task->status = 'completed';
                    $task->save();
                } elseif ($final_status == "failure") {
                    $task->results = $final_results;
                    $task->status = 'failed';
                    $task->save();
                }

                return app_json($final_results, $final_status);
            }
            return response()->json(['status' => "failure", 'message' => "Task could not be found."], 404);
        } catch (Exception $e) {
            Log::error("generate image issue " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
            return response()->json(['status' => "failure", 'message' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }

    public function storeLocaly($imageUrl)
    {
        $dir = 'results'; // Define your storage path

        $imageData = file_get_contents($imageUrl);

        if ($imageData !== false || $imageData != "") {
            $fileName = time() . basename($imageUrl);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($imageData);
            //$image->place(public_path('img/watermark.png'), 'right-bottom', 300, 100, 50);
            //$image = $this->watermark($image);
            $image->toPng()->save(Storage::disk('public')->path($dir) . '/' . $fileName);

            // Optionally, you can also get the public URL of the stored image
            $publicUrl = asset("storage/$dir/$fileName");
            return $publicUrl;
        } else {
            return "";
        }
    }
}

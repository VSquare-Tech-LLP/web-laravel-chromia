<?php

namespace App\Http\Controllers;

use App\Models\FaceSwapTask;
use App\Models\Pack;
use App\Services\ReplicateApi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaceSwapController extends Controller
{
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
            return response()->json(['status' => "success", 'data' => $analysisResult]);
        } catch (Exception $e) {
            return response()->json(['status' => "false", 'data' => $e], 500);
        }
    }

    public function uploadImageBatch(Request $request)
    {
        try {
            // ... existing validation for the 'source'
            // Validate the 'targets' as an array of images
            $request->validate([
                'source' => 'required|image|mimes:jpeg,png,jpg,gif',
                'targets' => 'required|array|min:1|max:10',
                'targets.*' => 'image|mimes:jpeg,png,jpg,gif|max:50000',
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
                'status' => 'processing',
                'task_id' => $task->id,
                // You might want to send back a summary instead of all results, depending on the size
                'data' => $analysisResults,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // This will return the validation errors.
            return response()->json(['status' => "false", 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['status' => "false", 'data' => $e], 500);
        }
    }
    public function uploadImagePack(Request $request)
    {
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
            $selected_pack = Pack::findOrFail($request->pack_id)->with('photos')->first();
            // Upload images to storage
            $sourceImage = $request->file('source')->storeAs('images', $rq_time . '_ri1.jpg');
            $targetImages = $selected_pack->photos()->pluck('path');
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
                'status' => 'processing',
                'task_id' => $task->id,
                // You might want to send back a summary instead of all results, depending on the size
                'data' => $analysisResults,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // This will return the validation errors.
            return response()->json(['status' => "false", 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['status' => "false", 'data' => $e->getMessage()], 500);
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
    private function analyzePackBatch($sourceImage, Pack $pack)
    {
        $replicateService = new ReplicateApi();
        $analysisResults = [];

        foreach ($pack->photos()->pluck('url') as $targetImage) {
            $body = json_encode([
                'version' => env('MODEL_VERSION'),
                'input' => [
                    'swap_image' => "data:image/png;base64," . base64_encode(file_get_contents(storage_path('app/' . $sourceImage))),
                    'target_image' => $targetImage,
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

    public function getResult(Request $request)
    {
        try {
            $url = $request->callback_url;
            $replicateService = new ReplicateApi();
            $data = $replicateService->getResults($url);

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
                return response()->json(['status' => "error", 'message' => "Task not found"], 404);
            }
            // Check if the task has completed  `
            if ($task->status == 'completed') {
                return response()->json(['status' => "success", 'results' => json_decode($task->results)]);
            }
            // Check if the task has results
            if ($task->results) {
                $replicateService = new ReplicateApi();
                $final_results = [];
                // Loop through the results
                foreach (json_decode($task->results) as $result) {
                    $url = $result->get;
                    $data = $replicateService->getResults($url);
                    // Check if the task has completed
                    if ($data->status == "succeeded") {
                        $data = $data->output;
                        if ($data) {
                            $final_results[] = $data;
                        } else {
                            $final_results[] = "";
                        }
                    } else {
                        $final_results[] = ['status' => $data->status, 'results' => $data];
                    }
                }
                // Update the task status
                $task->results = $final_results;
                $task->status = 'completed';
                $task->save();
                return response()->json(['status' => "success", 'results' => $final_results]);
            }
            return response()->json(['status' => "failure", 'message' => "Task could not be found."], 404);
        } catch (Exception $e) {
            Log::error("generate image issue " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
            return response()->json(['status' => "failure", 'message' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
        }
    }
}

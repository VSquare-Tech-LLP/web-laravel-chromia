<?php

namespace App\Http\Controllers;

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

    // private function analyzeImagesFal($image1Path, $image2Path)
    // {
    //     $data = "";
    //     $apiEndpoint = 'https://fal.run/fal-ai/llavav15-13b';


    //     //         echo $res->getBody();

    //     $body = [
    //         'prompt' => 'be a giga chad and rate each of jawline, hair, skin, eyes, face, masculinity out of 10 go upto 1 decimal point. try to be more strict in rating. rate as if you are super ultra masculine person.',
    //         'image_url' => 'data:image/png;base64,' . base64_encode(file_get_contents(storage_path('app/' . $image1Path))),
    //     ];

    //     try {
    //         // $client = new Client();

    //         // $response = $client->post($apiEndpoint, [
    //         //     'json' => $body,
    //         //     // Add other request options if needed
    //         // ]);

    //         // $apiResponse = json_decode($response->getBody(), true);

    //         // // Process the API response as needed
    //         // return response()->json($apiResponse);

    //         $client = new Client();
    //         $headers = [
    //             'Authorization' => 'Key ' . env('FAL_KEY'),
    //             'Content-Type' => 'application/json'
    //         ];
    //         $format = ""; //"give it in this format ex.'Jawline: 8.5\n Hair: 8.0\n Skin: 8.5\n Eyes: 8.0\n Face: 8.5\n6. Masculinity: 8.5'.";
    //         $body = '{
    //                 "prompt": "be a giga chad and rate each of jawline, hair, skin, eyes, face, masculinity out of 10 go upto 1 decimal point. only ratings. try to be more strict in rating. rate as if you are super ultra masculine person.",
    //                 "image_url": "https://akm-img-a-in.tosshub.com/indiatoday/images/story/202305/recall-hrithik-roshan-inter-sixteen_nine.jpg?VersionId=9EUAjXH2OKvgjOvKEmy8_8k8aOV6oW1_&size=690:388"
    //         }';
    //         $request = $client->request('POST', $apiEndpoint, ["body" => $body, "headers" => $headers]);
    //         //$res = $client->sendAsync($request)->wait();
    //         $data = (string)  $request->getBody();
    //     } catch (\Exception $e) {
    //         // Handle API request failure
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    //     return  json_decode($data);
    // }

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

    public function formatJson($data)
    {
        $newdata = $data;
        try {
            $a = array_map(function ($key) {
                $key = $this->filterKey($key);
                return str_replace(' ', '', $key);
            }, array_keys($data));
            $b = array_map(function ($val) {
                if (is_array($val)) {
                    $c = array_map(function ($key) {
                        $key = $this->filterKey($key);
                        return str_replace(' ', '', $key);
                    }, array_keys($val));
                    $d = array_map(function ($s) {
                        return $s;
                    }, $val);
                    return array_combine($c, $d);
                } else {
                    return $val;
                }
            }, $data);

            $newdata = array_combine($a, $b);
            return $newdata;
        } catch (Exception $e) {
            Log::error("Formatting issue " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getFile(), 'input' => $data]);
            return false;
        }
    }

    public function filterKey($key)
    {

        if ($key == 'Rating' || $key == 'rating') {
            $key = "Ratings";
        }

        return ucwords($key);
    }
}

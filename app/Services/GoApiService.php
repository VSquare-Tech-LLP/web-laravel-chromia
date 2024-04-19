<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoApiService
{
  private $key;
  protected $request_url;
  protected $result_url;
  protected $http;
  protected $headers;

  public function __construct()
  {
    $this->key = env('GO_API_KEY', '');
    $this->request_url = "https://api.goapi.xyz/api/face_swap/v1/async";
    $this->result_url = "https://api.goapi.xyz/api/face_swap/v1/fetch";
    $this->http = new Client();
    $this->headers =  [
      'X-API-Key' => $this->key,
      'Content-Type' => 'application/json',
    ];
  }

  public function requestSwap($source_image, $target_image)
  {
    $body = [
      "target_image" => $target_image,
      "swap_image" => $source_image,
      "result_type" => "url"
    ];

    try {
      $result = $this->postRequest($this->request_url, $body, $this->headers);
      Log::info("GoApi API faceswap response:", json_decode(json_encode($result), true));
      return $result->data->task_id ?? "";
    } catch (Exception $e) {
      Log::error("GoApi API issue " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
      return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
    }
  }

  public function swapResult($task_uuid)
  {
    $body = [
      "task_id" => $task_uuid,
    ];
    try {
      $result = $this->postRequest($this->result_url, $body, $this->headers);
      Log::info("GoApi API faceswap response:", json_decode(json_encode($result), true));
      return $result->data->image ?? "";
    } catch (Exception $e) {
      Log::error("GoApi API issue " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
      return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
    }
  }

  public function postRequest($url, $body, $headers)
  {
    $request = $this->http->request('POST', $url, ["body" => json_encode($body), "headers" => $headers, "http_errors" => true]);
    $response = $request ? (string)  $request->getBody()->getContents() : null;
    $status = $request ? $request->getStatusCode() : 500;
    if ($response && $status === 200 && $response !== 'null') {
      return (object) json_decode($response);
    } else {
      return null;
    }
  }
}

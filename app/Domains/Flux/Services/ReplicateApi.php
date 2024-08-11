<?php

namespace App\Domains\Flux\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ReplicateApi
{
  protected $http;
  protected $headers;

  public function __construct()
  {
    $this->http = new Client();
    $this->headers =  [
      'Content-Type' => 'application/json',
      'Authorization' => "Token " . env('REPLICATE_API_KEY', "")
    ];
  }

  public function sendRequest($body){
    $url = 'https://api.replicate.com/v1/models/black-forest-labs/flux-schnell/predictions';
    try {
      $request = $this->http->request('POST', $url, ["body" => json_encode(['input' => $body]), "headers" => $this->headers, "http_errors" => true]);
      $response = $request ? (string)  $request->getBody()->getContents() : null;
      return (object) json_decode($response);
    } catch (Exception $e) {
      Log::error("Replicate API issue  " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
      return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
    }
  }

  public function getResults($id){
    $url = 'https://api.replicate.com/v1/predictions/'.$id;
    try {
      $request = $this->http->request('GET', $url, ["headers" => $this->headers, "http_errors" => true]);
      $response = $request ? (string)  $request->getBody()->getContents() : null;
      $status = $request ? $request->getStatusCode() : 500;
      if ($response && $status === 200 && $response !== 'null') {
        return (object) json_decode($response);
      }else{
        return null;
      }
    } catch (Exception $e) {
      Log::error("Replicate API issue " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
      return app_data(false,['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
    }
  }
  
}

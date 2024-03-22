<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ReplicateApi
{
  protected $url;
  protected $http;
  protected $headers;

  public function __construct()
  {
    $this->url = 'https://api.replicate.com/v1/predictions';
    $this->http = new Client();
    $this->headers =  [
      'Content-Type' => 'application/json',
      'Authorization' => "Token " . env('REPLICATE_API_KEY', "")
    ];
  }

  private function getResponse(string $uri = null)
  {
    $full_path = $this->url;
    $full_path .= $uri;

    try {
      $request = $this->http->request('GET', $full_path, ["headers" => $this->headers, "http_errors" => true]);
      $response = $request ? (string)  $request->getBody()->getContents() : null;
      $status = $request ? $request->getStatusCode() : 500;
      if ($response && $status === 200 && $response !== 'null') {
        return (object) json_decode($response);
      }else{
        return null;
      }
    } catch (Exception $e) {
      Log::error("Replicate API issue " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
      return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
    }
    

    
  }

  private function postResponse($uri = null, $body = '')
  {
    $full_path = $this->url;
    $full_path .= $uri;
    try {
      $request = $this->http->request('POST', $full_path, ["body" => $body, "headers" => $this->headers, "http_errors" => true]);
      $response = $request ? (string)  $request->getBody()->getContents() : null;
      return (object) json_decode($response);
    } catch (Exception $e) {
      Log::error("Replicate API issue  " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
      return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
    }
  }

  public function getRatings($body)
  {
    return $this->postResponse(null, $body);
  }

  public function getFaceSwap($body)
  {
    return $this->postResponse(null, $body);
  }

  public function getResults($url)
  {
    $this->url = $url;
    return $this->getResponse("");
  }

  
}

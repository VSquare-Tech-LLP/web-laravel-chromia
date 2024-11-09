<?php

namespace App\Domains\Flux\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoApi
{
  protected $http;
  protected $headers;

  public function __construct()
  {
    $this->http = new Client();
    $this->headers = [
      'X-API-Key' => env('GO_API_KEY', ""),
      'Content-Type' => 'application/json'
    ];
  }

  public function sendRequest($body)
  {
    $url = 'https://api.goapi.ai/api/v1/task';
    try {
      $request = $this->http->request('POST', $url, [
        "body" => json_encode([
          'model'=> 'Qubico/flux1-schnell',
          'task_type'=> "txt2img",
          'input' => $body
        ]),
        "headers" => $this->headers,
        "http_errors" => true
      ]);
      $response = $request ? (string) $request->getBody()->getContents() : null;
      return (object) json_decode($response);
    } catch (Exception $e) {
      Log::error("GoApi issue: " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
      return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
    }
  }

  public function getResults($id)
  {
    $url = 'https://api.goapi.ai/api/v1/task/' . $id;
    try {
      $request = $this->http->request('GET', $url, [
        "headers" => $this->headers,
        "http_errors" => true
      ]);
      $response = $request ? (string) $request->getBody()->getContents() : null;
      $status = $request ? $request->getStatusCode() : 500;
      if ($response && $status === 200 && $response !== 'null') {
        return (object) json_decode($response);
      } else {
        return null;
      }
    } catch (Exception $e) {
      Log::error("GoApi issue: " . $e->getMessage(), ['line' => $e->getLine(), 'trace' => $e->getTrace()]);
      return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'trace' => $e->getTrace()], 500);
    }
  }
  
}

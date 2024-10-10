<?php

namespace App\Domains\Flux\Services;

use Exception;
use Log;

class IpCheckerService
{
  private $ip;
  private $apiKey;
  public $isApple = false;
  public function __construct($ip)
  {
    $this->apiKey = env('IP_API_KEY', '');
    $this->ip = $ip;
    $info = $this->getInfo($ip);
    $this->isApple = $this->checkApple($info);
  }

  public function getInfo($ip)
  {
    //$ipCheckerUrl = "https://ipapi.co/{$ip}/json";
    if($this->apiKey==''){
      return "";
    }
    try {
      $ipCheckerUrl = "https://pro.ip-api.com/json/{$ip}?key={$this->apiKey}";
      $ipChecker = file_get_contents($ipCheckerUrl);
      $ipChecker = json_decode($ipChecker);
    } catch (Exception $e) {
      $this->apiKey = '';
      return "";
    }
    return $ipChecker;
  }

  public function checkApple($info)
  {
    if($this->apiKey==''){
      return false;
    }
    $infoString = json_encode($info);
    $infoString = strtolower($infoString);
    $isApple = strpos($infoString, 'apple');
    if($isApple !== false){
      Log::info('Apple Device ip Found.', ['ip' => $this->ip]);
    }else{
      Log::info('Non Apple Device ip Found.', ['ip' => $this->ip]);
    }
    return $isApple !== false;
  }
}

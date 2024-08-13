<?php

namespace App\Domains\Flux\Services;

use App\Domains\Flux\Models\Log;

class LogService
{

  public function __construct()
  {
  }

  public static function addLog($ip, $prompt, $settings, $result_id, $is_paid = false, $device_id = null)
  {
    $swapLog = Log::create([
      'ip_address' => $ip,
      'device_id' => $device_id,
      'prompt' => $prompt,
      'settings' => $settings,
      'result_id' => $result_id,
      'is_paid' => ($is_paid != false) ? true : false
    ]);
    return $swapLog;
  }

  public static function updateResultLog($uuid, $results)
  {
    $swapLog =  Log::where('result_id', $uuid)->first();
    $swapLog->update(['results' => $results]);
    return $swapLog;
  }

  public function findLog($uuid)
  {
    return Log::where('result_id', $uuid)->first() ?? null;
  }

  public function getLogs($ip = null, $device_id = null)
  {
    if ($ip) {
      return Log::where('ip_address', $ip)->get();
    } else if ($device_id) {
      return Log::where('device_id', $device_id)->get();
    } else {
      return Log::all();
    }
  }
}

<?php

namespace App\Domains\Flux\Services;

use App\Domains\Flux\Models\SwapLog;

class SwapLogService
{

  public function __construct()
  {
  }

  public static function addLog($ip, $source, $target, $result_id, $is_paid = false, $device_id = null)
  {
    $swapLog = SwapLog::create([
      'ip_address' => $ip,
      'device_id' => $device_id,
      'swap_source' => $source,
      'swap_target' => $target,
      'swap_result_id' => $result_id,
      'is_paid' => ($is_paid != false) ? true : false
    ]);
    return $swapLog;
  }

  public static function updateResultLog($uuid, $result)
  {
    $swapLog =  SwapLog::where('swap_result_id', $uuid)->first();
    $swapLog->update(['swap_result' => $result]);
    return $swapLog;
  }

  public function getLogs($ip = null, $device_id = null)
  {
    if ($ip) {
      return SwapLog::where('ip_address', $ip)->get();
    } else if ($device_id) {
      return SwapLog::where('device_id', $device_id)->get();
    } else {
      return SwapLog::all();
    }
  }
}

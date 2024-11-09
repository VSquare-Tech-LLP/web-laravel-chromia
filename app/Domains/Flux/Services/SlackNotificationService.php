<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SlackNotificationService
{
    protected $webhookUrl;

    public function __construct($webhookUrl=null)
    {
        if (!$webhookUrl) {
            $webhookUrl = config('services.slack.webhook_url');
        }
        $this->webhookUrl = $webhookUrl;
    }

    public function sendNotification($projectName, $type, $message)
    {
        if (!$this->webhookUrl) {
            return;
        }
        
        $payload = [
            'text' => "[$projectName] [$type]: $message",
        ];

        // Send the request to Slack using HTTP client
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post($this->webhookUrl, $payload);

        // Optionally handle response or errors
        // ...
    }
}

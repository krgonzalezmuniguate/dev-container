<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EmailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $payload;
    public function __construct(array $payload)
    {
        // Log::info('Evento EmailEvent disparado con el payload:', $payload);
        // dd('Evento EmailEvent disparado con el payload:', $payload);
        $this->payload = $payload;
    }
}

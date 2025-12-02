<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRequestSuppliesCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $description;

    public function __construct($user, ?string $description = null)
    {
        $this->user = $user;
        $this->description = $description;
    }
}

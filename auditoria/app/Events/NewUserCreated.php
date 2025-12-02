<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewUserCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $password;

    /**
     * @param \App\Models\User|object $user
     * @param string|null $password
     */
    public function __construct($user, ?string $password = null)
    {
        $this->user = $user;
        $this->password = $password;
    }
}

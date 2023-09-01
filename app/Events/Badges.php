<?php

namespace App\Events;

use App\Models\Badge;
use App\Models\LessonUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class Badges
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    public function badges(){
        $user_id = Auth::user()->id;

        $badges = LessonUser::where('user_id', $user_id)->count();

        if(count($badges) == 0){
            $badge_received = "Beginner";
        }elseif(count($badges) == 4){
            $badge_received = "Intermediate";
        }elseif(count($badges) == 8){
            $badge_received = "Advanced";
        }elseif(count($badges) == 10){
            $badge_received = "Master";
        }

        if($badge_received){
            $badge_store = new Badge();
            $badge_store->user_id = $user_id;
            $badge_store->badge_title = $badge_received;
            $badge_store->save();
        }
    }
}

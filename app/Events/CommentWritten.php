<?php

namespace App\Events;

use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Auth;

class CommentWritten
{
    use Dispatchable, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function commentWritten(){
        $user_id = Auth::user()->id;
        $total_comment = Comment::where('user_id', $user_id)->count();

        if(count($total_comment) == 1){
            $achievement_title = "First Lesson Watched";
        }elseif(count($total_comment) == 3){
            $achievement_title = "3 Comments Written";
        }elseif(count($total_comment) == 5){
            $achievement_title = "5 Comments Written";
        }elseif(count($total_comment) == 10){
            $achievement_title = "10 Comments Written";
        }elseif(count($total_comment) == 20){
            $achievement_title = "20 Comments Written";
        }

        if($achievement_title){
            $achievement_store = new Achievement();
            $achievement_store->user_id = $user_id;
            $achievement_store->achievement_title = $achievement_title;
            $achievement_store->achivement_type = 0;
            $achievement_store->save();
        }
    }

}

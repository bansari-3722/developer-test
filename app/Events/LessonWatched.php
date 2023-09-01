<?php

namespace App\Events;

use App\Models\Achievement;
use App\Models\User;
use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Auth;

class LessonWatched
{
    use Dispatchable, SerializesModels;

    public $lesson;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Lesson $lesson, User $user)
    {
        $this->lesson = $lesson;
        $this->user = $user;
    }

    public function lessonWatch(){
        $user_id = Auth::user()->id;
        $lesson_watched = LessonUser::where('user_id', $user_id)->count();

        if(count($lesson_watched) == 1){
            $lesson_achievement = "First Lesson Watched";
        }elseif(count($lesson_watched) == 5){
            $lesson_achievement = "5 Lessons Watched";
        }elseif(count($lesson_watched) == 10){
            $lesson_achievement = "10 Lessons Watched";
        }elseif(count($lesson_watched) == 25){
            $lesson_achievement = "25 Lessons Watched";
        }elseif(count($lesson_watched) == 50){
            $lesson_achievement = "50 Lessons Watched";
        }

        if($lesson_achievement){
            $achievement_store = new Achievement();
            $achievement_store->user_id = $user_id;
            $achievement_store->achievement_title = $lesson_achievement;
            $achievement_store->achivement_type = 1;
            $achievement_store->save();
        }
    }
}

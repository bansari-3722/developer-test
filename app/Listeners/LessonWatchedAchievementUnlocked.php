<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class LessonWatchedAchievementUnlocked
{
    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event): void
    {
        $userId = $event->user->id;
        $totalLessons = LessonUser::where('user_id', $userId)->count();
        $achievementUnlocked = 1;
        $achievement_title = '';
        switch ($totalLessons) {
            case 1:
                $achievement_title = "First Lesson Watched";
                break;
            case 5:
                $achievement_title = "5 Lessons Watched";
                break;
            case 10:
                $achievement_title = "10 Lessons Watched";
                break;
            case 25:
                $achievement_title = "25 Lessons Watched";
                break;
            case 50:
                $achievement_title = "50 Lessons Watched";
                break;
            default:
                $achievementUnlocked = 0;
                break;
        }

        if ($achievementUnlocked == 1) {
            event(new AchievementUnlocked(["achievement_name" => $achievement_title, "user" => $event->user]));
            Achievement::create([
                "user_id" => $userId,
                "achievement_title" => $achievement_title
            ]);
            $achievementCount = Achievement::where('user_id', $userId)->count();
            $badgeUnlocked = 1;
            switch ($achievementCount) {
                case 0:
                    $achievement_title = "Beginner";
                    break;
                case 4:
                    $achievement_title = "Intermediate";
                    break;
                case 8:
                    $achievement_title = "Advanced";
                    break;
                case 10:
                    $achievement_title = "Master";
                    break;
                default:
                    $badgeUnlocked = 0;
                    break;
            }
            if ($badgeUnlocked == 1) {
                event(new BadgeUnlocked(["achievement_name" => $achievement_title, "user" => $event->user]));
            }
        }
    }
}

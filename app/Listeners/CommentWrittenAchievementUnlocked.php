<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Models\Achievement;
use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class CommentWrittenAchievementUnlocked
{
    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $userId = $event->user->id;
        $totalComments = Comment::where('user_id', $userId)->count();
        $achievementUnlocked = 1;
        $achievement_title = '';
        switch ($totalComments) {
            case 1:
                $achievement_title = "First Lesson Watched";
                break;
            case 3:
                $achievement_title = "3 Comments Written";
                break;
            case 5:
                $achievement_title = "5 Comments Written";
                break;
            case 10:
                $achievement_title = "10 Comments Written";
                break;
            case 20:
                $achievement_title = "20 Comments Written";
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

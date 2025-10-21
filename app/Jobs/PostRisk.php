<?php

namespace App\Jobs;

use App\Mail\HighRiskPostAlert;
use App\Models\Post;
use App\Models\User;
use App\Services\RiskScoringService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PostRisk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $postId)
    {
    }

    public function handle(): void
    {
        $post = Post::find($this->postId);

        $score = 20;
        if(preg_match('/accident|fire|theft|damage/i', $post->content, $match)) {
            $score += 50;
        }
        if (strlen($post->content) < 50) {
            $score += 10;
        }

        $level = $score >= 70 ? 'high' : ($score >= 30 ? 'medium' : 'low');

        $post->update([
            'risk_score' => $score,
            'risk_level' => $level,
        ]);

        if ($level !== 'high') {
            return;
        }

        $adminEmails = User::where('role', 'ADMIN')
            ->pluck('email')
            ->values()
            ->all();

        Mail::to($adminEmails)->send(new HighRiskPostAlert($post));

    }
}

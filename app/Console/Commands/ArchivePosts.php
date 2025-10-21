<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ArchivePosts extends Command
{
    protected $signature = 'archive:posts
                            {--days=365 : Archive posts older than this many days}
                            {--dry-run : Show which posts would be archived without making changes}';

    protected $description = 'Archive old posts by setting archived_at timestamp';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $dryRun = (bool) $this->option('dry-run');

        $threshold = Carbon::now()->subDays($days);

        $query = Post::query()->whereNull('archived_at')->where('created_at', '<', $threshold);

        $count = $query->count();

        if ($count === 0) {
            $this->info('No posts match the criteria.');
            return 0;
        }

        $this->info("Found {$count} posts to archive (older than {$days} days).");

        if (! $this->confirm('Proceed to archive these posts?')) {
            $this->info('Aborted.');
            return 0;
        }

        $archived = 0;

        try {
            $query->orderBy('id')->chunkById(100, function ($posts) use (&$archived, $dryRun) {
                $ids = $posts->pluck('id')->all();

                if (empty($ids)) {
                    return;
                }

                if ($dryRun) {
                    $this->line('Would archive post ids: ' . implode(', ', $ids));
                    $archived += count($ids);
                    return;
                }

                Post::whereIn('id', $ids)->update(['archived_at' => Carbon::now()]);
                $archived += count($ids);
                $this->info("Archived " . count($ids) . " posts.");
            });
        } catch (\Throwable $e) {
            Log::error('archive:posts failed', ['error' => $e->getMessage()]);
            $this->error('Failed while archiving posts: ' . $e->getMessage());
            return 1;
        }

        $this->info("Done. Total processed: {$archived} posts.");
        return 0;
    }
}

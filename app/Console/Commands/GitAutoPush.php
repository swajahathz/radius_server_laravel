<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GitAutoPush extends Command
{
    protected $signature = 'git:push';
    protected $description = 'Auto push Laravel project to Git with today\'s date';

    public function handle()
    {
        $this->info('🛠 Starting Git auto push...');

        // Step 1: Git add
        exec('git add .', $outputAdd, $returnAdd);
        if ($returnAdd !== 0) {
            $this->error('❌ git add failed');
            return;
        }

        // Step 2: Commit with date
        $date = date('Y-m-d');
        exec("git commit -m \"Auto update: $date\"", $outputCommit, $returnCommit);
        if ($returnCommit !== 0) {
            $this->warn('⚠️ No changes to commit');
        } else {
            $this->info('✅ Changes committed');
        }

        // Step 3: Push to GitHub
        exec('git push origin main', $outputPush, $returnPush);
        if ($returnPush !== 0) {
            $this->error('❌ git push failed');
        } else {
            $this->info('🚀 Code pushed to GitHub');
        }
    }
}

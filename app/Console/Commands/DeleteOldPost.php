<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class DeleteOldPost extends Command
{
    protected $signature = 'post:delete-old';

    protected $description = 'Delete posts 15 days old';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Post::whereDate('created_at', '<=', now()->subDays(15))->delete();
        return 1;
    }
}

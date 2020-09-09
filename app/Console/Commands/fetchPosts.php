<?php

namespace App\Console\Commands;

use App\Models\ApiHistory;
use App\Models\Post;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class fetchPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to fetch new posts every 5 minutes!';

    private const FETCH_POSTS_ROUTE = 'http://jsonplaceholder.typicode.com/posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return int
     */
    public function handle()
    {
        $count = 0;
        $updateCount = 0;
        $guzzleClient = new Client();
        $fetchedPosts = json_decode($guzzleClient->get(self::FETCH_POSTS_ROUTE)->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $apiHistory = ApiHistory::query()->where('route', '=', 'posts')->firstOrNew();
        foreach($fetchedPosts as $fetchedPost) {
            if($fetchedPost['id'] <= $apiHistory->last_id) {
                $updated = $this->updateIfNeeded($fetchedPost);
                if($updated) {
                    $updateCount++;
                }
            } else {
                $newPost = new Post();
                $newPost->id = $fetchedPost['id'];
                $newPost->user_id = $fetchedPost['userId'];
                $newPost->title = $fetchedPost['title'];
                $newPost->body = $fetchedPost['body'];
                $newPost->save();
                $count++;
            }
        }

        $lastId = $fetchedPosts[count($fetchedPosts) - 1]['id'];
        $apiHistory->last_id = $lastId;
        $apiHistory->route = 'posts';
        $apiHistory->save();

        fwrite(STDOUT, "\nUpdated $updateCount posts \n");
        fwrite(STDOUT, "Added $count new posts \n");
        return 1;
    }

    /**
     * @param $fetchedPost
     *
     * @return bool
     */
    private function updateIfNeeded($fetchedPost): bool
    {
        $changes = false;
        $post = Post::query()->where('id', '=', $fetchedPost['id'])->first();

        if($post === null) {
            return false;
        }

        if($post->title !== $fetchedPost['title']) {
            $post->title = $fetchedPost['title'];
            $changes = true;
        }

        if($post->body !== $fetchedPost['body']) {
            $post->body = $fetchedPost['body'];
            $changes = true;
        }

        if($changes) {
            $post->save();
        }

        return $changes;
    }
}

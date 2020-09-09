<?php

namespace Database\Seeders;

use App\Models\ApiHistory;
use App\Models\Post;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    private const FETCH_POSTS_ROUTE = 'http://jsonplaceholder.typicode.com/posts';

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return void
     */
    public function run()
    {
        $guzzleClient = new Client();

        $fetchedPosts = json_decode($guzzleClient->get(self::FETCH_POSTS_ROUTE)->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        foreach($fetchedPosts as $fetchedPost) {
            $newPost = new Post();
            $newPost->id = $fetchedPost['id'];
            $newPost->user_id = $fetchedPost['userId'];
            $newPost->title = $fetchedPost['title'];
            $newPost->body = $fetchedPost['body'];
            $newPost->save();
        }

        $lastId = $fetchedPosts[count($fetchedPosts) - 1]['id'];

        $apiHistory = ApiHistory::query()->where('route', '=', 'posts')->firstOrNew();
        $apiHistory->last_id = $lastId;
        $apiHistory->route = 'posts';
        $apiHistory->save();
    }
}

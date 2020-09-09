<?php

namespace Database\Seeders;

use App\Models\ApiHistory;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    private const FETCH_USERS_ROUTE = 'http://jsonplaceholder.typicode.com/users';

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return void
     */
    public function run()
    {
        $guzzleClient = new Client();

        $fetchedUsers = json_decode($guzzleClient->get(self::FETCH_USERS_ROUTE)->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        foreach($fetchedUsers as $fetchedUser) {
            $newUser = new User();
            $newUser->id = $fetchedUser['id'];
            $newUser->username = $fetchedUser['username'];
            $newUser->email = $fetchedUser['email'];
            $newUser->name = $fetchedUser['name'];
            $newUser->save();
        }

        $lastId = $fetchedUsers[count($fetchedUsers) - 1]['id'];

        $apiHistory = ApiHistory::query()->where('route', '=', 'users')->firstOrNew();
        $apiHistory->last_id = $lastId;
        $apiHistory->route = 'users';
        $apiHistory->save();
    }
}

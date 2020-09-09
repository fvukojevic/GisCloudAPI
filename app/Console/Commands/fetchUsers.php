<?php

namespace App\Console\Commands;

use App\Models\ApiHistory;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class fetchUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to fetch new users every 5 minutes!';

    private const FETCH_USERS_ROUTE = 'http://jsonplaceholder.typicode.com/users';

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
        $fetchedUsers = json_decode($guzzleClient->get(self::FETCH_USERS_ROUTE)->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        $apiHistory = ApiHistory::query()->where('route', '=', 'users')->firstOrNew();
        foreach($fetchedUsers as $fetchedUser) {
            if($fetchedUser['id'] <= $apiHistory->last_id) {
                $updated = $this->updateIfNeeded($fetchedUser);
                if($updated) {
                    $updateCount++;
                }
            } else {
                $newUser = new User();
                $newUser->id = $fetchedUser['id'];
                $newUser->username = $fetchedUser['username'];
                $newUser->email = $fetchedUser['email'];
                $newUser->name = $fetchedUser['name'];
                $newUser->save();
                $count++;
            }
        }

        $lastId = $fetchedUsers[count($fetchedUsers) - 1]['id'];
        $apiHistory->last_id = $lastId;
        $apiHistory->route = 'users';
        $apiHistory->save();

        fwrite(STDOUT, "\nUpdated $updateCount users \n");
        fwrite(STDOUT, "Added $count new users \n");
        return 1;
    }

    /**
     * @param $fetchedUser
     *
     * @return bool
     */
    private function updateIfNeeded($fetchedUser): bool
    {
        $changes = false;
        $user = User::query()->where('id', '=', $fetchedUser['id'])->first();

        if($user === null) {
            return false;
        }

        if($user->name !== $fetchedUser['name']) {
            $user->name = $fetchedUser['name'];
            $changes = true;
        }

        if($user->email !== $fetchedUser['email']) {
            $user->email = $fetchedUser['email'];
            $changes = true;
        }

        if($user->username !== $fetchedUser['username']) {
            $user->username = $fetchedUser['username'];
            $changes = true;
        }

        if($changes) {
            $user->save();
        }

        return $changes;
    }
}

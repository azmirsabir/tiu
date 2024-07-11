<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user_id=DB::table('users')->insertGetId([
           'user_name'=>'azmir',
           'password'=>Hash::make('password'),
           'status'=>"1",
           'type'=>'admin'
       ]);

        DB::table('cards')->insert([
            [
            'title'=>'reviewing an article for a journal',
            'user_type'=>'normal'
            ],
            [
                'title'=>'Supervising graduation projects',
                'user_type'=>'normal'
            ],
        ]);

        DB::table('card_questions')->insert([
            [
                'card_id'=>1,
                'name'=>'National Journal',
                'point'=>0
            ],
            [
                'card_id'=>1,
                'name'=>'Another Journal',
                'point'=>0
            ],
            [
                'card_id'=>2,
                'name'=>'Test Journal',
                'point'=>0
            ],
            [
                'card_id'=>2,
                'name'=>'Test2 Journal',
                'point'=>0
            ],
        ]
        );
    }
}

<?php

use App\Image;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UserTableSeeder extends Seeder {

    public function run() {

        try {

            $seedImages = true;
            $faker = Faker::create();
            $images = $seedImages ? $this->getUserImages() : [];

            //5 testing users
            $usersData = [
                [
                  
                    'name' => 'Admin',
                    'email' => 'admin@blog.com',
                    'password' => bcrypt('secret'),
                    
                    'role' => 'admin',
                    'language' => 'en'
                ],
                [
                  
                    'name' => 'Moderator',
                    'email' => 'moderator@blog.com',
                    'password' => bcrypt('secret'),
                   
                    'role' => 'moderator',
                    'language' => 'en'
                ],
                [
                  
                    'name' => 'User',
                    'email' => 'user@blog.com',
                    'password' => bcrypt('secret'),
                    
                    'role' => 'user',
                    'language' => 'en'
                ]
            ];
            for($i=0;$i<=50;$i++){
                $usersData[]=                [
                  
                    'name' => 'User'.$i,
                    'email' => 'user'.$i.'@blog.com',
                    'password' => bcrypt('secret'),
                    
                    'role' => 'user',
                    'language' => 'en'
                ];
            }

            foreach ($usersData as $userData) {
                $user = factory(User::class)->create(array_except($userData, 'role'));
                if ($seedImages) {
                    $image = Image::create($images->random());
                    $user->setAvatar($image);
                    $user->save();
                }
                $user->assignRole($userData['role']);
            }

            

           
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function getUserImages() {
        $client = new Client();
        $res = $client->request('GET', 'https://pixabay.com/api/?key=5403129-77e250972243adb6f9e49690d&q=human+face&image_type=photo&pretty=true&min_width=800&min-height=600&per_page=100');
        $images = json_decode($res->getBody()->getContents());
        return collect($images->hits)->pluck('webformatURL');
    }

}

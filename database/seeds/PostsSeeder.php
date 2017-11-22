<?php

use App\Post;
use App\Comment;
use App\Image;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $seedImages = true;


        $images = $seedImages ? $this->getImages() : [];


        factory(Post::class, 30)
                ->create()
                ->each(function($post) use ($images, $seedImages) {
                    $moderator = $post->user;
                    if ($seedImages) {
                        if (rand(0, 1)) {
                            $image = Image::create($images->random());
                            $post->setImage($image)->save();
                        }
                    }

                    


                    $users = User::inRandomOrder()->take(30)->get();

                    //likes
                    $usersThatLikedThePost = $users->random(rand(5, 30));
                    $post->likes()->attach($usersThatLikedThePost->pluck('id'));
                    $post->no_of_likes = $usersThatLikedThePost->count();

                    //comments
                    $usersThatCommentedThePost = $users->random(rand(5, 30));
                    $comments = factory(Comment::class, rand(5, 40))
                            ->make([
                                'user_id' => $usersThatCommentedThePost->random()->id
                            ])
                            ->each(function($comment) use ($post, $users, $images, $seedImages) {
                        $post->comments()->save($comment);

                        if ($seedImages) {
                            if (rand(0, 5) > 3) {
                                $image = Image::create($images->random());
                                $comment->setImage($image)->save();
                            }
                        }

                        if (rand(0, 1)) {
                            $usersThatLikedTheComment = $users->random(rand(5, 30));
                            $comment->likes()->attach($usersThatLikedTheComment->pluck('id'));
                            $comment->no_of_likes = $usersThatLikedTheComment->count();
                            $comment->save();
                        }

                        //add comment to comment
                    });

                    $post->no_of_comments = $comments->count();
                    $post->save();
                });
    }

    private function getImages() {
        $client = new Client();
        $res = $client->request('GET', 'https://pixabay.com/api/?key=5403129-77e250972243adb6f9e49690d&q=nature&image_type=photo&pretty=true&min_width=800&min-height=600&per_page=100');
        $images = json_decode($res->getBody()->getContents());
        return collect($images->hits)->pluck('webformatURL');
    }

}

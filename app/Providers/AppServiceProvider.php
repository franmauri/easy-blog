<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Post;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Schema::defaultStringLength(151);

        Relation::morphMap([
            
            'post' => Post::class,
//            'comment' => Comment::class,
//            'image' => Image::class,
            
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}

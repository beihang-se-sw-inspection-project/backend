<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


use \App\Models\User;

use \App\Models\Post;

use \App\Models\Comment;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function(User $author){
            $posts = Post::factory()->count(2)->create(
                ['user_id' => $author->id]
            );
            foreach($posts as $post){
                
                for($i=0; $i<5; $i++){
                    $comment = Comment::factory()->count(1)->create(
                        [
                            'user_id' => User::all()[rand(0,4)]->id,
                            'post_id' => $post->id
                        ]
                    );  
                    
                    $comments[] = $comment;   
                }   
            }
         });
    }
}

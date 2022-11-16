<?php


namespace Tests\Feature;

require __DIR__."/../../vendor/autoload.php";

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;


class PostsTest extends TestCase
{
    // use DatabaseMigrations;

    const BASE_URL = "http://localhost/buganda-clans-app-api/public/api/v1";
    /**
     * @test
     */
    public function it_returns_an_post_as_a_resource_object()
    {

        // $user = User::factory()->create();
        // $post = Post::factory()->create(['user_id' => $user->id]);        
        // Passport::actingAs($user);

        $res = $this->getJson(self::BASE_URL."/posts", [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => '1',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $post->title,
                        'description' => $post->description,
                        
                        'created_at' => $post->created_at->toJSON(),
                        'updated_at' => $post->updated_at->toJSON(),
                    ]
                ]
            ]);

    }

    /**
     * @test
     */
    public function it_returns_all_posts_as_a_collection_of_resource_objects()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $posts = factory(Post::class, 3)->create();

        $this->get('/api/v1/posts', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '1',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[0]->title,
                        'description' => $posts[0]->description,
                        
                        'created_at' => $posts[0]->created_at->toJSON(),
                        'updated_at' => $posts[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[1]->title,
                        'description' => $posts[1]->description,
                        
                        'created_at' => $posts[1]->created_at->toJSON(),
                        'updated_at' => $posts[1]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[2]->title,
                        'description' => $posts[2]->description,                        
                        'created_at' => $posts[2]->created_at->toJSON(),
                        'updated_at' => $posts[2]->updated_at->toJSON(),
                    ]
                ],
            ]
        ]);
        
    }

    /**
     * @test
     */
    public function it_can_create_an_post_from_a_resource_object()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',
                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(201)
            ->assertJson([
                "data" => [
                    "id" => '1',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Building an API with Laravel',
                        'description' => 'A post about API development',                        
                        'created_at' => now()->setMilliseconds(0)->toJSON(),
                        'updated_at' => now() ->setMilliseconds(0)->toJSON(),
                    ]
                ]
            ])->assertHeader('Location', url('/api/v1/posts/1'));

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => 'Building an API with Laravel',
            'description' => 'A post about API development',
            
        ]);
    }

    /**
     * @test
     */
    public function it_validates_that_the_type_member_is_given_when_creating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => '',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.type field is required.',
                        'source'  => [
                            'pointer' => '/data/type',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('posts', [
            'id' => 1,
            'title' => 'Building an API with Laravel',
            'description' => 'A post about API development',
            
        ]);
    }

    /**
     * @test
     */
    public function it_validates_that_the_type_member_has_the_value_of_posts_when_creating_an_post()
    {

        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'booo',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The selected data.type is invalid.',
                        'source'  => [
                            'pointer' => '/data/type',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseMissing('posts', [
            'id' => 1,
            'title' => 'Building an API with Laravel',
            'description' => 'A post about API development',            
        ]);
    }

    /**
     * @test
     */
    public function it_validates_that_the_attributes_member_has_been_given_when_creating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'posts',
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes field is required.',
                        'source'  => [
                            'pointer' => '/data/attributes',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_validates_that_the_attributes_member_is_an_object_given_when_creating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => 'this is not an object'
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes must be an array.',
                        'source'  => [
                            'pointer' => '/data/attributes',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_validates_that_a_title_attribute_is_given_when_creating_an_post()
    {

        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'description' => 'A post about API development',                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes.title field is required.',
                        'source'  => [
                            'pointer' => '/data/attributes/title',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_validates_that_a_title_attribute_is_a_string_when_creating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'title' => 42,
                    'description' => 'A post about API development',                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes.title must be a string.',
                        'source'  => [
                            'pointer' => '/data/attributes/title',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_validates_that_a_description_attribute_is_given_when_creating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'title' => 'Building an API with Laravel',                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes.description field is required.',
                        'source'  => [
                            'pointer' => '/data/attributes/description',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_validates_that_a_description_attribute_is_a_string_when_creating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 42,                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes.description must be a string.',
                        'source'  => [
                            'pointer' => '/data/attributes/description',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_validates_that_a_publication_year_attribute_is_given_when_creating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes.publication year field is required.',
                        'source'  => [
                            'pointer' => '/data/attributes/publication_year',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_validates_that_a_publication_year_attribute_is_a_string_when_creating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/posts', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',
                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes.publication year must be a string.',
                        'source'  => [
                            'pointer' => '/data/attributes/publication_year',
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function it_can_update_an_post_from_a_resource_object()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'id' => '1',
                'type' => 'posts',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',
                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => '1',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Building an API with Laravel',
                        'description' => 'A post about API development',
                        
                        'created_at' => now()->setMilliseconds(0)->toJSON(),
                        'updated_at' => now() ->setMilliseconds(0)->toJSON(),
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => 'Building an API with Laravel',
            'description' => 'A post about API development',
            
        ]);
    }

    /**
     * @test
     */
    public function it_validates_that_an_id_member_is_given_when_updating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'type' => 'posts',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',
                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.id field is required.',
                        'source'  => [
                            'pointer' => '/data/id',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => $post->title,
        ]);
    }

    /**
     * @test
     */
    public function it_validates_that_an_id_member_is_a_string_when_updating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'id' => 1,
                'type' => 'posts',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',
                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.id must be a string.',
                        'source'  => [
                            'pointer' => '/data/id',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => $post->title,
        ]);
    }

    /**
     * @test
     * 
     */
    public function it_validates_that_the_type_member_is_given_when_updating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'id' => '1',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',
                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.type field is required.',
                        'source'  => [
                            'pointer' => '/data/type',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => $post->title,
        ]);
    }

    /**
     * @test
     * 
     */
    public function it_validates_that_the_type_member_has_the_value_of_posts_when_updating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'id' => '1',
                'type' => 'booo',
                'attributes' => [
                    'title' => 'Building an API with Laravel',
                    'description' => 'A post about API development',
                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The selected data.type is invalid.',
                        'source'  => [
                            'pointer' => '/data/type',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => $post->title,
        ]);
    }

    /**
     * @test
     * 
     */
    public function it_validates_that_the_attributes_member_has_been_given_when_updating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'id' => '1',
                'type' => 'posts',
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes field is required.',
                        'source'  => [
                            'pointer' => '/data/attributes',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => $post->title,
        ]);
    }

    /**
     * @test
     * 
     */
    public function it_validates_that_the_attributes_member_is_an_object_given_when_updating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'id' => '1',
                'type' => 'posts',
                'attributes' => 'this is not an object'
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes must be an array.',
                        'source'  => [
                            'pointer' => '/data/attributes',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => $post->title,
        ]);
    }

    /**
     * @test
     * 
     */
    public function it_validates_that_a_title_attribute_is_a_string_when_updating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'id' => '1',
                'type' => 'posts',
                'attributes' => [
                    'title' => 42,
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes.title must be a string.',
                        'source'  => [
                            'pointer' => '/data/attributes/title',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => $post->title,
        ]);
    }

    /**
     * @test
     * 
     */
    public function it_validates_that_a_description_attribute_is_a_string_when_updating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'id' => '1',
                'type' => 'posts',
                'attributes' => [
                    'description' => 42,
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes.description must be a string.',
                        'source'  => [
                            'pointer' => '/data/attributes/description',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => $post->title,
        ]);
    }

    /**
     * @test
     * 
     */
    public function it_validates_that_a_publication_year_attribute_is_a_string_when_updating_an_post()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->patchJson('/api/v1/posts/1', [
            'data' => [
                'id' => '1',
                'type' => 'posts',
                'attributes' => [
                    
                ]
            ]
        ], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Validation Error',
                        'details' => 'The data.attributes.publication year must be a string.',
                        'source'  => [
                            'pointer' => '/data/attributes/publication_year',
                        ]
                    ]
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => 1,
            'title' => $post->title,
        ]);
    }

    /**
     * @test
     * 
     */
    public function it_can_delete_an_post_through_a_delete_request()
    {
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user);
        $post = factory(Post::class)->create();

        $this->delete('/api/v1/posts/1',[], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(204);

        $this->assertDatabaseMissing('posts', [
           'id' => 1,
           'title' => $post->title,
        ]);
    }

    /**
     * @test
     */
    public function it_can_sort_posts_by_title_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $posts = collect([
            'Building an API with Laravel',
            'Classes are our blueprints',
            'Adhering to the JSON:API Specification',
        ])->map(function($title){
            return factory(Post::class)->create([
                'title' => $title
            ]);
        });

        $this->get('/api/v1/posts?sort=title', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '3',
                    "type" => "posts",
                    "attributes" => [
                        'title' =>  'Adhering to the JSON:API Specification',
                        'description' => $posts[2]->description,
                        
                        'created_at' => $posts[2]->created_at->toJSON(),
                        'updated_at' => $posts[2]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '1',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Building an API with Laravel',
                        'description' => $posts[0]->description,
                        
                        'created_at' => $posts[0]->created_at->toJSON(),
                        'updated_at' => $posts[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Classes are our blueprints',
                        'description' => $posts[1]->description,
                        
                        'created_at' => $posts[1]->created_at->toJSON(),
                        'updated_at' => $posts[1]->updated_at->toJSON(),
                    ]
                ],
            ]
        ]);
        
    }

    /**
     * @test
     */
    public function it_can_sort_posts_by_title_in_descending_order_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $posts = collect([
            'Building an API with Laravel',
            'Classes are our blueprints',
            'Adhering to the JSON:API Specification',
        ])->map(function($title){
            return factory(Post::class)->create([
                'title' => $title
            ]);
        });

        $this->get('/api/v1/posts?sort=-title', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '2',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Classes are our blueprints',
                        'description' => $posts[1]->description,
                        
                        'created_at' => $posts[1]->created_at->toJSON(),
                        'updated_at' => $posts[1]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '1',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Building an API with Laravel',
                        'description' => $posts[0]->description,
                        
                        'created_at' => $posts[0]->created_at->toJSON(),
                        'updated_at' => $posts[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "posts",
                    "attributes" => [
                        'title' =>  'Adhering to the JSON:API Specification',
                        'description' => $posts[2]->description,
                        
                        'created_at' => $posts[2]->created_at->toJSON(),
                        'updated_at' => $posts[2]->updated_at->toJSON(),
                    ]
                ],
            ]
        ]);
        
    }

    /**
     * @test
     */
    public function it_can_sort_posts_by_multiple_attributes_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $posts = collect([
            'Building an API with Laravel',
            'Classes are our blueprints',
            'Adhering to the JSON:API Specification',
        ])->map(function($title){

            if($title === 'Building an API with Laravel'){
                return factory(Post::class)->create([
                    'title' => $title,
                    
                ]);
            }

            return factory(Post::class)->create([
                'title' => $title,
                
            ]);

        });

        $this->get('/api/v1/posts?sort=publication_year,title', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '3',
                    "type" => "posts",
                    "attributes" => [
                        'title' =>  'Adhering to the JSON:API Specification',
                        'description' => $posts[2]->description,
                        
                        'created_at' => $posts[2]->created_at->toJSON(),
                        'updated_at' => $posts[2]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Classes are our blueprints',
                        'description' => $posts[1]->description,
                        
                        'created_at' => $posts[1]->created_at->toJSON(),
                        'updated_at' => $posts[1]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '1',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Building an API with Laravel',
                        'description' => $posts[0]->description,
                        
                        'created_at' => $posts[0]->created_at->toJSON(),
                        'updated_at' => $posts[0]->updated_at->toJSON(),
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_can_sort_posts_by_multiple_attributes_in_descending_order_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $posts = collect([
            'Building an API with Laravel',
            'Classes are our blueprints',
            'Adhering to the JSON:API Specification',
        ])->map(function($title){

            if($title === 'Building an API with Laravel'){
                return factory(Post::class)->create([
                    'title' => $title,
                    
                ]);
            }

            return factory(Post::class)->create([
                'title' => $title,
                
            ]);

        });

        $this->get('/api/v1/posts?sort=-publication_year,title', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '1',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Building an API with Laravel',
                        'description' => $posts[0]->description,
                        
                        'created_at' => $posts[0]->created_at->toJSON(),
                        'updated_at' => $posts[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "posts",
                    "attributes" => [
                        'title' =>  'Adhering to the JSON:API Specification',
                        'description' => $posts[2]->description,
                        
                        'created_at' => $posts[2]->created_at->toJSON(),
                        'updated_at' => $posts[2]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "posts",
                    "attributes" => [
                        'title' => 'Classes are our blueprints',
                        'description' => $posts[1]->description,
                        
                        'created_at' => $posts[1]->created_at->toJSON(),
                        'updated_at' => $posts[1]->updated_at->toJSON(),
                    ]
                ],
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_can_paginate_posts_through_a_page_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $posts = factory(Post::class, 10)->create();

        $this->get('/api/v1/posts?page[size]=5&page[number]=1', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '1',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[0]->title,
                        'description' => $posts[0]->description,
                        
                        'created_at' => $posts[0]->created_at->toJSON(),
                        'updated_at' => $posts[0]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[1]->title,
                        'description' => $posts[1]->description,
                        
                        'created_at' => $posts[1]->created_at->toJSON(),
                        'updated_at' => $posts[1]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[2]->title,
                        'description' => $posts[2]->description,
                        
                        'created_at' => $posts[2]->created_at->toJSON(),
                        'updated_at' => $posts[2]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '4',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[3]->title,
                        'description' => $posts[3]->description,
                        
                        'created_at' => $posts[3]->created_at->toJSON(),
                        'updated_at' => $posts[3]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '5',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[4]->title,
                        'description' => $posts[4]->description,
                        
                        'created_at' => $posts[4]->created_at->toJSON(),
                        'updated_at' => $posts[4]->updated_at->toJSON(),
                    ]
                ],
            ],
            'links' => [
                'first' => route('posts.index', ['page[size]' => 5, 'page[number]' => 1]),
                'last' => route('posts.index', ['page[size]' => 5, 'page[number]' => 2]),
                'prev' => null,
                'next' => route('posts.index', ['page[size]' => 5, 'page[number]' => 2]),
            ]
        ]);
    }

    /**
     * @test
     */
    public function it_can_paginate_posts_through_a_page_query_parameter_and_show_different_pages()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $posts = factory(Post::class, 10)->create();

        $this->get('/api/v1/posts?page[size]=5&page[number]=2', [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '6',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[5]->title,
                        'description' => $posts[5]->description,
                        
                        'created_at' => $posts[5]->created_at->toJSON(),
                        'updated_at' => $posts[5]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '7',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[6]->title,
                        'description' => $posts[6]->description,
                        
                        'created_at' => $posts[6]->created_at->toJSON(),
                        'updated_at' => $posts[6]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '8',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[7]->title,
                        'description' => $posts[7]->description,
                        
                        'created_at' => $posts[7]->created_at->toJSON(),
                        'updated_at' => $posts[7]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '9',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[8]->title,
                        'description' => $posts[8]->description,
                        
                        'created_at' => $posts[8]->created_at->toJSON(),
                        'updated_at' => $posts[8]->updated_at->toJSON(),
                    ]
                ],
                [
                    "id" => '10',
                    "type" => "posts",
                    "attributes" => [
                        'title' => $posts[9]->title,
                        'description' => $posts[9]->description,
                        
                        'created_at' => $posts[9]->created_at->toJSON(),
                        'updated_at' => $posts[9]->updated_at->toJSON(),
                    ]
                ],
            ],
            'links' => [
                'first' => route('posts.index', ['page[size]' => 5, 'page[number]' => 1]),
                'last' => route('posts.index', ['page[size]' => 5, 'page[number]' => 2]),
                'prev' => route('posts.index', ['page[size]' => 5, 'page[number]' => 1]),
                'next' => null,
            ]
        ]);
    }

}

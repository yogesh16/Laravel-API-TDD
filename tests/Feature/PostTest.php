<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * List post.
     *
     * @return void
     */
    public function test_get_post()
    {
        $this->generatePosts();

        $this->json('GET', 'api/posts', [], ['Accept' => 'application/json'])
             ->assertStatus(200);
    }

    /**
     * User can create new post
     */
    public function test_post_can_be_created()
    {
        $user = User::factory()->create();
        $post = [
            'title' => 'Post title',
            'description' => 'Description of post',
            'image' => UploadedFile::fake()->create('test.png')
        ];

        $this->actingAs($user)
             ->json('POST', 'api/posts', $post, ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJson([
                 'status' => true,
                 'message' => 'Post created successfully'
             ])
             ->assertJsonStructure([
                 "status",
                 "message",
                 "post" => [
                     "id",
                     "title",
                     "image",
                     "description",
                     "date",
                     "author",
                     "total_likes",
                     "likes"
                 ]
             ]);
    }

    /**
     * test without user login, not allowed to create new post
     */
    public function test_post_cannot_create_without_login()
    {
        $post = [
            'title' => 'Post title',
            'description' => 'Description of post',
            'image' => UploadedFile::fake()->create('test.png')
        ];

        $this->json('POST', 'api/posts', $post, ['Accept' => 'application/json'])
             ->assertStatus(401)
             ->assertJson([
                'message' => 'Unauthenticated.'
             ]);
    }

    /**
     * Can delete users own post
     */
    public function test_post_can_be_deleted()
    {
        $this->generatePosts();
        $post = Post::first();
        $user = User::first();

        $this->actingAs($user)
             ->json('DELETE', 'api/posts/'.$post->id, [], ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJson([
                'status' => true,
                'message' => 'Your post deleted successfully'
             ]);
    }

    /**
     * It should not allow to delete post of other users
     */
    public function test_post_cannot_delete_of_other_user()
    {
        $this->generatePosts();
        $post = Post::first();
        $user = User::factory()->create();

        $this->actingAs($user)
             ->json('DELETE', 'api/posts/'.$post->id, [], ['Accept' => 'application/json'])
             ->assertStatus(403)
             ->assertJson([
                 'message' => 'Unauthorized action'
             ]);
    }

    /**
     * Test user can like a post
     */
    public function test_user_can_like_post()
    {
        $this->generatePosts();
        $post = Post::first();

        $this->actingAs(User::first())
             ->json('POST', 'api/posts/' . $post->id . '/like', [], ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJson([
                'status' => true
             ]);
    }

    /**
     * Test user can unlike a post
     */
    public function test_user_can_unlike_post()
    {
        $this->generatePosts();
        $post = Post::first();

        $this->actingAs(User::first())
             ->json('POST', 'api/posts/' . $post->id . '/unlike', [], ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJson([
                 'status' => true
             ]);
    }

    /**
     * Test user can see likes of any post
     */
    public function test_user_can_see_post_likes()
    {
        $this->generatePosts();
        $post = Post::first();

        $this->actingAs(User::first())
             ->json('POST', 'api/posts/' . $post->id . '/likes', [], ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJson([
                 'status' => true
             ]);
    }


    private function generatePosts()
    {
        User::factory()->create();
        Post::factory()->create();
    }
}

<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class Acara24Test extends TestCase
{
    use RefreshDatabase;

    public function test_gate_allows_author_and_denies_others()
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::create(['user_id' => $author->id, 'title' => 'Test Post', 'content' => 'Content']);

        $this->assertTrue(Gate::forUser($author)->allows('edit-post', $post));
        $this->assertTrue(Gate::forUser($otherUser)->denies('edit-post', $post));
    }

    public function test_update_with_gate_endpoint_authorizes_correctly()
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::create(['user_id' => $author->id, 'title' => 'Original Title', 'content' => 'Content']);

        // Unauthorized user attempts update via Gate -> HTTP 403
        $response = $this->actingAs($otherUser)->put(route('acara24.update-gate', $post->id), [
            'title' => 'Unauthorized Change',
        ]);
        $response->assertStatus(403);

        // Authorized user attempts update via Gate -> Success
        $response = $this->actingAs($author)->put(route('acara24.update-gate', $post->id), [
            'title' => 'Authorized Change via Gate',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Authorized Change via Gate']);
    }

    public function test_update_with_policy_endpoint_authorizes_correctly()
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::create(['user_id' => $author->id, 'title' => 'Original Title', 'content' => 'Content']);

        // Unauthorized user attempts update via Policy -> HTTP 403
        $response = $this->actingAs($otherUser)->put(route('acara24.update-policy', $post->id), [
            'title' => 'Unauthorized Change Policy',
        ]);
        $response->assertStatus(403);

        // Authorized user attempts update via Policy -> Success
        $response = $this->actingAs($author)->put(route('acara24.update-policy', $post->id), [
            'title' => 'Authorized Change via Policy',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Authorized Change via Policy']);
    }

    public function test_middleware_can_protects_route()
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::create(['user_id' => $author->id, 'title' => 'Title', 'content' => 'Content']);

        // Non-owner blocked by middleware can:update,post -> HTTP 403
        $response = $this->actingAs($otherUser)->get(route('acara24.edit-middleware', $post->id));
        $response->assertStatus(403);

        // Owner allowed by middleware can:update,post -> HTTP 200
        $response = $this->actingAs($author)->get(route('acara24.edit-middleware', $post->id));
        $response->assertStatus(200);
    }
}

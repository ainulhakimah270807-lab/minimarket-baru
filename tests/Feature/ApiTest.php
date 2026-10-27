<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_book_via_api()
    {
        $response = $this->postJson('/api/books', [
            'title' => 'Konsep Sistem Basis Data',
            'description' => 'Buku ini terdiri dari 17 kegiatan pembelajaran',
            'status' => 1,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('title', 'Konsep Sistem Basis Data');

        $this->assertDatabaseHas('books', [
            'title' => 'Konsep Sistem Basis Data',
        ]);
    }

    public function test_can_get_all_books()
    {
        Book::create([
            'title' => 'Buku Test 1',
            'description' => 'Deskripsi 1',
            'status' => 1,
        ]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function test_can_show_single_book()
    {
        $book = Book::create([
            'title' => 'Buku Detail',
            'description' => 'Deskripsi Detail',
            'status' => 1,
        ]);

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('title', 'Buku Detail');
    }

    public function test_can_update_book()
    {
        $book = Book::create([
            'title' => 'Buku Lama',
            'description' => 'Deskripsi Lama',
            'status' => 1,
        ]);

        $response = $this->putJson("/api/books/{$book->id}", [
            'status' => 0,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'status' => 0,
        ]);
    }

    public function test_can_delete_book()
    {
        $book = Book::create([
            'title' => 'Buku Hapus',
            'description' => 'Deskripsi Hapus',
            'status' => 1,
        ]);

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Book deleted']);

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }

    public function test_user_login_and_access_protected_route()
    {
        $user = User::factory()->create([
            'email' => 'david_juli@polije.ac.id',
            'password' => 'Polije1234',
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'david_juli@polije.ac.id',
            'password' => 'Polije1234',
        ]);

        $loginResponse->assertStatus(200)
                      ->assertJsonStructure(['access_token', 'token_type']);

        $token = $loginResponse->json('access_token');

        $userResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                             ->getJson('/api/user');

        $userResponse->assertStatus(200)
                     ->assertJsonPath('email', 'david_juli@polije.ac.id');

        $logoutResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                               ->postJson('/api/logout');

        $logoutResponse->assertStatus(200)
                       ->assertJson(['message' => 'Logged out']);
    }
}

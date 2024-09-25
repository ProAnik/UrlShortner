<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Url;

class UrlTest extends TestCase
{
    use RefreshDatabase;


    /**
     * A basic feature test example.
     */
    public function test_valid_url_generation()
    {
        $response = $this->postJson('api/generate', ['original_url' => 'https://www.example.com']);
        $response->assertStatus(201);
        $this->assertDatabaseHas('urls', ['original_url' => 'https://www.example.com']);
    }

    public function test_invalid_url()
    {
        $response = $this->postJson('api/generate', ['original_url' => 'invalid-url']);
        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid URL']);
    }

    /** @test */
    public function it_returns_original_url_when_short_url_exists()
    {
        // Create a sample short URL record in the database
        $url = Url::create([
            'original_url' => 'https://www.example.com',
            'short_url' => Url::generateShortUrl()
        ]);

        // Send a request to the retrieve method with the valid short URL
        $response = $this->getJson('api/retrieve/' . $url->short_url);

        // Assert the response returns 201 with the correct original URL
        $response->assertStatus(201);
        $response->assertJson([
            'original_url' => 'https://www.example.com'
        ]);
    }

    /** @test */
    public function it_returns_404_when_short_url_does_not_exist()
    {
        // Send a request with an invalid short URL
        $response = $this->getJson('api/retrieve/nonexistent');

        // Assert the response returns 404 with an error message
        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'URL not found'
        ]);
    }



}

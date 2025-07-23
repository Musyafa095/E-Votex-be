<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_update_profile_with_image()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        // Mock the Cloudinary upload
        Cloudinary::shouldReceive('upload')
            ->once()
            ->andReturn((object)['getSecurePath' => 'https://res.cloudinary.com/demo/image/upload/sample.jpg']);

        // Create a fake image
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        // Send a request to update the profile
        $response = $this->postJson('/api/profile', [
            'bio' => 'This is a test bio',
            'age' => 25,
            'image' => $file,
        ]);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Profil berhasil diperbarui',
            ]);

        // Assert the profile was updated in the database
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'bio' => 'This is a test bio',
            'age' => 25,
            'image' => 'https://res.cloudinary.com/demo/image/upload/sample.jpg',
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Tinify\Tinify;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        try {
            // Generate a unique image name
            $imageName = 'profile_' . uniqid() . '_' . time() . '.jpg';

            // Get a random image from Lorem Picsum
            $imageUrl = "https://picsum.photos/70/70";

            // Optimize and resize with TinyPNG
            $source = \Tinify\fromUrl($imageUrl);
            $resized = $source->resize([
                "method" => "cover",
                "width" => 70,
                "height" => 70
            ]);

            // Store the optimized image
            Storage::disk('public')->put(
                'images/' . $imageName,
                $resized->toBuffer()
            );

            $imagePath = 'storage/images/' . $imageName;
            

        } catch (\Exception $e) {
            // If an exception occurs, use a default image path
            $imagePath = 'storage/images/default.jpg';
        }

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'profile_image' => $imagePath,
        ];
    }
}

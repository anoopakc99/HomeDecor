<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('home_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        $defaults = [
            ['key' => 'hero_heading', 'value' => 'CHAIR'],
            ['key' => 'hero_subheading', 'value' => 'LIMITED EDITION'],
            ['key' => 'banner_image', 'value' => null],
            ['key' => 'banner_title', 'value' => 'Shop our new collection of wooden tables'],
            ['key' => 'banner_description', 'value' => 'Designed for modern living spaces to gather around.'],
            ['key' => 'banner_button_link', 'value' => '/products'],
            ['key' => 'story_image_1', 'value' => null],
            ['key' => 'story_image_2', 'value' => null],
            ['key' => 'story_image_3', 'value' => null],
            ['key' => 'story_image_4', 'value' => null],
            ['key' => 'vocal_tagline', 'value' => 'Supporting Indian Craftsmanship & Artisans'],
        ];

        foreach ($defaults as $item) {
            DB::table('home_settings')->insert(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('home_settings');
    }
};

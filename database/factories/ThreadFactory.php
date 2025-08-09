<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $authorId = rand(3, 9);
        $author = User::find($authorId);
        $isAdmin = $author && $author->isAdmin();

        return [
            'title'         => $this->faker->text(30),
            'body'          => $this->faker->paragraph(2, true),
            'slug'          => $this->faker->unique()->slug,
            'author_id'     => $authorId,
            'category_id'   => rand(1, 7),
            'status'        => $isAdmin ? 'approved' : 'pending',
            'approved_at'   => $isAdmin ? now() : null,
            'approved_by'   => $isAdmin ? $authorId : null,
        ];
    }
}

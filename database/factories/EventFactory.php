<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function configure()
    {
        return $this->afterCreating(function (Event $event) {
            $photos = \File::allFiles(__DIR__ . '/photos');

            foreach (range(1, 2) as $i) {
                $filePath = Arr::random($photos);

                $fileName = rand() . '.jpg';

                Storage::disk('public')->put($fileName, file_get_contents($filePath));

                $event->{"speaker_{$i}_photo"} = $fileName;
                $event->save();
            }
        });
    }

    public function definition()
    {
        return [
            'held_at' => $this->faker->dateTimeBetween('-3 days', '3 days'),
            'youtube_url' => $this->faker->url,

            'speaker_1_name' => $this->faker->name,
            'speaker_1_link' => 'https://twitter.com/twitter',

            'speaker_1_title' => $this->faker->word,
            'speaker_1_talk_title' => $this->faker->words(5, true),
            'speaker_1_talk_abstract' => $this->faker->text,
            'speaker_1_photo' => $this->faker->imageUrl(),

            'speaker_2_name' => $this->faker->name,
            'speaker_2_link' => 'https://twitter.com/twitter',
            'speaker_2_title' => $this->faker->word,
            'speaker_2_talk_title' => $this->faker->words(5, true),
            'speaker_2_talk_abstract' => $this->faker->text,
            'speaker_2_photo' => $this->faker->imageUrl(),
        ];
    }

    public function past()
    {
        return $this->state(fn ($faker) => ['held_at' => $this->faker->dateTimeBetween('-3 months', '-1 day')]);
    }

    public function upcoming()
    {
        return $this->state(fn ($faker) => ['held_at' => $this->faker->dateTimeBetween('+5 days', '+10 days')]);
    }
}

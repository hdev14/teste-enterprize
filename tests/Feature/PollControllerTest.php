<?php

namespace Tests\Feature;

use App\Models\Option;
use App\Models\Poll;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PollControllerTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function shouldCreateAPoll()
    {
        $data = [
            'poll_description' => 'test description',
            'options' => [
                'options 1',
                'options 2',
                'options 3'
            ]
        ];

        $response = $this->postJson('/api/poll', $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('polls', [ 'id' => $response['poll_id']]);
        $this->assertDatabaseCount('options', 3);
    }

    /** @test */
    public function shouldReturn422IfPollDescriptionIsInvalid()
    {
        $dataWithoutPollDescription = [
            'options' => [
                'options 1',
                'options 2',
                'options 3'
            ]
        ];

        $this->postJson('/api/poll', $dataWithoutPollDescription)
            ->assertStatus(422);

        $dataWithInvalidPollDescription = [
            'poll_description' => 123,
            'options' => [
                'options 1',
                'options 2',
                'options 3'
            ]
        ];

        $response = $this->postJson('/api/poll', $dataWithInvalidPollDescription)
            ->assertStatus(422);
    }

    /** @test */
    public function shouldReturn422IfOptionsAreInvalid()
    {
        $dataWithInvalidOptions= [
            'poll_description' => 'test description',
            'options' => []
        ];

        $this->postJson('/api/poll', $dataWithInvalidOptions)
            ->assertStatus(422);
    }

    /** @test */
    public function shouldReturnAPoll()
    {
        $poll = Poll::factory(['poll_description' => 'test description'])
            ->has(Option::factory()->count(3))
            ->create();

        $response = $this->getJson("api/poll/$poll->id");
        $response->assertOk();
        $response->assertJson([
            'poll_id' => $poll->id,
            'poll_description' => $poll->poll_description
        ]);

        $this->assertCount(3, $response['options']);
    }

}

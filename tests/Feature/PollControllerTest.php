<?php

namespace Tests\Feature;

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

        $response = $this->postJson('/api/poll', $data)->assertStatus(201);
        $this->assertDatabaseHas('polls', [ 'id' => $response['poll_id']]);
        $this->assertDatabaseCount('options', 3);
    }


}

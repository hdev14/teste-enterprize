<?php

namespace Tests\Feature;

use App\Models\Option;
use App\Models\Poll;
use App\Models\Vote;
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

     /** @test */
    public function shouldIncreaseViewsWhenRequestAPoll()
    {
        $poll = Poll::factory(['poll_description' => 'test description'])
            ->has(Option::factory()->count(3))
            ->create();

        $currentViews = $poll->views;
        $response = $this->getJson("api/poll/$poll->id");
        $response->assertOk();
        $response->assertJson([
            'poll_id' => $poll->id,
            'poll_description' => $poll->poll_description
        ]);

        $poll->refresh();
        $this->assertGreaterThan($currentViews, $poll->views);
        $this->assertCount(3, $response['options']);
    }

     /** @test */
    public function shouldReturn404IfPollDoesntExists()
    {
        $fakePollId = 10;

        $response = $this->getJson("api/poll/$fakePollId");
        $response->assertNotFound();
    }

    /** @test */
    public function shouldRegisterAVoteToOption()
    {
        $poll = Poll::factory(['poll_description' => 'test description'])
            ->has(Option::factory()->count(1))
            ->create();
        $data = [ 'option_id' => $poll->options[0]->id];

        $response = $this->postJson("api/poll/$poll->id/vote", $data);
        $response->assertStatus(204);

        $this->assertDatabaseHas('votes', ['option_id' => $data['option_id']]);

        $response = $this->postJson("api/poll/$poll->id/vote", $data);
        $response->assertStatus(204);
    }

    /** @test */
    public function OnRegisterVoteShouldReturn404ifPollDoesntExists()
    {
        $fakePollId = 10;
        $response = $this->postJson("api/poll/$fakePollId/vote", []);
        $response->assertNotFound();
    }

    /** @test */
    public function OnRegisterVoteShouldReturn404ifOptionDoesntExists()
    {
        $poll = Poll::factory(['poll_description' => 'test description'])->create();
        $dataWithFakeOptionId = [ 'option_id' => 10];
        $response = $this->postJson("api/poll/$poll->id/vote", $dataWithFakeOptionId);
        $response->assertNotFound();
    }

    /** @test */
    public function shouldReturnStats()
    {
        $poll = Poll::factory(['poll_description' => 'test description'])
            ->hasOptions(1)
            ->create();
        Vote::factory(['option_id' =>$poll->options[0]->id])->createOne();

        $response = $this->getJson("api/poll/$poll->id/stats");

        $response->assertOk();
        $response->assertJson(['views' => $poll->views]);
        $this->assertCount(1, $response['votes']);
    }

    /** @test */
    public function OnStatsShouldReturn404IfPollDoesntExists()
    {
        $fakePollId = 10;
        $this->getJson("api/poll/$fakePollId/stats")->assertNotFound(404);
    }

}

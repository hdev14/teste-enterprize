<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{

    public function index()
    {
        $polls = Poll::all();
        return response($polls, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'poll_description' => 'required|string',
            'options' => 'required'
        ]);

        $poll = Poll::create([
            'poll_description' => $data['poll_description']
        ]);

        $poll->options()->createMany(array_map(function ($op) {
            return ['option_description' => $op];
        }, $data['options']));

        return response([ 'poll_id' => $poll->id], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $poll = Poll::findOrFail($id);
        $poll->views++;
        $poll->save();

        return response([
            'poll_id' => $poll->id,
            'poll_description' => $poll->poll_description,
            'options' => $poll->options
        ], 200);
    }

    public function registerVote(Request $request, int $id)
    {
        $poll = Poll::findOrFail($id);
        $option_id = $request->get('option_id');

        $option = $poll->options()->where('id', $option_id)->firstOrFail();
        $vote = $option->vote()->where('option_id', $option->id)->first();

        if (!$vote) {
            $option->vote()->create(['qty' => 1]);
        } else {
            $vote->qty++;
            $vote->update();
        }

        return response([], 204);
    }

    public function stats(int $id)
    {
        $poll = Poll::findOrFail($id);
        $options = $poll->options;
        $votes = [];

        foreach ($options as $op) {
            $vote = $op->vote()->first();
            if ($vote) {
                array_push($votes, [
                    'option_id' => $vote->option_id,
                    'qty' => $vote->qty
                ]);
            }
        }

        return response([
            'views' => $poll->views,
            'votes' => $votes
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

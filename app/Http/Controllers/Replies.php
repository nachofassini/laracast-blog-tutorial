<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyRequest;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class Replies extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channelSlug, Thread $thread)
    {
        $replies = $thread->replies()->paginate(20);

        return response()->json($replies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ReplyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReplyRequest $request, $channelSlug, Thread $thread)
    {
        $reply = $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return response()->json($reply->load('owner'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate($request, [
            'body' => 'required|min:5'
        ]);

        $reply->update($request->only('body'));

        return $reply;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $returnPath = $reply->thread->path();

        $reply->delete();

        if (request()->wantsJson()) {
            return response()->json(['status' => 'Reply deleted']);
        }

        return redirect($returnPath)
            ->withFlash('The reply has been deleted!');
    }
}

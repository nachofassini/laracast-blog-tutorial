@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <a class="btn btn-default pull-right" href="{{ route('threads.create') }}">Create new thread</a>
        <div class="clearfix"></div>
        <br>

        @forelse($threads as $thread)
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="level">
                <h4 class="flex">
                  <a href="{{ $thread->path() }}">
                    @if($thread->hasUpdatesFor(auth()->user()))
                      <strong>{{ $thread->title }}</strong>
                    @else
                      {{ $thread->title }}
                    @endif
                  </a>
                </h4>
                <a href="{{ $thread->path() }}">
                  {{ $thread->replies_count }} {{ str_plural('Reply', $thread->replies_count) }}
                </a>
              </div>
            </div>

            <div class="panel-body">{{ $thread->body }}</div>
          </div>
        @empty
          <div class="alert alert-info">
              There are no relevant results at this time
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection

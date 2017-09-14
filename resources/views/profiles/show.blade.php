@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="page-header">
        <h1>
          {{ $profileUser->name }}
          <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
        </h1>
      </div>

      @foreach($threads as $thread)
        <div class="panel panel-default">
          <div class="panel-heading level">
            <span class="flex">{{ $thread->title }}</span>
            <span>posted {{ $thread->created_at->diffForHumans() }}</span>
          </div>
          <div class="panel-body">{{ $thread->body }}</div>
        </div>
      @endforeach
      {{ $threads->links() }}
    </div>
  </div>
@endsection

@extends('layouts.app')

@section('content')
  <thread-view :data="{{ $thread }}" inline-template>
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          @if($errors->count())
            <div class="alert alert-danger">
              <ul>
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="level">
                <div class="flex">
                  <a href="{{ route('profiles.show', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                  {{ $thread->title }}
                </div>
                @can('update', $thread)
                  <form action="{{ route('threads.destroy', $thread) }}" method="POST">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                    <button class="btn btn-link"><i class="fa fa-trash-o"></i></button>
                  </form>
                @endcan
              </div>
            </div>
            <div class="panel-body">{{ $thread->body }}</div>
          </div>

          <replies :thread="{{ $thread }}"
                   @added="repliesCount++"
                   @removed="repliesCount--">
          </replies>
        </div>

        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-body">
              <p>
                This thread was created {{ $thread->created_at->diffForHumans() }} by
                <a href="{{ route('threads.index') }}?by={{ $thread->creator->name }}">{{ $thread->creator->name }}</a>
                and currently it has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
              </p>

              <subscriptions :endpoint="'/threads/' + this.thread.id + '/subscribe'"
                            :is-subscribed="thread.isSubscribed"
                            @subscribed="subscribe"
                            @unsubscribed="unsubscribe"
              ></subscriptions>
            </div>
          </div>
        </div>
      </div>
    </div>
  </thread-view>
@endsection

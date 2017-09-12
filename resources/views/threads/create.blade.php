@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a new thread</div>

                    <div class="panel-body">
                        <form action="{{ route('threads.store') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="channel_id">Channel</label>
                                <select class="form-control" name="channel_id" id="channel_id" required>
                                    <option>Choose one</option>
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ $channel->id == old('channel_id') ? 'selected' : '' }}>
                                            {{ $channel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input class="form-control" name="title" id="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="body">Body</label>
                                <textarea class="form-control" name="body" id="body" rows="5" required>{{ old('body') }}</textarea>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary">Submit</button>
                                <a class="btn btn-default" href="{{ route('threads.index') }}">Cancel</a>
                            </div>

                            @if(count($errors))
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

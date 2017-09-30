@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <profile-image :user="{{ $profileUser }}" inline-template>
          <div>
            <div class="page-header level">
              <h1 class="flex">
                {{ $profileUser->name }}
              </h1>
              <img class="img-responsive" :src="avatar" alt="" width="25">
            </div>
            @can('update', $profileUser)
              <div class="pull-right">
                <image-upload @updated="updateAvatar" name="avatar" id="avatar"></image-upload>
              </div>
            @endcan
          </div>
        </profile-image>

        @forelse($activities as $date => $activity)
          <h3 class="page-header">{{ $date }}</h3>

          @foreach($activity as $record)
            @if(view()->exists("profiles.activities.{$record->type}"))
              @include ("profiles.activities.{$record->type}", ['activity' => $record])
            @endif
          @endforeach
        @empty
          <p>The user has no recent activity.</p>
        @endforelse
      </div>
    </div>
  </div>
@endsection

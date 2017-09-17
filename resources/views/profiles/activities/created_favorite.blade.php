@component('profiles.activities.activity')
  @slot('heading')
    <a href="{{ $activity->subject->favorited->path() }}">{{ $profileUser->name }} has favorite a reply</a>
  @endslot

  @slot('body')
    {{ $activity->subject->favorited->body }}
  @endslot
@endcomponent

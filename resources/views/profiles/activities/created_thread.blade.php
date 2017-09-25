@component('profiles.activities.activity')
  @slot('heading')
    {{ $profileUser->name }} published
    <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
  @endslot

  @slot('body')
    {!! str_limit($activity->subject->body, 500) !!}
  @endslot
@endcomponent

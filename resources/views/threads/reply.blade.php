<div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading level">
        <div class="flex">
            <a href="{{ route('profiles.show', $reply->owner) }}">{{ $reply->owner->name }}</a>
            said {{ $reply->created_at->diffForHumans() }}...
        </div>
        @can('update', $reply)
            <form action="{{ route('replies.destroy', $reply) }}" method="POST">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <button class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
            </form>
        @endcan
        <form action="{{ route('reply.favorite', $reply->id) }}" method="POST">
            {{ csrf_field() }}
            <button class="btn btn-link"
                    title="{{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}"
                    {{ $reply->isFavorited() ? 'disabled' : '' }}>
                @if($reply->isFavorited())
                    <i class="fa fa-star"></i>
                @else
                    <i class="fa fa-star-o"></i>
                @endif
                <span class="badge">{{ $reply->favorites_count }}</span>
            </button>
        </form>
    </div>
    <div class="panel-body">{{ $reply->body }}</div>
</div>

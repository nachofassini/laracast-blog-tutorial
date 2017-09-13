<div class="panel panel-default">
    <div class="panel-heading level">
        <div class="flex">
            <a href="#">{{ $reply->owner->name }}</a>
            said {{ $reply->created_at->diffForHumans() }}...
        </div>
        <form action="{{ route('reply.favorite', $reply->id) }}" method="POST">
            {{ csrf_field() }}
            <button class="btn btn-link"
                    title="{{ $reply->favorites()->count() }} {{ str_plural('Favorite', $reply->favorites()->count()) }}"
                    {{ $reply->isFavorited() ? 'disabled' : '' }}>
                @if($reply->isFavorited())
                    <i class="fa fa-star"></i>
                @else
                    <i class="fa fa-star-o"></i>
                @endif
                <span class="badge">{{ $reply->favorites()->count() }}</span>
            </button>
        </form>
    </div>
    <div class="panel-body">{{ $reply->body }}</div>
</div>

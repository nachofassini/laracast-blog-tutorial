<reply :attributes="{{ $reply }}" inline-template v-cloak>
  <div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading level">
      <div class="flex">
        <a href="{{ route('profiles.show', $reply->owner) }}">{{ $reply->owner->name }}</a>
        said {{ $reply->created_at->diffForHumans() }}...
      </div>

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

    <div class="panel-body">
      <div v-if="editing">
        <div class="form-group">
          <textarea name="body" id="body" v-model="form.body" class="form-control" rows="5"></textarea>
        </div>

        <button class="btn btn-xs btn-primary" @click="update">Update</button>
        <button class="btn btn-xs btn-link" @click="clean">Cancel</button>
      </div>
      <div v-else v-text="form.body"></div>
    </div>

    @can('update', $reply)
      <div class="panel-footer">
        <form action="{{ route('replies.destroy', $reply) }}" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}
          <button type="button" class="btn btn-primary btn-xs mr-1" @click="edit">Edit</button>
          <button class="btn btn-danger btn-xs">Delete</button>
        </form>
      </div>
    @endcan
  </div>
</reply>

<reply :attributes="{{ $reply }}" inline-template v-cloak>
  <div id="reply-{{ $reply->id }}" class="panel panel-default">
    <div class="panel-heading level">
      <div class="flex">
        <a href="{{ route('profiles.show', $reply->owner) }}">{{ $reply->owner->name }}</a>
        said {{ $reply->created_at->diffForHumans() }}...
      </div>

      <favorite :reply="{{ $reply }}"></favorite>
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
        <button type="button" class="btn btn-primary btn-xs" @click="edit">Edit</button>
        <button type="button" class="btn btn-danger btn-xs" @click="destroy">Delete</button>
      </div>
    @endcan
  </div>
</reply>

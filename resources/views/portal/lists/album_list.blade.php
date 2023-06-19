<div class="row">
  @foreach($albums as $album)
  <div class="col-xl-3 col-md-4 col-sm-6 col-xs-12">
    <div class="property-main">
      <div class="property-wrap">
        <div class="property-item">
          <figure class="item-thumb">
            <a class="hover-effect" href="/gallery/album/{{ $album->id }}">
              @if($album->featured_image)
              <img src="{{ asset('storage/'. $album->featured_image) }}" alt="" width="340" height="210">
              @else
              <img src="{{ asset('storage/img/notfound.png') }}" alt="" width="340" height="210">
              @endif
            </a>
            @if(Auth::user())
            @if(Auth::user()->user_group == 'Editor')
            <ul class="actions">
              <li>
                <button type="button" class="add-fav" id="editAlbumBtn" data-id="{{ $album->id }}"
                  data-activity="{{ $album->activity_type }}" data-description="{{ $album->description }}"
                  data-album="{{ $album->name }}"><i class="icon-pencil"></i></button>
              </li>
              <li>
                <span class="add-fav" style="background-color: #d9534f;" id="deleteAlbumBtn" data-id="{{ $album->id }}"
                  data-album="{{ $album->name }}"><i class="icon-trash"></i></span>
              </li>
            </ul>
            @endif
            @endif
          </figure>
          <div class="item-body">
            <h3 class="property-title"><a href="/gallery/album/{{ $album->id }}" class="text-decoration-none">{{ $album->name }}</a></h3>
          </div>
        </div>
      </div>
      <div class="item-foot date hide-on-list">
        <div class="pull-left">
          <p class="prop-user-agent"><i class="icon-user"></i><a href="/gallery/album/{{ $album->id }}" class="text-decoration-none">{{
              str_limit($album->created_by, 14) }}</a>
          </p>
        </div>
        <div class="pull-right">
          <p class="prop-date">
            <i class="icon-calendar"></i>{{ date('m-d-Y', strtotime($album->created_at)) }}
          </p>
        </div>
      </div>
    </div>
  </div>

  @endforeach

  <div class="col-md-12" id="album_pagination">
    <div class="pagination-bar center">{{ $albums->links()}}</div>
  </div>
</div>
<div class="col-11 mx-auto mt-3" style="background-color: #F5F5F5">
    <div class="container text-center">
        <h3 class="section-title center">Company Events</h3>
    </div>
    <div class="album-container mx-auto p-3">
        @foreach($albums->take(5) as $album)
            @php
            $img = $album->featured_image ? $album->featured_image : 'img/notfound.png';
            @endphp
            <a href="/gallery/album/{{ $album->id }}" class="album-card">
                <img src="{{ asset('storage/'.$img) }}" />
                <div class="p-3 w-100 text-light" style="position: absolute; bottom: 0; left: 0; background-color:rgba(0, 0, 0, .65)">
                    <h6>
                        {{ $album->name }}
                    </h6>
                </div>
            </a>
        @endforeach
        <a href="/gallery" class="album-card">
            <img src="{{ asset('storage/img/featured/1.jpg') }}" />
            <div class="p-3 w-100 h-100 text-light" style="position: absolute; bottom: 0; left: 0; background-color:rgba(0, 0, 0, .3); display: flex; justify-content: center; align-items: center;">
                <h6>
                    View More
                </h6>
            </div>
        </a>
    </div>
</div>

<style>
    .album-container {
        display: flex;
        align-content: center;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        vertical-align: middle;
        flex: 1;
    }

    .album-card{
        width: 300px !important;

    }

    .album-container .album-card {
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        flex: 1;
        height: 400px;
        margin: 0 5px;
        border-radius: 20px;
        box-shadow: 0px 10px 15px -3px rgba(0, 0, 0, 0.1);
        cursor: pointer;

        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        transition: 0.3s all ease;
    }

    .album-container .album-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .album-container .album-card:hover {
        flex: 2;
    }
</style>
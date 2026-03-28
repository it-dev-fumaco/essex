<div class="col-11 mx-auto mt-3" style="background-color: #F5F5F5">
    <div class="container text-center">
        <h3 class="section-title center">Company Events</h3>
    </div>
    <div class="album-container mx-auto p-3">
        @php
        $featuredBg = 'img/featured/1.jpg';
        $featuredBgPath = ltrim((string) $featuredBg, '/');
        $featuredBgUrl = null;

        try {
            if (\Illuminate\Support\Facades\Storage::disk('upcloud')->exists($featuredBgPath)) {
                $featuredBgUrl = \Illuminate\Support\Facades\Storage::disk('upcloud')->url($featuredBgPath);
            }
        } catch (\Throwable $e) {
            // ignore and fall back below
        }

        $featuredBgUrl = $featuredBgUrl ?: asset('storage/'.$featuredBg);
        @endphp
        @foreach($albums->take(5) as $album)
            @php
            $img = $album->featured_image ? $album->featured_image : 'img/notfound.png';
            $imgPath = ltrim((string) $img, '/');
            if (\Illuminate\Support\Str::startsWith($imgPath, 'storage/')) {
                $imgPath = \Illuminate\Support\Str::after($imgPath, 'storage/');
            }
            // Album images are stored under `uploads/...` in UpCloud.
            // If it's not an uploads path (e.g., local placeholder), fall back to local `asset('storage/...')`.
            if (\Illuminate\Support\Str::startsWith($imgPath, 'uploads/')) {
                $imgUrl = \Illuminate\Support\Facades\Storage::disk('upcloud')->url($imgPath);
            } else {
                $imgUrl = asset('storage/'.$img);
            }
            @endphp
            <a href="/gallery/album/{{ $album->id }}" class="album-card" style="position: relative !important">
                <img src="{{ $imgUrl }}" />
                <div class="p-3 w-100 text-light" style="position: absolute; bottom: 0; left: 0; background-color:rgba(0, 0, 0, .65)">
                    <h6 class="responsive-font">
                        {{ $album->name }}
                    </h6>
                </div>
            </a>
        @endforeach
        <a href="/gallery" class="album-card">
            <div class="p-3 w-100 h-100 text-light d-flex justify-content-center align-items-center" style="
            background-color:rgba(0, 0, 0, .3);
            background: url('{{ $featuredBgUrl }}') no-repeat;
            background-size: cover;
            ">
                <h6 class="responsive-font">
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
        text-decoration: none !important
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
<div class="container-fluid p-2">
    @foreach ($articles as $category => $article)
        <div class="row mt-3">
            <div class="col-12">
                <h6>{{ $category }}</h6>
            </div>
            @foreach ($article as $a)
                <div class="col-3 p-2">
                    <div class="card h-100 shadow">
                        <div class="card-body pt-2 pb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $a->title }}">
                            <div class="row h-100" style="display: flex; justify-content: center; align-items: center;">
                                <div class="col-2" style="display: flex; justify-content: center; align-items: center;">
                                    <i class="fa fa-file-text" style="font-size: 25pt;"></i>
                                </div>
                                <div class="col-10">
                                    <a class="text-decoration-none" href="/article/{{ $a->slug }}">
                                        <h6 style="line-height: 14pt !important">{{ \Illuminate\Support\Str::limit($a->title, 45, $end='...') }}</h6>
                                        <span class="text-muted" style="font-size: 9pt;">{{ \Illuminate\Support\Str::limit($a->title, 40, $end='...') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
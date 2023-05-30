@php
    $chunk_count = count($articles) / 2;
    $chunk_count = round($chunk_count) < 1 ? count($articles) : round($chunk_count);
    $chunk = $articles->chunk($chunk_count);
@endphp
<div class="col-md-10">
    @forelse ($chunk as $articles)
        <div class="col-md-6">
            @foreach ($articles as $category => $article)
            <div style="padding: 15px; border-radius: 15px; margin: 10px 15px 10px 15px; border: 1px solid rgba(175, 175, 175, .4)">
                <span style="font-size: 14pt;"><i class="fa fa-folder"></i>&nbsp;{{ $category }} ({{ count($article) }})</span>
                <div class="container-fluid">
                    <br>
                    @foreach ($article as $a)
                        <div class="row" style="padding: 10px 0 10px 0;">
                            <div class="col-md-1" style="display: flex; justify-content: center; align-items: center; min-height: 45px;">
                                <i class="fa fa-file-text" style="font-size: 25pt;"></i>
                            </div>
                            <div class="col-md-11" style="min-height: 45px; display: flex; justify-content: center; align-items: center; ">
                                <div class="col-md-12" style="text-align: left !important">
                                    <a href="/article/{{ $a->slug }}">
                                        <h5 style="line-height: 14pt !important">{{ $a->title }}</h5>
                                        <span class="text-muted" style="font-size: 9pt;">{{ substr($a->short_text, 0, 45) }}...</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr style="border: 1px solid rgba(175, 175, 175, .4); margin: 10px !important">
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    @empty
        <div class="col-md-12">
            <h3 class="center">No result(s) found.</h3>
        </div>
    @endforelse
</div>
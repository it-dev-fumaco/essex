@extends('portal.app')
@section('content')
<div class="main-container">
    <div class="section">
        <div class="container">
            @php
                $kb_storage = 'http://'.env('LINK_KB').'/storage/files/';
            @endphp
            <h2 style="margin-top: -40px; border: 0;">{{ $article->title }}</h2><br>
            <i class="fa fa-calendar-o"></i>&nbsp;{{ Carbon\Carbon::parse($article->created_at)->format('M. d, Y h:i A') }} | <i class="fa fa-folder"></i>&nbsp;{{ $article->category }}
            <div class="row poppins">
                {!! $article->full_text !!}
            </div>
            <hr>
            @if (count($files) > 0)
                <div class="row">
                    <b>Attachements:</b>
                    <div class="container">
                        @foreach ($files as $file)
                            <i class="fa fa-file-text"></i>&nbsp;<a href="{{ $kb_storage.$article->slug.'/'.$file->filename }}" target="_blank">{{ $file->filename }}</a>
                        @endforeach
                    </div>
                </div>
                <hr>
            @endif
            <div class="container-fluid">
                <b>Tags:</b> <br>
                @foreach ($tags as $tag)
                    <a href="/manuals?tag={{ $tag->id }}" class="badge bg-light text-secondary text-decoration-none border border-secondary">{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script src="/vendor/unisharp/laravel-ckeditor/adapters/jquery.js"></script>
@endsection
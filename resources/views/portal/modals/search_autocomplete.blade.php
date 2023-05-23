<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            @foreach ($searchResults as $searchResult)
                <a href="{{ $searchResult->url }}">
                    <b>{{ $searchResult->title }}</b>{{ $searchResult->category }}<br/>
                    {!! str_limit($searchResult->description , $limit = 70, $end = '...') !!}
                </a>
                <hr style="border: 1px solid rgba(0,0,0,.1);">
            @endforeach
        </div>
        <center>
            <p class="submit-search" style="cursor: pointer; color: #0069D9; text-decoration: underline">See all search result(s)</p>
        </center>
    </div>
</div>
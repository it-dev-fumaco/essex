<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            @foreach ($searchResults as $searchResult)
                @php
                    if(is_object($searchResult)){
                        $url = $searchResult->url;
                        $title = $searchResult->title;
                        $category = $searchResult->category;
                        $description = $searchResult->description;
                        $icon = 'icon-info';
                    }else{
                        $url = $searchResult['url'];
                        $title = $searchResult['title'];
                        $category = $searchResult['category'];
                        $description = $searchResult['description'];
                        $icon = 'fa fa-file';
                    }
                @endphp
                <a href="{{ $url }}">
                    <b>{{ $title }}</b> - <span style="text-transform: capitalize !important">{{ str_replace('_', ' ', $category) }}</span><br/>
                    {!! str_limit($description , $limit = 70, $end = '...') !!}
                </a>
                <hr style="border: 1px solid rgba(0,0,0,.1);">
            @endforeach
        </div>
        <center>
            <p class="submit-search" style="cursor: pointer; color: #0069D9; text-decoration: underline">See all search result(s)</p>
        </center>
    </div>
</div>
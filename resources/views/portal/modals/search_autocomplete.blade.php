<div class="container-fluid m-0">
    <div class="card">
        <div class="card-body">
            @if (count($searchResults) > 0)
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
                            $icon = 'fas fa-file';
                        }
                    @endphp
                    <a href="{{ $url }}" class="text-decoration-none">
                        <b>{{ $title }}</b> - <span style="text-transform: capitalize !important">{{ str_replace('_', ' ', $category) }}</span><br/>
                        {!! str_limit($description , $limit = 70, $end = '...') !!}
                    </a>
                    <hr style="border: 1px solid rgba(0,0,0,.1);">
                @endforeach
                <center>
                    <a class="submit-search p-3" style="cursor: pointer; color: #0D6EFD">See all search result(s)</a>
                </center>
            @else
                <center>
                    <p class="p-3">No result(s) found.</p>
                </center>
            @endif
        </div>
    </div>
</div>
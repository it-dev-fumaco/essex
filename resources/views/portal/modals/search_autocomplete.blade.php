<div class="container-fluid m-0">
    <div class="card">
        <div class="card-body">
            @if (count($searchResults) > 0)
                @foreach ($searchResults as $searchResult)
                    @php
                        if ($searchResult instanceof \Spatie\Searchable\SearchResult) {
                            $url = $searchResult->url ?? '#';
                            $title = $searchResult->title ?? '';
                            $category = $searchResult->type ?? '';
                            $description = '';
                            $icon = 'icon-info';
                            $model = $searchResult->searchable;
                            if ($model instanceof \Illuminate\Database\Eloquent\Model) {
                                if ($model->getTable() === 'users' && isset($model->email)) {
                                    $description = (string) $model->email;
                                }
                                if ($description === '' && isset($model->short_text) && $model->short_text !== '') {
                                    $description = (string) $model->short_text;
                                }
                                if ($description === '' && isset($model->content) && $model->content !== '') {
                                    $description = (string) $model->content;
                                }
                                if ($description === '' && isset($model->description) && $model->description !== '') {
                                    $description = (string) $model->description;
                                }
                            }
                            if (($searchResult->type ?? '') === 'Files') {
                                $icon = 'fas fa-file';
                            }
                        } elseif (is_array($searchResult)) {
                            $url = $searchResult['url'] ?? '#';
                            $title = $searchResult['title'] ?? '';
                            $category = $searchResult['category'] ?? '';
                            $description = $searchResult['description'] ?? '';
                            $icon = 'fas fa-file';
                        } else {
                            $url = '#';
                            $title = '';
                            $category = '';
                            $description = '';
                            $icon = 'icon-info';
                        }
                    @endphp
                    <a href="{{ $url }}" class="text-decoration-none">
                        <b>{{ $title }}</b> - <span style="text-transform: capitalize !important">{{ str_replace('_', ' ', $category) }}</span><br/>
                        {!! Illuminate\Support\Str::limit($description, 70, '...') !!}
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
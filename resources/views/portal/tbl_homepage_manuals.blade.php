<div class="row">
    @php
        $chunk_count = count($general_concerns) / 2;
        $chunk_count = round($chunk_count) < 1 ? count($general_concerns) : round($chunk_count);
        $chunk = $general_concerns->chunk($chunk_count);
        $tags_input = null;
        $selected_tags = [];
        if(isset($tags_array['selected_tags']) && $tags_array['selected_tags']){
            $tags_input = collect(array_keys($tags_array['selected_tags']->toArray()))->implode(',');
            $selected_tags = $tags_array['selected_tags'];
        }

        $tags = isset($tags_array['tags']) ? $tags_array['tags'] : [];
    @endphp
    <input type="text" class="tag-input" value="{{ $tags_input }}" style="display: none">
    @if (count($selected_tags) > 0)
        <div class="col-md-12">
            Tags: 
            @foreach ($selected_tags as $tag_id => $tagname)
                <span class="tag" data-id="{{ $tagname }}">{{ $tagname }}&nbsp;<i class="fa fa-remove remove-tag" data-id="{{ $tag_id }}"></i></span>
            @endforeach
        </div>
    @endif
    @foreach ($chunk as $gen_concerns)
    <div class="col-md-6" style="padding: 10px 20px 10px 20px;">
        @foreach ($gen_concerns as $concern)
            @php
                $tag = isset($tags[$concern->id]) ? $tags[$concern->id] : [];
            @endphp
            <div class="container-fluid card" style="box-shadow: 1px 1px 4px #999999; margin-top: 20px;">
                <div class="row equal-height">
                    <div class="col-md-1" style="display: flex; justify-content: center; align-items: center;">
                        <i class="fa fa-question-circle" style="font-size: 23px;"></i>
                    </div>
                    <div class="col-md-11">
                        <a href="/article/{{ $concern->slug }}">
                            @if ($department && $concern->is_private)
                                <span class="badge bg-primary">{{ $department }}</span>&nbsp;
                            @endif
                            <span style="font-style: italic">{{ $concern->category }}</span>
                            <br/>
                            <b>{{ $concern->title }}</b><br>
                            <small class="text-muted">
                                {{ substr($concern->short_text, 0, 45) }}...
                            </small>
                        </a>
                        <small style="margin-top: 10px;">
                            <b>Tags:</b> 
                            @foreach ($tag as $t)
                                <span class="tag add-tag" data-id="{{ $t->id }}">{{ $t->name }}</span>
                            @endforeach
                        </small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endforeach
</div>
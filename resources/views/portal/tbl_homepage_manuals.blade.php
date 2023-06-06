<div class="row">
    <h3>Helpful Articles</h3>
</div>
{{-- <div class="row">
    <form action="/tbl_manuals" id="manuals-form" method="get">
        @foreach ($categories as $id => $category)
            <label class="PillList-item">
                <input type="checkbox" name="category[]" class="category-checkbox" value="{{ $id }}" {{ in_array($id, $request_category) ? 'checked' : null }}>
                <span class="PillList-label">{{ $category }}</span>
            </label>
        @endforeach
    </form>
</div> --}}
<div class="row mt-2">
    @foreach ($general_concerns as $concern)
        @php
            $tag = isset($tags[$concern->id]) ? $tags[$concern->id] : [];
        @endphp
        <div class="col-4 p-2">
            <i class="fa fa-caret-right"></i> &nbsp;
            <a href="/article/{{ $concern->slug }}" class="text-decoration-none">
                <b>{{ $concern->title }}</b>
            </a>
        </div>
    @endforeach
</div>
{{-- <div class="row">
    @foreach ($general_concerns as $concern)
        @php
            $tag = isset($tags[$concern->id]) ? $tags[$concern->id] : [];
        @endphp
        <div class="col-6 p-2">
            <i class="fa fa-caret-right"></i> &nbsp;
            <a href="/article/{{ $concern->slug }}" class="text-decoration-none">
                <b>{{ $concern->title }}</b>
            </a>
        </div>
    @endforeach
</div> --}}
{{-- <div class="row">
    @php
        $chunk_count = count($general_concerns) / 3;
        $chunk_count = round($chunk_count) < 1 ? count($general_concerns) : round($chunk_count);
        $chunk = $general_concerns->chunk($chunk_count);
    @endphp
    @foreach ($chunk as $gen_concerns)
    <div class="col-md-4" style="padding: 0 20px 0 20px;">
        @foreach ($gen_concerns as $concern)
            <div class="col-md-12" style="padding: 10px; margin: 10px 0 10px 0">
                <i class="fa fa-caret-right"></i> &nbsp;
                <a href="/article/{{ $concern->slug }}">
                    <b>{{ $concern->title }}</b>
                </a>
            </div>
            <hr >
        @endforeach
    </div>
    @endforeach
</div> --}}
<style>
    .PillList-item {
        cursor: pointer;
        display: inline-block;
        float: left;
        font-size: 12px;
        font-weight: normal;
        line-height: 20px;
        margin: 0 12px 12px 0;
        text-transform: capitalize;
    }

    .PillList-item input[type="checkbox"] {
        display: none;
    }

    .PillList-item input[type="checkbox"]:checked + .PillList-label {
        background-color: #28A745;
        border: 1px solid #28A745;
        color: #fff;
    }
    
    .PillList-label {
        border: 1px solid #28A745;
        border-radius: 20px;
        color: #28A745;
        display: block;
        padding: 7px 28px;
        text-decoration: none;
    }
</style>
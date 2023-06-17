<div class="row">
    <h3>Helpful Articles</h3>
</div>
<div class="row mt-2">
    @foreach ($general_concerns as $concern)
        @php
            $tag = isset($tags[$concern->id]) ? $tags[$concern->id] : [];
        @endphp
        <div class="col-6 col-xl-4 p-2">
            <i class="fa fa-caret-right"></i> &nbsp;
            <a href="/article/{{ $concern->slug }}" class="text-decoration-none">
                <b>{{ $concern->title }}</b>
            </a>
        </div>
    @endforeach
</div>
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
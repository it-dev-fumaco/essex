@props([
    'title',
    'url' => null,
    'type' => null,
    'category' => null,
    'date' => null,
    'description' => null,
    'icon' => 'fas fa-file',
])

<div class="col-xl-3 col-md-4 col-sm-6 col-xs-12">
    <div class="support-inner">
        <div class="support-info portal-document-card">
            <div class="info-title">
                @if($url)
                    <a href="{{ $url }}" class="text-decoration-none one-line ellipsis responsive-font" target="_blank" rel="noopener">
                        <i class="{{ $icon }}" aria-hidden="true"></i>{{ $title }}
                    </a>
                @else
                    <span class="one-line ellipsis responsive-font d-block">
                        <i class="{{ $icon }}" aria-hidden="true"></i>{{ $title }}
                    </span>
                @endif
                @if($description)
                    <span>{{ \Illuminate\Support\Str::limit($description, 40) }}</span>
                @endif
            </div>
            @if($type || $category || $date)
                <ul class="portal-document-card__meta list-unstyled mb-0 mt-2">
                    @if($type)
                        <li><strong>Type:</strong> {{ $type }}</li>
                    @endif
                    @if($category)
                        <li><strong>Category:</strong> {{ $category }}</li>
                    @endif
                    @if($date)
                        <li><strong>Date:</strong> {{ $date }}</li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
</div>

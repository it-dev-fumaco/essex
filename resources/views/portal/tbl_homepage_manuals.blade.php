@php
    $articleLimit = (int) config('portal.homepage_article_limit', 8);
    $concerns = $general_concerns instanceof \Illuminate\Support\Collection
        ? $general_concerns
        : collect($general_concerns);
    $totalKb = $concerns->count();
    $shownConcerns = $concerns->take($articleLimit);
    $hasMore = $totalKb > $articleLimit;
@endphp
<div class="card portal-card portal-helpful-articles">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Helpful Articles</span>
    </div>
    <div class="card-body p-0">
        <ul class="list-unstyled mb-0 portal-article-list">
            <li class="portal-article-item">
                <span class="portal-article-icon portal-article-icon--blue"><i class="fas fa-book" aria-hidden="true"></i></span>
                <a href="{{ url('/gallery') }}" class="portal-article-link">Updates — Gallery</a>
            </li>
            <li class="portal-article-item">
                <span class="portal-article-icon portal-article-icon--green"><i class="fas fa-users" aria-hidden="true"></i></span>
                <a href="{{ url('/services/directory') }}" class="portal-article-link">Employee Directory</a>
            </li>
            <li class="portal-article-item">
                <span class="portal-article-icon portal-article-icon--teal"><i class="fas fa-book-open" aria-hidden="true"></i></span>
                <a href="{{ url('/manuals') }}" class="portal-article-link">Manuals</a>
            </li>
            @foreach ($shownConcerns as $concern)
                <li class="portal-article-item">
                    <span class="portal-article-icon portal-article-icon--muted"><i class="fas fa-file-alt" aria-hidden="true"></i></span>
                    <a href="{{ url('/article/'.$concern->slug) }}" class="portal-article-link text-decoration-none">
                        <span class="fw-semibold">{{ $concern->title }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
        @if ($hasMore)
            <div class="border-top px-3 py-2 text-center bg-light rounded-bottom">
                <a href="{{ url('/manuals') }}" class="small fw-semibold text-decoration-none">View all articles ({{ $totalKb }})</a>
            </div>
        @endif
    </div>
</div>

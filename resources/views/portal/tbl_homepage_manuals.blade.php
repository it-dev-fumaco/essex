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
<style>
    .portal-helpful-articles .portal-article-list {
        padding: 0.5rem 0;
    }
    .portal-article-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 1.1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: background 0.15s ease;
    }
    .portal-article-item:last-child {
        border-bottom: none;
    }
    .portal-article-item:hover {
        background: rgba(26, 95, 180, 0.04);
    }
    .portal-article-icon {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        color: #fff;
    }
    .portal-article-icon--blue {
        background: linear-gradient(135deg, #1a5fb4, #3d8bfd);
    }
    .portal-article-icon--green {
        background: linear-gradient(135deg, #159957, #20c997);
    }
    .portal-article-icon--teal {
        background: linear-gradient(135deg, #0f8f8a, #2dd4bf);
    }
    .portal-article-icon--muted {
        background: linear-gradient(135deg, #64748b, #94a3b8);
    }
    .portal-article-link {
        color: #1a1a2e;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .portal-article-link:hover {
        color: #1a5fb4;
    }
</style>

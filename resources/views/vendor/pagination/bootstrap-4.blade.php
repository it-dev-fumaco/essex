@if ($paginator->hasPages())
    @php
        $toRelativeUrl = function ($url) {
            if (! $url) {
                return $url;
            }

            // Force pagination links to be relative so they always stay on the current host
            // (prevents navigation to a wrong APP_URL domain like "posse.local").
            return preg_replace('#^https?://[^/]+#', '', $url) ?: $url;
        };
    @endphp
    <ul class="pagination" role="navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                {{-- <span class="page-link" aria-hidden="true">&lsaquo;</span> --}}
                <a class="page-link" aria-hidden="true">&lsaquo;</a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $toRelativeUrl($paginator->previousPageUrl()) }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true">
                    {{-- <span class="page-link">{{ $element }}</span> --}}
                    <a class="page-link">{{ $element }}</a>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page">
                            {{-- <span class="page-link">{{ $page }}</span> --}}
                            <a class="page-link">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $toRelativeUrl($url) }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $toRelativeUrl($paginator->nextPageUrl()) }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                {{-- <span class="page-link" aria-hidden="true">&rsaquo;</span> --}}
                <a class="page-link" aria-hidden="true">&rsaquo;</a>
            </li>
        @endif
    </ul>
@endif

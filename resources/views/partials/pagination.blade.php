@if ($paginator->hasPages())
<nav class="pagination" aria-label="Pagination">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
    <span class="page-btn disabled" aria-disabled="true">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6" />
        </svg>
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" aria-label="Previous page">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6" />
        </svg>
    </a>
    @endif

    {{-- Page numbers --}}
    @foreach ($elements as $element)

    @if (is_string($element))
    <span class="page-dots">{{ $element }}</span>
    @endif

    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <span class="page-btn active" aria-current="page">{{ $page }}</span>
    @else
    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
    @endif
    @endforeach
    @endif

    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" aria-label="Next page">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6" />
        </svg>
    </a>
    @else
    <span class="page-btn disabled" aria-disabled="true">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6" />
        </svg>
    </span>
    @endif

</nav>
@endif
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination-nav">
        <div class="pagination-wrapper">
            <div class="pagination-info">
                <p>
                    <span>Showing</span>
                    <span>{{ $paginator->firstItem() }}</span>
                    <span>to</span>
                    <span>{{ $paginator->lastItem() }}</span>
                    <span>of</span>
                    <span>{{ $paginator->total() }}</span>
                </p>
            </div>

            <div class="pagination-links">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="pagination-btn disabled">
                        ←
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn">
                        ←
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="pagination-dots">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn">
                        →
                    </a>
                @else
                    <span class="pagination-btn disabled">
                        →
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
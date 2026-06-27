@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Навигация по страницам">
        <p class="pagination-summary">
            Показано с {{ $paginator->firstItem() }} по {{ $paginator->lastItem() }} из {{ $paginator->total() }} заявок
        </p>

        <div class="pagination-pages">
            @if ($paginator->onFirstPage())
                <span class="pagination-link pagination-disabled">Назад</span>
            @else
                <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Назад</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-link pagination-disabled">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-link pagination-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Вперед</a>
            @else
                <span class="pagination-link pagination-disabled">Вперед</span>
            @endif
        </div>
    </nav>
@endif

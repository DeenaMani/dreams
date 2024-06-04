@if ($paginator->hasPages() && $paginator->lastPage() > 1)
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <!-- Previous Page Link -->
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="fa fa-arrow-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}"><i class="fa fa-angle-left"></i></a>
                </li>
            @endif
            
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <!-- Display ellipsis (...) for separator -->
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <!-- Highlight current page -->
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            
            <!-- Next Page Link -->
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fa fa-angle-right"></i></a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="fa fa-angle-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif

@if ($paginator->hasPages())
    <nav style="display: flex; justify-content: center; margin-top: 20px;">
        <div style="display: flex; gap: 8px; align-items: center;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="
                    padding: 8px 12px;
                    background: #e5e7eb;
                    color: #9ca3af;
                    border-radius: 6px;
                    font-size: 14px;
                    border: none;
                ">Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" style="
                    padding: 8px 12px;
                    background: #3b82f6;
                    color: white;
                    border-radius: 6px;
                    text-decoration: none;
                    font-size: 14px;
                    transition: all 0.2s;
                ">Previous</a>
            @endif

            {{-- Page Info --}}
            <span style="
                padding: 8px 12px;
                background: #f8fafc;
                color: #475569;
                border-radius: 6px;
                font-size: 14px;
                border: 1px solid #e2e8f0;
            ">
                {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
            </span>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" style="
                    padding: 8px 12px;
                    background: #3b82f6;
                    color: white;
                    border-radius: 6px;
                    text-decoration: none;
                    font-size: 14px;
                    transition: all 0.2s;
                ">Next</a>
            @else
                <span style="
                    padding: 8px 12px;
                    background: #e5e7eb;
                    color: #9ca3af;
                    border-radius: 6px;
                    font-size: 14px;
                    border: none;
                ">Next</span>
            @endif
        </div>
    </nav>
@endif
@if ($paginator->hasPages())
    <div style="display: flex; justify-content: center; align-items: center; gap: 8px; padding: 16px 0;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <div style="width: 40px; height: 40px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                ‹
            </div>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
               style="width: 40px; height: 40px; background: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; font-weight: 600;">
                ‹
            </a>
        @endif

        {{-- Page Numbers --}}
        @php
            $start = max(1, $paginator->currentPage() - 2);
            $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
        @endphp

        @if($start > 1)
            <a href="{{ $paginator->url(1) }}" 
               style="width: 40px; height: 40px; background: white; border: 1px solid #d1d5db; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #374151; text-decoration: none; font-weight: 500;">
                1
            </a>
            @if($start > 2)
                <div style="color: #9ca3af; padding: 0 4px;">...</div>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $paginator->currentPage())
                <div style="width: 40px; height: 40px; background: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                    {{ $i }}
                </div>
            @else
                <a href="{{ $paginator->url($i) }}" 
                   style="width: 40px; height: 40px; background: white; border: 1px solid #d1d5db; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #374151; text-decoration: none; font-weight: 500;">
                    {{ $i }}
                </a>
            @endif
        @endfor

        @if($end < $paginator->lastPage())
            @if($end < $paginator->lastPage() - 1)
                <div style="color: #9ca3af; padding: 0 4px;">...</div>
            @endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}" 
               style="width: 40px; height: 40px; background: white; border: 1px solid #d1d5db; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #374151; text-decoration: none; font-weight: 500;">
                {{ $paginator->lastPage() }}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
               style="width: 40px; height: 40px; background: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; font-weight: 600;">
                ›
            </a>
        @else
            <div style="width: 40px; height: 40px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                ›
            </div>
        @endif
    </div>

    {{-- Page Info --}}
    <div style="text-align: center; margin-top: 8px; font-size: 12px; color: #6b7280;">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
    </div>
@endif
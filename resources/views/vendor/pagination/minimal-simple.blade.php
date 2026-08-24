@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="صفحه‌بندی">
        <span></span>
        <div class="pager__pages">
            @if ($paginator->onFirstPage())
                <span class="btn btn--ghost btn--sm" aria-disabled="true">صفحه‌ی قبل</span>
            @else
                <a class="btn btn--ghost btn--sm" href="{{ $paginator->previousPageUrl() }}" rel="prev">صفحه‌ی قبل</a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="btn btn--ghost btn--sm" href="{{ $paginator->nextPageUrl() }}" rel="next">صفحه‌ی بعد</a>
            @else
                <span class="btn btn--ghost btn--sm" aria-disabled="true">صفحه‌ی بعد</span>
            @endif
        </div>
    </nav>
@endif

@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="صفحه‌بندی">

        <p class="micro">
            @if(method_exists($paginator, 'total'))
                نمایش {{ \App\Support\Fmt::num($paginator->firstItem()) }}
                تا {{ \App\Support\Fmt::num($paginator->lastItem()) }}
                از {{ \App\Support\Fmt::num($paginator->total()) }} مورد
            @endif
        </p>

        <div class="pager__pages">
            {{-- قبلی --}}
            @if ($paginator->onFirstPage())
                <span class="pager__link" aria-disabled="true" aria-label="صفحه‌ی قبل">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </span>
            @else
                <a class="pager__link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="صفحه‌ی قبل">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pager__gap">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pager__link" aria-current="page">{{ \App\Support\Fmt::fa($page) }}</span>
                        @else
                            <a class="pager__link" href="{{ $url }}" aria-label="صفحه‌ی {{ \App\Support\Fmt::fa($page) }}">{{ \App\Support\Fmt::fa($page) }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- بعدی --}}
            @if ($paginator->hasMorePages())
                <a class="pager__link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="صفحه‌ی بعد">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>
                </a>
            @else
                <span class="pager__link" aria-disabled="true" aria-label="صفحه‌ی بعد">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif

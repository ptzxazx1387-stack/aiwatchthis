@props([
    'title',
    'href' => null,
    'linkLabel' => 'مشاهده همه',
])

<div class="card__head">
    <h2 class="h4">{{ $title }}</h2>
    @if(isset($aside))
        {{ $aside }}
    @elseif($href)
        <a href="{{ $href }}" class="row-6 micro nowrap">
            <span>{{ $linkLabel }}</span>
            <x-icon name="chevron" class="icon-forward" :size="12" />
        </a>
    @endif
</div>

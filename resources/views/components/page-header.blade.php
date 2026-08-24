@props([
    'eyebrow' => null,
    'title',
    'lead' => null,
    'back' => null,
    'backLabel' => 'بازگشت',
])

<header class="mb-24" data-reveal>
    @if($back)
        <a href="{{ $back }}" class="row-6 micro mb-16" style="display:inline-flex">
            {{-- آیکون جهت‌دار: در RTL روی محور افقی قرینه می‌شود --}}
            <x-icon name="arrow" class="icon-back" :size="14" />
            <span>{{ $backLabel }}</span>
        </a>
    @endif

    <div class="row between wrap" style="gap:20px">
        <div>
            @if($eyebrow)<span class="eyebrow">{{ $eyebrow }}</span>@endif
            <h1 class="h1">{{ $title }}</h1>
            @if($lead)<p class="lead mt-8">{{ $lead }}</p>@endif
        </div>

        @if(isset($actions))
            <div class="row-8 wrap">{{ $actions }}</div>
        @endif
    </div>
</header>

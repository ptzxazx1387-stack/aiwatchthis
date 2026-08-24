@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
    'count' => null,
])

<a href="{{ $href }}"
   @if($active) aria-current="page" @endif
   {{ $attributes->class('navlink') }}>
    @if($icon)
        <x-icon :name="$icon" :size="16" />
    @endif
    <span class="truncate">{{ $slot }}</span>
    @if($count)
        <span class="navlink__count">{{ \App\Support\Fmt::num($count) }}</span>
    @endif
</a>

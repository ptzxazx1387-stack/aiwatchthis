@props([
    'icon' => 'inbox',
    'title' => 'چیزی اینجا نیست',
    'tight' => false,
])

<div {{ $attributes->class(['empty', 'empty--tight' => $tight]) }}>
    <span class="empty__mark"><x-icon :name="$icon" :size="20" /></span>
    <p class="empty__title">{{ $title }}</p>
    @if(trim($slot) !== '')
        <div class="empty__body">{{ $slot }}</div>
    @endif
</div>

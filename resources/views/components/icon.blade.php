@props([
    'name',
    'size' => 16,
])

@php
    // یک خانواده‌ی آیکون خطی. ضخامت ۱٫۷۵ همه‌جا، و ۲ فقط زیر ۱۴ پیکسل
    // که خط نازک تکه‌تکه دیده می‌شود.
    $stroke = $size < 14 ? 2 : 1.75;

    $paths = [
        'home'      => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9.5"/><path d="M9.5 21v-6h5v6"/>',
        'megaphone' => '<path d="M3 11v2a1 1 0 0 0 1 1h3l6 4V6L7 10H4a1 1 0 0 0-1 1Z"/><path d="M17 8.5a5 5 0 0 1 0 7"/><path d="M7 14v5"/>',
        'check'     => '<path d="M20 6 9 17l-5-5"/>',
        'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/>',
        'x'         => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'x-circle'  => '<circle cx="12" cy="12" r="9"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>',
        'users'     => '<path d="M16 20v-1.5a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4V20"/><circle cx="9" cy="7" r="3.2"/><path d="M22 20v-1.5a4 4 0 0 0-3-3.87"/><path d="M16.5 4.13a4 4 0 0 1 0 5.74"/>',
        'user'      => '<path d="M19 20v-1.5a5 5 0 0 0-5-5h-4a5 5 0 0 0-5 5V20"/><circle cx="12" cy="7" r="3.5"/>',
        'star'      => '<path d="m12 3.5 2.6 5.4 5.9.8-4.3 4.1 1 5.9-5.2-2.8-5.2 2.8 1-5.9L3.5 9.7l5.9-.8Z"/>',
        'wallet'    => '<path d="M3 8.5A2.5 2.5 0 0 1 5.5 6H18a2 2 0 0 1 2 2v1"/><path d="M3 8.5V17a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-2"/><path d="M21 11h-4a2 2 0 0 0 0 4h4Z"/>',
        'banknote'  => '<rect x="2.5" y="6" width="19" height="12" rx="2.5"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/>',
        'bell'      => '<path d="M18 8.5a6 6 0 1 0-12 0c0 5-2 6.5-2 6.5h16s-2-1.5-2-6.5"/><path d="M13.7 19a2 2 0 0 1-3.4 0"/>',
        'settings'  => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.6 1.6 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.6 1.6 0 0 0-1.8-.3 1.6 1.6 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1A1.6 1.6 0 0 0 9 19.4a1.6 1.6 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.6 1.6 0 0 0 .3-1.8 1.6 1.6 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1A1.6 1.6 0 0 0 4.6 9a1.6 1.6 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.6 1.6 0 0 0 1.8.3H9a1.6 1.6 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.6 1.6 0 0 0 1 1.5 1.6 1.6 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.6 1.6 0 0 0-.3 1.8V9a1.6 1.6 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.6 1.6 0 0 0-1.5 1Z"/>',
        'chart'     => '<path d="M3 3v16.5a1.5 1.5 0 0 0 1.5 1.5H21"/><path d="M7.5 15.5 11 11l3 2.5 4.5-5.5"/>',
        'layers'    => '<path d="m12 3 9 4.5-9 4.5-9-4.5Z"/><path d="m3 12.5 9 4.5 9-4.5"/><path d="m3 17 9 4.5 9-4.5"/>',
        'search'    => '<circle cx="11" cy="11" r="6.5"/><path d="m20 20-3.6-3.6"/>',
        'inbox'     => '<path d="M21 12.5V18a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5.5"/><path d="M3 12.5h5l1.5 2.5h5L16 12.5h5"/><path d="m5.5 4.5 13 0 2.5 8H3Z"/>',
        'upload'    => '<path d="M21 15v3.5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V15"/><path d="M12 15.5V3.5"/><path d="m7.5 8 4.5-4.5L16.5 8"/>',
        'plus'      => '<path d="M12 5v14"/><path d="M5 12h14"/>',
        'arrow'     => '<path d="M19 12H5"/><path d="m12 5-7 7 7 7"/>',
        'arrow-back'=> '<path d="M5 12h14"/><path d="m12 19 7-7-7-7"/>',
        'chevron'   => '<path d="m9 6 6 6-6 6"/>',
        'menu'      => '<path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h10"/>',
        'logout'    => '<path d="M15 5V4a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h9a1 1 0 0 0 1-1v-1"/><path d="M20 12H9"/><path d="m12 8-4 4 4 4"/>',
        'eye'       => '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/>',
        'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 2"/>',
        'calendar'  => '<rect x="3.5" y="5" width="17" height="16" rx="2.5"/><path d="M8 3v4M16 3v4M3.5 10h17"/>',
        'pin'       => '<path d="M12 21s7-6 7-11a7 7 0 1 0-14 0c0 5 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/>',
        'tag'       => '<path d="M20.5 13.5 13 21a2 2 0 0 1-2.8 0l-7-7A2 2 0 0 1 2.6 12l.4-7A2 2 0 0 1 5 3l7-.4a2 2 0 0 1 1.5.6l7 7a2 2 0 0 1 0 3.3Z"/><circle cx="7.5" cy="7.5" r="1.2"/>',
        'target'    => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1"/>',
        'pause'     => '<rect x="7" y="5" width="3.5" height="14" rx="1.2"/><rect x="13.5" y="5" width="3.5" height="14" rx="1.2"/>',
        'edit'      => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7.5 18.5 3 20l1.5-4.5Z"/>',
        'trash'     => '<path d="M4 7h16"/><path d="M9.5 7V5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v2"/><path d="M6.5 7 7.4 20a1 1 0 0 0 1 .9h7.2a1 1 0 0 0 1-.9L17.5 7"/>',
        'image'     => '<rect x="3.5" y="4.5" width="17" height="15" rx="2.5"/><circle cx="9" cy="10" r="1.8"/><path d="m4.5 17.5 4.6-4.2a1.6 1.6 0 0 1 2.2 0L17 18.5"/>',
        'copy'      => '<rect x="9" y="9" width="11" height="11" rx="2.2"/><path d="M15 5.5V5a1.5 1.5 0 0 0-1.5-1.5h-8A1.5 1.5 0 0 0 4 5v8a1.5 1.5 0 0 0 1.5 1.5H6"/>',
        'info'      => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 7.8h.01"/>',
        'alert'     => '<path d="M10.3 3.9 2.4 17.5A2 2 0 0 0 4.1 20.5h15.8a2 2 0 0 0 1.7-3l-7.9-13.6a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4"/><path d="M12 16.8h.01"/>',
        'sparkle'   => '<path d="M12 3.5 13.6 9 19 10.6 13.6 12.2 12 17.7 10.4 12.2 5 10.6 10.4 9Z"/><path d="M18.5 16.5 19.2 18.8 21.5 19.5 19.2 20.2 18.5 22.5 17.8 20.2 15.5 19.5 17.8 18.8Z"/>',
        'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M16.8 7.2h.01"/>',
        'trend'     => '<path d="m3 16 5.5-5.5 3.5 3.5L21 5"/><path d="M15.5 5H21v5.5"/>',
        'command'   => '<path d="M15 6a3 3 0 1 1 3 3h-3Zm0 0v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12"/>',
        'filter'    => '<path d="M3.5 5.5h17l-6.5 8V20l-4-2v-4.5Z"/>',
        'down'      => '<path d="m6 9 6 6 6-6"/>',
    ];

    $body = $paths[$name] ?? $paths['info'];
@endphp

<svg {{ $attributes }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24"
     fill="none" stroke="currentColor" stroke-width="{{ $stroke }}"
     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    {!! $body !!}
</svg>

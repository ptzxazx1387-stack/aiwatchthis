@props([
    'label' => null,
    'name' => null,
    'required' => false,
    'hint' => null,
    'optional' => false,
])

<div {{ $attributes->class('field') }}>
    @if($label)
        <label class="label" @if($name) for="f-{{ $name }}" @endif>
            {{ $label }}
            @if($required)<span class="req" aria-hidden="true">*</span><span class="sr-only">(الزامی)</span>@endif
            @if($optional)<span class="label__opt">— اختیاری</span>@endif
        </label>
    @endif

    {{ $slot }}

    @if($hint)
        <p class="hint">{{ $hint }}</p>
    @endif

    @if($name && $errors->has($name))
        <p class="err">{{ $errors->first($name) }}</p>
    @endif
</div>

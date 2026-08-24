@extends('layouts.app')

@section('title', 'سطوح کاربری')

@section('content')
    <x-page-header eyebrow="Groups" title="سطوح کاربری" lead="سقف کمپین و حداقل قدرت پیج هر سطح سفیر را اینجا تعریف کنید." />

    <div class="grid grid-side grid-24">
        <form method="POST" action="{{ route('admin.groups.store') }}" class="card stack-16" data-reveal>
            @csrf
            <span class="eyebrow">New level</span>
            <h2 class="h3">ایجاد سطح جدید</h2>
            <x-field label="نام گروه" name="name" required><input type="text" name="name" required class="input" placeholder="مثلاً سطح طلایی"></x-field>
            <x-field label="کد یکتا" name="code" required><input type="text" name="code" required class="input ltr" placeholder="gold"></x-field>
            <div class="grid grid-2 keep">
                <x-field label="سقف روزانه" name="daily_campaign_limit"><input type="number" name="daily_campaign_limit" required value="1" min="0" class="input figure"></x-field>
                <x-field label="سقف هفتگی" name="weekly_campaign_limit"><input type="number" name="weekly_campaign_limit" required value="5" min="0" class="input figure"></x-field>
            </div>
            <x-field label="حداقل میانگین ویو" name="min_avg_views"><input type="number" name="min_avg_views" value="0" min="0" class="input figure"></x-field>
            <x-field label="توضیحات" name="description" optional><textarea name="description" rows="2" class="textarea"></textarea></x-field>
            <button class="btn">افزودن سطح</button>
        </form>

        <div class="stack-16">
            @forelse($groups as $group)
                <article class="card stack-16" data-reveal>
                    <form method="POST" action="{{ route('admin.groups.update', $group) }}" class="stack-16">
                        @csrf
                        <div class="row between wrap" style="gap:12px">
                            <div class="row-8">
                                <x-badge :tone="$group->is_active ? 'emerald' : 'mist'">{{ $group->code }}</x-badge>
                                <input type="text" name="name" value="{{ $group->name }}" class="input" style="max-inline-size:240px">
                            </div>
                            <label class="check">
                                <input type="checkbox" name="is_active" value="1" {{ $group->is_active ? 'checked' : '' }}>
                                <span>فعال</span>
                            </label>
                        </div>

                        <div class="grid grid-4 keep" style="gap:12px">
                            <x-field label="سقف روزانه"><input type="number" name="daily_campaign_limit" value="{{ $group->daily_campaign_limit }}" class="input figure"></x-field>
                            <x-field label="سقف هفتگی"><input type="number" name="weekly_campaign_limit" value="{{ $group->weekly_campaign_limit }}" class="input figure"></x-field>
                            <x-field label="حداقل ویو"><input type="number" name="min_avg_views" value="{{ $group->min_avg_views }}" class="input figure"></x-field>
                            <x-field label="تعداد سفیر"><div class="strong figure" style="padding-block:10px">{{ \App\Support\Fmt::num($group->ambassador_profiles_count ?? 0) }}</div></x-field>
                        </div>

                        <div class="row between wrap" style="gap:12px">
                            <input type="text" name="description" value="{{ $group->description }}" class="input grow">
                            <button class="btn btn--sm">ذخیره تغییرات</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.groups.destroy', $group) }}" data-confirm="این سطح برای همیشه حذف می‌شود؟" data-confirm-title="حذف سطح" data-confirm-tone="rose" data-confirm-ok="حذف کن">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn--quiet btn--xs tone-rose">حذف سطح</button>
                    </form>
                </article>
            @empty
                <div class="card"><x-empty-state icon="layers" title="سطحی تعریف نشده است" tight>برای شروع، یک سطح کاربری بسازید.</x-empty-state></div>
            @endforelse
        </div>
    </div>
@endsection

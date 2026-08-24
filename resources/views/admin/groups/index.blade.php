@extends('layouts.app')

@section('title', 'سطوح کاربری')
@section('page-title', 'سطوح کاربری (گروه‌ها)')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- فرم ایجاد گروه --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 h-fit">
            <h3 class="font-bold text-gray-800 mb-4">ایجاد گروه جدید</h3>
            <form method="POST" action="{{ route('admin.groups.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">نام گروه</label>
                    <input type="text" name="name" required class="w-full text-sm rounded-lg border-gray-300 px-3 py-2 border">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">کد یکتا</label>
                    <input type="text" name="code" required placeholder="level-4" class="w-full text-sm rounded-lg border-gray-300 px-3 py-2 border">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">سقف روزانه</label>
                        <input type="number" name="daily_campaign_limit" required value="1" class="w-full text-sm rounded-lg border-gray-300 px-3 py-2 border">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">سقف هفتگی</label>
                        <input type="number" name="weekly_campaign_limit" required value="5" class="w-full text-sm rounded-lg border-gray-300 px-3 py-2 border">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">حداقل میانگین ویو</label>
                    <input type="number" name="min_avg_views" value="0" class="w-full text-sm rounded-lg border-gray-300 px-3 py-2 border">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">توضیحات</label>
                    <textarea name="description" rows="2" class="w-full text-sm rounded-lg border-gray-300 px-3 py-2 border"></textarea>
                </div>
                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg py-2 text-sm font-medium">افزودن گروه</button>
            </form>
        </div>

        {{-- لیست گروه‌ها --}}
        <div class="lg:col-span-2 space-y-4">
            @forelse($groups as $group)
                <div class="bg-white rounded-2xl border border-gray-200 p-5">
                    <form method="POST" action="{{ route('admin.groups.update', $group) }}" class="space-y-3">
                        @csrf
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $group->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">{{ $group->code }}</span>
                                <input type="text" name="name" value="{{ $group->name }}" class="font-bold text-gray-800 text-sm rounded border-gray-300 px-2 py-1 border">
                            </div>
                            <label class="flex items-center gap-2 text-xs text-gray-600">
                                <input type="checkbox" name="is_active" value="1" {{ $group->is_active ? 'checked' : '' }}> فعال
                            </label>
                        </div>

                        <div class="grid grid-cols-4 gap-3 text-xs">
                            <div>
                                <label class="block text-gray-500 mb-1">سقف روزانه</label>
                                <input type="number" name="daily_campaign_limit" value="{{ $group->daily_campaign_limit }}" class="w-full rounded-lg border-gray-300 px-2 py-1.5 border">
                            </div>
                            <div>
                                <label class="block text-gray-500 mb-1">سقف هفتگی</label>
                                <input type="number" name="weekly_campaign_limit" value="{{ $group->weekly_campaign_limit }}" class="w-full rounded-lg border-gray-300 px-2 py-1.5 border">
                            </div>
                            <div>
                                <label class="block text-gray-500 mb-1">حداقل ویو</label>
                                <input type="number" name="min_avg_views" value="{{ $group->min_avg_views }}" class="w-full rounded-lg border-gray-300 px-2 py-1.5 border">
                            </div>
                            <div>
                                <label class="block text-gray-500 mb-1">تعداد سفیر</label>
                                <div class="py-1.5 text-gray-700 font-bold">{{ $group->ambassador_profiles_count ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <input type="text" name="description" value="{{ $group->description }}" class="flex-1 text-xs rounded-lg border-gray-300 px-3 py-2 border">
                            <div class="flex gap-2 mr-3">
                                <button class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-xs font-medium">ذخیره</button>
                            </div>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('admin.groups.destroy', $group) }}" class="mt-2" onsubmit="return confirm('این گروه حذف شود؟')">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs text-rose-600 hover:underline">حذف گروه</button>
                    </form>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center text-gray-400">گروهی تعریف نشده است</div>
            @endforelse
        </div>
    </div>
@endsection

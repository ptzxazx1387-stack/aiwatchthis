@extends('layouts.app')

@section('title', 'مدیریت کاربران')

@section('content')
    <x-page-header eyebrow="Users" title="مدیریت کاربران" lead="نقش، وضعیت و موجودی همه حساب‌ها در یک جدول ساده قابل بررسی است." />

    <x-filter-chips
        route="admin.users.index"
        param="role"
        :current="request('role')"
        :options="['' => 'همه', 'admin' => 'مدیران', 'advertiser' => 'تبلیغ‌دهندگان', 'ambassador' => 'سفیران']"
        class="mb-24" />

    <section class="card card--flush" data-reveal>
        <div class="tablewrap">
            <table class="table">
                <thead>
                <tr><th>کاربر</th><th>نقش</th><th>وضعیت</th><th>موجودی</th><th>تاریخ ثبت</th><th>عملیات</th></tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="row-8">
                                <x-avatar :name="$user->name" size="sm" tone="mist" />
                                <div class="stack-4">
                                    <span class="strong">{{ $user->name }}</span>
                                    <span class="micro ltr">{{ $user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td><x-badge tone="sky">{{ $user->role->label() }}</x-badge></td>
                        <td><x-badge :tone="$user->status === 'active' ? 'emerald' : 'rose'">{{ $user->status === 'active' ? 'فعال' : 'معلق' }}</x-badge></td>
                        <td class="num">@money($user->wallet?->balance ?? 0)</td>
                        <td class="num dim">@jshort($user->created_at)</td>
                        <td>
                            <div class="row-6">
                                <form method="POST" action="{{ route('admin.users.toggleStatus', $user) }}">
                                    @csrf
                                    <button class="btn btn--ghost btn--xs">{{ $user->status === 'active' ? 'تعلیق' : 'فعال‌سازی' }}</button>
                                </form>
                                @if(!$user->isAdmin() || auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.changeRole', $user) }}">
                                        @csrf
                                        <select name="role" class="select" style="min-block-size:30px;padding-inline:28px 10px;font-size:11px" onchange="this.form.submit()">
                                            @foreach(\App\Enums\UserRole::options() as $key => $label)
                                                <option value="{{ $key }}" {{ $user->role->value === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-empty-state icon="users" title="کاربری یافت نشد" tight /></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card__foot">{{ $users->links() }}</div>
    </section>
@endsection

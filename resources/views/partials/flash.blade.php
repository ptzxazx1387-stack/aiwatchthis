{{--
    پیام‌های لحظه‌ای. بدون جاوااسکریپت هم دیده می‌شوند؛ JS فقط بعد از چند
    ثانیه محوشان می‌کند و دکمه‌ی بستن را کاربردی نگه می‌دارد.
--}}
@if(session('success') || session('error') || session('warning') || $errors->any())
    <div class="toasts" role="status" aria-live="polite">

        @if(session('success'))
            <div class="toast toast--emerald" data-life="5000">
                <span class="toast__icon"><x-icon name="check-circle" /></span>
                <p class="grow">{{ session('success') }}</p>
                <button type="button" class="iconbtn" data-toast-close aria-label="بستن پیام">
                    <x-icon name="x" :size="14" />
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="toast toast--amber" data-life="7000">
                <span class="toast__icon"><x-icon name="alert" /></span>
                <p class="grow">{{ session('warning') }}</p>
                <button type="button" class="iconbtn" data-toast-close aria-label="بستن پیام">
                    <x-icon name="x" :size="14" />
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="toast toast--rose" data-life="9000">
                <span class="toast__icon"><x-icon name="x-circle" /></span>
                <p class="grow">{{ session('error') }}</p>
                <button type="button" class="iconbtn" data-toast-close aria-label="بستن پیام">
                    <x-icon name="x" :size="14" />
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="toast toast--rose" data-life="12000">
                <span class="toast__icon"><x-icon name="alert" /></span>
                <div class="grow">
                    <p class="strong" style="font-size:13px">فرم را کامل کنید</p>
                    <ul class="mt-8">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="iconbtn" data-toast-close aria-label="بستن پیام">
                    <x-icon name="x" :size="14" />
                </button>
            </div>
        @endif

    </div>
@endif

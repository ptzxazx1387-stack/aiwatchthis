{{--
    دیالوگ‌های مشترک.

    هیچ‌کدام «مکانیزم» نیستند، فقط «تجربه‌ی بهتر»اند: بدون جاوااسکریپت هر
    فرم مستقیم ارسال می‌شود و هر لینک کار می‌کند.
--}}

{{-- جعبه‌ی فرمان: Ctrl/⌘ + K یا کلید / --}}
<div class="modal palette" data-palette hidden role="dialog" aria-modal="true" aria-label="جست‌وجوی سریع">
    <div class="modal__scrim"></div>
    <div class="modal__panel">
        <input type="search" class="palette__input" data-palette-input
               placeholder="کجا می‌خواهید بروید؟ بنویسید…" autocomplete="off" spellcheck="false"
               aria-label="جست‌وجو در بخش‌های سامانه">
        <div class="palette__list" data-palette-list></div>
        <div class="palette__foot">
            <span class="row-6"><span class="kbd">↑</span><span class="kbd">↓</span> جابه‌جایی</span>
            <span class="row-6"><span class="kbd">↵</span> رفتن</span>
            <span class="row-6"><span class="kbd">Esc</span> بستن</span>
        </div>
    </div>
</div>

{{-- تأیید کارهای بازگشت‌ناپذیر --}}
<div class="modal" data-confirm-modal hidden role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div class="modal__scrim"></div>
    <div class="modal__panel">
        <h2 class="h3" id="confirm-title" data-confirm-title>مطمئن هستید؟</h2>
        <p class="body mt-8" data-confirm-body></p>
        <div class="row-8 mt-24" style="justify-content:flex-end">
            <button type="button" class="btn btn--ghost" data-modal-close>انصراف</button>
            <button type="button" class="btn" data-confirm-ok>بله</button>
        </div>
    </div>
</div>

{{-- ثبت دلیل — رد کردن یک ویو، یک کمپین یا یک برداشت --}}
<div class="modal" data-reason-modal hidden role="dialog" aria-modal="true" aria-labelledby="reason-title">
    <div class="modal__scrim"></div>
    <div class="modal__panel">
        <h2 class="h3" id="reason-title" data-reason-title>ثبت دلیل</h2>

        <div class="field mt-16">
            <label class="label" for="reason-input" data-reason-label>دلیل</label>
            <textarea class="textarea" id="reason-input" data-reason-input rows="3"></textarea>
            <p class="err" data-reason-err hidden>نوشتن دلیل الزامی است — این متن برای کاربر ارسال می‌شود.</p>
        </div>

        <div class="row-8 mt-24" style="justify-content:flex-end">
            <button type="button" class="btn btn--ghost" data-modal-close>انصراف</button>
            <button type="button" class="btn btn--rose" data-reason-ok>ثبت</button>
        </div>
    </div>
</div>

@extends('layouts.website')

@section('title', __('tenders.payment_details'))

@section('content')
<div class="container py-5" style="max-width: 600px;">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="bi bi-cash-stack text-success" style="font-size: 3rem;"></i>
                <h3 class="fw-bold mt-2">{{ __('tenders.payment_details') }}</h3>
                <p class="text-muted">
                    @if($type === 'add')
                        دفع رسوم لمرة واحدة لإضافة مناقصة جديدة
                    @else
                        دفع رسوم لمرة واحدة للتقديم على المناقصة: <strong>{{ $tender->title }}</strong>
                    @endif
                </p>
                <div class="alert alert-info d-inline-block px-4 py-2 mt-2">
                    <span class="fs-5">المبلغ المطلوب: <strong>{{ number_format($fee, 2) }} {{ __('tenders.sar') }}</strong></span>
                </div>
            </div>

            <form action="{{ $type === 'add' ? route('website.tenders.payToAdd') : route('website.tenders.pay', $tender->id) }}" method="POST" dir="rtl">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold">اسم المحول</label>
                    <input type="text" name="transfer_name" class="form-control form-control-lg" placeholder="اسم المحول" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">رقم الحوالة / الإيصال</label>
                    <input type="text" name="{{ $type === 'add' ? 'transfer_number' : 'receipt_number' }}" class="form-control form-control-lg" placeholder="رقم الحوالة/الإيصال" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle me-1"></i> {{ __('tenders.confirm_payment') }}
                    </button>
                    <a href="{{ $type === 'add' ? route('website.tenders.index') : route('website.tenders.show', $tender->id) }}" class="btn btn-outline-secondary btn-lg">
                        إلغاء والعودة
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

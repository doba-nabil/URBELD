@extends('website.layouts.master')
@section('title', 'إتمام الدفع - ' . $package->name)
@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm border-0 rounded-4 p-5">
                <div class="mb-4">
                    <i class="bi bi-credit-card text-primary" style="font-size: 4rem;"></i>
                </div>
                <h3 class="fw-bold mb-3">صفحة الدفع (نسخة تجريبية)</h3>
                <p class="text-muted fs-5 mb-4">
                    لقد اخترت باقة: <strong class="text-dark">{{ $package->name }}</strong><br>
                    المبلغ المطلوب: <strong class="text-success">{{ number_format($package->price, 0) }} ريال / سنوياً</strong>
                </p>
                <div class="alert alert-info d-inline-block">
                    <i class="bi bi-info-circle me-2"></i>
                    هذه الصفحة عبارة عن واجهة مؤقتة (Placeholder) لبوابة الدفع.
                </div>
                <div class="mt-4">
                    <form action="{{ route('checkout.package.process', $package->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg px-5">تأكيد الدفع</button>
                    </form>
                    <a href="{{ route('profile.subscription') }}" class="btn btn-outline-secondary btn-lg px-5">العودة</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

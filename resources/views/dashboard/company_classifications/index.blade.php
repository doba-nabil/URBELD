@extends('dashboard.layout.master')

@section('title', __('admin.company_classifications') ?? 'تصنيفات وحجم الشركات')

@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light">{{ __('admin.company_classifications') ?? 'تصنيفات وحجم الشركات' }}</span>
            </h4>
            <a href="{{ route('company_classifications.create') }}" class="btn btn-primary">
                <i class="ti tabler-plus me-1"></i> {{ __('admin.add_new') ?? 'إضافة جديد' }}
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="card-datatable table-responsive">
                    {{ $dataTable->table(['class' => 'table border-top'], true) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('dashboard-head')
    @include('dashboard.partials.index.css')
@endsection

@section('dashboard-footer')
    {{ $dataTable->scripts() }}
    @include('dashboard.partials.index.js')
@endsection

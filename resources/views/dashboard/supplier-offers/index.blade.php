@extends('dashboard.layout.master')
@section('title', __('admin.supplier_offers') ?? 'عروض الموردين')
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between align-items-center border-b">
                {{ __('admin.supplier_offers') ?? 'عروض الموردين' }}
                <div class="buttons">
                    @include('dashboard.partials.index.table_btns')
                    <a href="{{ route('supplier-offers.create') }}" class="btn btn-primary">
                        <i class="ti tabler-plus"></i> {{ __('admin.add_new') }}
                    </a>
                </div>
            </h5>
            <div class="table-responsive text-nowrap">
                {{ $dataTable->table() }}
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

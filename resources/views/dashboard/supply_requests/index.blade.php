@extends('dashboard.layout.master')
@section('title', __('admin.supply_requests') ?? 'طلبات التوريد')
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <h5 class="card-header d-flex justify-content-between border-b">
                {{ __('admin.supply_requests') ?? 'طلبات التوريد' }}
                <div class="buttons d-flex justify-content-between">
                    @include('dashboard.partials.index.table_btns')
                </div>
            </h5>
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

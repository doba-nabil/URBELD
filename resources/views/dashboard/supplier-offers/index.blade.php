@extends('dashboard.layout.master')
@section('title', __('admin.supplier_offers'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                {{ __('admin.supplier_offers') }}
                <a href="{{ route('supplier-offers.create') }}" class="btn btn-primary">
                    <i class="ti tabler-plus"></i> {{ __('admin.add_new') }}
                </a>
            </h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>اسم العرض</th>
                            <th>المورد</th>
                            <th>نسبة الخصم</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($offer->getFirstMediaUrl('offer_images'))
                                        <img src="{{ $offer->getFirstMediaUrl('offer_images') }}" alt="Image" width="50" class="rounded">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $offer->title }}</td>
                                <td>{{ $offer->user->name ?? '-' }}</td>
                                <td>{{ $offer->discount_percentage ?? 0 }}%</td>
                                <td>
                                    <a href="{{ route('supplier-offers.edit', $offer->id) }}" class="btn btn-sm btn-info"><i class="ti tabler-edit"></i></a>
                                    <form action="{{ route('supplier-offers.destroy', $offer->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('admin.are_you_sure') }}')"><i class="ti tabler-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __('admin.no_data_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($offers->hasPages())
                <div class="card-footer border-top">
                    {{ $offers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

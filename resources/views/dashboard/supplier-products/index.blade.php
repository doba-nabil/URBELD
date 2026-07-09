@extends('dashboard.layout.master')
@section('title', __('admin.supplier_products'))
@section('dashboard-main')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                {{ __('admin.supplier_products') }}
                <a href="{{ route('supplier-products.create') }}" class="btn btn-primary">
                    <i class="ti tabler-plus"></i> {{ __('admin.add_new') }}
                </a>
            </h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>اسم المنتج</th>
                            <th>المورد</th>
                            <th>السعر</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($product->getFirstMediaUrl('product_images'))
                                        <img src="{{ $product->getFirstMediaUrl('product_images') }}" alt="Image" width="50" class="rounded">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $product->title }}</td>
                                <td>{{ $product->user->name ?? '-' }}</td>
                                <td>{{ $product->price ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('supplier-products.edit', $product->id) }}" class="btn btn-sm btn-info"><i class="ti tabler-edit"></i></a>
                                    <form action="{{ route('supplier-products.destroy', $product->id) }}" method="POST" class="d-inline-block">
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
            @if($products->hasPages())
                <div class="card-footer border-top">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

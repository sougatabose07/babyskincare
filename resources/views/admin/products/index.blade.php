@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">Products</h1>
                <p class="text-muted mb-0">Manage products for the admin section.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add Product</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table id="products-table" class="table table-hover mb-0" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>URL</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Featured</th>
                                <th width="120" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    @push('scripts')
        <script>
            $(function () {
                $('#products-table').DataTable({
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: '{{ route('admin.products.data') }}',
                        dataSrc: 'data'
                    },
                    columns: [
                        { data: 'name' },
                        { data: 'category' },
                        { data: 'url' },
                        { data: 'price' },
                        { data: 'stock' },
                        { data: 'featured' },
                        { data: 'actions', orderable: false, searchable: false }
                    ],
                    order: [[0, 'asc']],
                    responsive: true,
                    language: {
                        search: 'Search products:'
                    }
                });
            });
        </script>
    @endpush
@endsection

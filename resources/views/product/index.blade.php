@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">


            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="float-left">Products</h3>
                        <div class="col-md-12 text-right mb-2">
                            <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary"><span
                                    class="ti-plus"></span> Add Product</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Image</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $item)
                                    <tr id="row-{{ $item->id }}">
                                        <td scope="row">{{ $key + 1 }}</td>
                                        <td>
                                            <img data-toggle="tooltip" data-placement="top"
                                                title="{{ $item->product_name }}" src="{{ $item->product_thumbnail }}"
                                                class="img-fluid rounded " alt="{{ ucwords($item->product_name) }}">
                                        </td>
                                        <td>
                                            <h6 class="text-uppercase"><a href="{{ $item->product_link }}"
                                                    target="_blank">{{ ucwords($item->product_name) }}</a>
                                            </h6>
                                            <span class="text-muted">Envato ID: {{ $item->envato_id }}</span> <br>

                                            @if ($item->is_plugin)
                                                <br>
                                                <span class="badge badge-pill badge-success">Plugin of
                                                    {{ $item->parent->product_name }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="ti-more f-21"></span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item"
                                                        href="{{ route('products.edit', $item->id) }}">Edit</a>
                                                    <a class="dropdown-item delete-row" data-row-id="{{ $item->id }}"
                                                        href="javascript:;">Delete</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('sales.index') . '?product=' . $item->id }}">Sales</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        $('body').on('click', '.delete-row', function() {
            var id = $(this).data('row-id');
            swal({
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    title: "Are you sure?",
                    text: "This action will delete product and its related data.",
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var url = "{{ route('products.destroy', ':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {
                                '_token': token,
                                '_method': 'DELETE'
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    $('#row-' + id).remove();
                                }
                            }
                        });
                    }
                });
        });
    </script>
@endpush

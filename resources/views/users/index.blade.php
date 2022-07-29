@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">


            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="float-left">Users</h3>
                        <div class="col-md-12 text-right mb-2">
                            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary"><span
                                    class="ti-plus"></span> Add User</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $item)
                                    <tr id="row-{{ $item->id }}">
                                        <td scope="row">{{ $key + 1 }}</td>
                                        <td>
                                            {{ ucwords($item->name) }}
                                        </td>
                                        <td>
                                            {{ $item->email }}
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
                                                        href="{{ route('users.edit', $item->id) }}">Edit</a>
                                                    <a class="dropdown-item delete-row" data-row-id="{{ $item->id }}"
                                                        href="javascript:;">Delete</a>
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
                    text: "This action will delete user and its related data.",
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var url = "{{ route('users.destroy', ':id') }}";
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

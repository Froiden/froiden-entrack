@extends('layouts.app')

@section('content')
<div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>Add User</h3></div>

                    <div class="card-body">
                        <form class="ajax-form" method="POST" id="create-form">
                            @csrf

                            <div class="form-group">
                                <label for="product_name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" >
                            </div>

                            <div class="form-group">
                                <label for="product_name">Email</label>
                                <input type="text" class="form-control" id="email" name="email" >
                            </div>

                            <div class="form-group">
                                <label for="product_name">Password</label>
                                <input type="password" class="form-control" id="password" name="password" >
                            </div>


                            <button type="button" id="save-form" class="btn btn-success">Submit</button>
                            <a href="{{ url()->previous() }}" class="btn btn-light ml-2">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script>
        $('body').on('click', '#save-form', function() {
            $.easyAjax({
                url: "{{ route('users.store') }}",
                container: '#create-form',
                type: "POST",
                redirect: true,
                data: $('#create-form').serialize()
            });
        });

    </script>
@endpush

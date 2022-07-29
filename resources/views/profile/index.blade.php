@extends('layouts.app')

@section('content')
<div class="container">
        <div class="row justify-content-center">
            
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header"><h3>Update Profile</h3></div>
    
                    <div class="card-body">
                        <form class="ajax-form" method="POST" id="create-form">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ user()->name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="product_link">Email</label>
                                        <input name="email" id="email" class="form-control" value="{{ user()->email }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="product_link">Password</label>
                                        <input name="password" id="password" class="form-control" type="password">
                                        <small>Leave blank to keep current password</small>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <button type="button" id="save-form" class="btn btn-success">Submit</button>
                            <a href="{{ url()->previous() }}" class="btn btn-light ml-1">Back</a>
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
                url: "{{ route('profile.store') }}",
                container: '#create-form',
                type: "POST",
                redirect: true,
                data: $('#create-form').serialize()
            });
        })
    </script>
@endpush
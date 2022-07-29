@extends('layouts.app')

@section('content')
<div class="container">
        <div class="row justify-content-center">
            
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>Create License</h3></div>
    
                    <div class="card-body">
                        <form class="ajax-form" method="POST" id="create-form">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="product_name">Product</label>
                                <select name="product" id="product_id" class="form-control">
                                    @foreach ($products as $item)
                                        <option value="{{ $item->id }}">{{ $item->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">Domain</label>
                                        <input type="text" class="form-control" id="domain" name="domain">
                                    </div>
                                    <div class="form-group">
                                        <label for="product_link">App URL</label>
                                        <input name="app_url" id="app_url" class="form-control">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="product_link">Purchase Code</label>
                                        <input name="purchase_code" id="purchase_code" class="form-control">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="product_link">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option
                                            value="active">Active</option>
                                            <option
                                            value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_link">License Type</label>
                                        <p class="form-control-plaintext">{{ $license->license_type }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_link">Buyer Username</label>
                                        <p class="form-control-plaintext">{{ $license->buyer_username }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_link">Purchased On</label>
                                        <p class="form-control-plaintext">{{ $license->purchased_on->format('d M, Y') }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_link">Earning</label>
                                        <p class="form-control-plaintext">${{ $license->earning }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" id="save-form" class="btn btn-success">Submit</button>
                            <a href="{{ url()->previous() }}" class="btn btn-light">Back</a>
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
                url: "{{ route('licenses.update', $license->id) }}",
                container: '#create-form',
                type: "POST",
                redirect: true,
                file: true,
                data: $('#create-form').serialize()
            });
        })
    </script>
@endpush
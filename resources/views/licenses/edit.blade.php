@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>Edit License</h3></div>

                    <div class="card-body">
                        <form class="ajax-form" method="POST" id="create-form">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="product_name">Product</label>
                                <h3><img src="{{  $license->product->product_thumbnail }}" class="img-fluid rounded"
                                        width="45"
                                         alt="{{ ucwords($license->product->product_name) }}"> {{ ucwords($license->product->product_name) }}
                                </h3>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="product_link">License Type</label>
                                        <p class="form-control-plaintext">{{ $license->license_type }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="product_link">Buyer Username</label>
                                        <p class="form-control-plaintext">{{ $license->buyer_username }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="product_link">Purchased On</label>
                                        <p class="form-control-plaintext">{{ $license->purchased_on->format('d M, Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="product_link">Earning</label>
                                        <p class="form-control-plaintext">${{ $license->earning }}</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                
                                    <div class="form-group">
                                        <label for="product_link">App URL</label>
                                        <input name="app_url" id="app_url" class="form-control"
                                               value="{{ $license->app_url }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="product_link">Purchase Code</label>
                                        <input name="purchase_code" id="purchase_code" class="form-control readonly" readonly
                                               value="{{ $license->purchase_code }}">
                                    </div>

                                </div>
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

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Verify Envato License</h3>
                    </div>

                    <div class="card-body">
                        <form class="ajax-form" method="POST" id="create-form" autocomplete="off">
                            @csrf

                            <div class="form-group">
                                <label for="product_name">Purchase Code</label>
                                <input type="text" class="form-control form-control-lg" id="purchase_code" required
                                    name="purchase_code">
                            </div>

                            <button type="button" id="save-form" class="btn btn-success">Verify</button>
                            <a href="{{ route('licenses.index') }}" class="btn btn-light ml-2">Back</a>
                        </form>

                        <div id="verification-response"></div>

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
                url: "{{ route('licenses.verifyCode') }}",
                container: '#create-form',
                type: "POST",
                data: $('#create-form').serialize(),
                success: function(response) {
                    $('#verification-response').html(response.html);
                }
            });
        })

        $('body').on('click', '#save-license', function() {
            $.easyAjax({
                url: "{{ route('licenses.store') }}",
                container: '#create-form',
                type: "POST",
                redirect: true,
                data: $('#license-form').serialize()
            });
        })
    </script>
@endpush

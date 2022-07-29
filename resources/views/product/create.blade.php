@extends('layouts.app')

@section('content')
<div class="container">
        <div class="row justify-content-center">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3>Add Product</h3></div>

                    <div class="card-body">
                        <form class="ajax-form" method="POST" id="create-form">
                            @csrf

                            <div class="form-group">
                                <label for="product_name">Envato ID</label>
                                <input type="number" min="0" class="form-control" id="envato_id" name="envato_id" >
                            </div>

                            <div class="form-group">
                                <label for="product_name" class="mr-2">Is Add On?</label>

                                <label class="radio-inline mr-1">
                                    <input type="radio" value="0" name="is_plugin" checked> No
                                </label>

                                <label class="radio-inline">
                                    <input type="radio" value="1" name="is_plugin"> Yes
                                </label>
                            </div>

                            <div class="form-group plugin-block d-none">
                                <label for="product_name" class="form-text">Plugin of Product</label>
                                <select name="parent_product_id" id="parent_product_id" class="form-control">
                                    @foreach ($products as $item)
                                        <option value="{{ $item->id }}">{{ ucwords($item->product_name) }}</option>
                                    @endforeach
                                </select>
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
                url: "{{ route('products.store') }}",
                container: '#create-form',
                type: "POST",
                redirect: true,
                messagePosition: "inline",
                file: true,
                data: $('#create-form').serialize()
            });
        });

        $('input[type=radio][name=is_plugin]').change(function() {
            if (this.value === '1') {
                $('.plugin-block').removeClass('d-none');
            }
            else if (this.value === '0') {
                $('.plugin-block').addClass('d-none');
            }
        });
    </script>
@endpush

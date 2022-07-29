@extends('layouts.app')

@push('head-script')
    <link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Data updates every 1 minute automatically.
                </div>

                <div class="dropdown float-left">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Toggle Products
                    </button>
                    <form class="dropdown-menu" id="toggle-products-form" aria-labelledby="dropdownMenuButton">
                        @csrf
                        @foreach ($products as $item)
                            <label class="dropdown-item"><input
                                    type="checkbox" data-product-id="{{ $item->id }}" checked class="toggle-products" name="products[]" value="{{ $item->id }}">
                                {{ ucwords($item->product_name) }}</label>
                        @endforeach
                    </form>
                </div>

                <button type="button" class="btn btn-outline-secondary mb-3 ml-3" onclick="openFullscreen();">Full Screen <i
                        class="fa fa-expand ml-1"></i></button>

                <div class="row" id="sale-count">
                    @foreach ($products as $item)
                        <div class="col-md-4  col-lg-3" id="product-{{ $item->id }}">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <img src="{{ $item->product_thumbnail }}" class="img-fluid rounded"
                                                alt="{{ ucwords($item->product_name) }}" data-toggle="tooltip"
                                                data-placement="top" title="{{ ucwords($item->product_name) }}">
                                        </div>
                                        <div class="col-7">
                                            @if (!is_null($item->rating))
                                                <p class="card-text mb-0 f-21" data-toggle="tooltip" data-placement="top"
                                                    title="{{ $item->rating_count }} Ratings"><i
                                                        class="fa fa-star text-warning"></i>
                                                    {{ number_format($item->rating, 1) }}</p>
                                            @endif
                                            <h4 class="card-text text-dark mt-2">{{ $item->number_of_sales }}
                                                Sales</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>
@endsection

@push('footer-script')
    <script>
        var loadSaleCount = function() {
            $.easyAjax({
                url: "{{ route('sales.leanBack') }}",
                type: "POST",
                data: $('#toggle-products-form').serialize(),
                success: function(response) {
                    $('#sale-count').html(response.html)
                }

            });
        }
        setInterval(loadSaleCount, 60000);

        $('body').on('click', '.toggle-products', function() {
            var pId = $(this).data('product-id');
            $('#sale-count').find('#product-'+pId).toggleClass('d-none');
        });
    </script>
    <script>
        var elem = document.getElementById("sale-count");

        function openFullscreen() {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                /* IE11 */
                elem.msRequestFullscreen();
            }
        }
    </script>
@endpush

@extends('layouts.app')

@push('head-script')
@endpush

@section('content')
<div class="container">
        <div class="row justify-content-center">

            <div class="col-md-6 text-center text-black-50 text-capitalize">
                <div class="card text-white bg-primary mb-3" >
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">Current Month ({{$keys[0]}})</h5>
                        <h3 class="card-text">$ {{ $sums[0] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 text-center">
                <div class="card text-white bg-success mb-3" >
                    <div class="card-body">
                        <h5 class="card-title">Highest Earning ({{$highestEarningMonth}})</h5>
                        <h3 class="card-text">$ {{ $highestEarningAmount }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-12">

                <div class="card shadow-sm">
                    <div class="card-header"><h3>$ Earning Chart</h3></div>

                    <div class="card-body">
                        <form class="ajax-form" method="GET" id="create-form" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="product" id="product_id" class="form-control">
                                            <option value="">Show All Products</option>
                                            @foreach ($products as $item)
                                                <option
                                                @if(request()->filled('product') && request()->input('product') == $item->id) selected @endif
                                                value="{{ $item->id }}">{{ ucwords($item->product_name) }}</option>
                                            @endforeach
                                            <option value="other"
                                                @if(request()->filled('product') && request()->input('product') == 'other') selected @endif
                                            >Others</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" class="input-sm form-control" name="from_date" id="from_date"
                                        @if(request()->filled('from_date'))
                                            value="{{ request()->input('from_date') }}"
                                        @else
                                            value="{{ $fromDate }}"
                                        @endif

                                        placeholder="From Date" />
                                        <span class="input-group-addon ml-1 mr-1 pt-2"> <i class="ti-arrows-horizontal"></i> </span>
                                        <input type="text" class="input-sm form-control"

                                        @if(request()->filled('from_date'))
                                            value="{{ request()->input('to_date') }}"
                                        @else
                                            value="{{ $toDate }}"
                                        @endif

                                        name="to_date" id="to_date" placeholder="To Date" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="button" id="apply-filter" class="btn btn-primary">Apply Filter</button>

                                        @if(request()->input('product') != '' || request()->input('code') != '' || request()->input('from_date') != '' || request()->input('to_date') != '')
                                                <button type="button" id="reset-filter" class="btn btn-danger ml-2">Reset Filter</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="mt-3">
                            {!! $chart->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
<script src="{{ asset('plugins/highcharts/highcharts.js') }}" charset="utf-8"></script>

{!! $chart->script() !!}

<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            todayBtn: true,
            clearBtn: true
        });

        $('#datepicker').datepicker({
            todayBtn: true,
            clearBtn: true,
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        $('body').on('click', '#apply-filter', function() {
            let productId = $('#product_id').val();
            let fromDate = $('#from_date').val();
            let toDate = $('#to_date').val();
            let currentUrl = "{{ route('sales.earningChart') }}";

            let productParam = "product="+productId;
            let fromDateParam = "from_date="+fromDate;
            let toDateParam = "to_date="+toDate;
            let url = currentUrl+'?'+[productParam, fromDateParam, toDateParam].filter(Boolean).join('&');
            window.location = url;
        });

        $('body').on('click', '#reset-filter', function() {
            let currentUrl = "{{ route('sales.earningChart') }}";
            let pageParam = "page=1";
            let productParam = "product=";
            let dateParam = "date=";
            let fromDateParam = "from_date=";
            let toDateParam = "to_date=";
            let url = currentUrl+'?'+[pageParam, productParam, dateParam, fromDateParam, toDateParam].filter(Boolean).join('&');
            window.location = url;
        });
</script>
@endpush

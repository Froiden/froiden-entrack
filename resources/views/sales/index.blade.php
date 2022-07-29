@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-2" data-toggle="tooltip" data-placement="top" title="Total Sales & Earnings">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <img src="{{  asset('envato.png') }}" class="img-fluid rounded">
                        </div>
                        <div class="col-7">
                            <h6 class="card-text">{{ $totalSales }}</h6>
                            <small class="card-text text-muted">${{ $totalEarning }}</small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @foreach ($products as $item)
        @if(!request()->filled('product'))
        <div class="col-md-2" data-toggle="tooltip" data-placement="top" title="{{ ucwords($item->product_name) }}">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <img src="{{  $item->product_thumbnail }}" class="img-fluid rounded"
                                alt="{{ ucwords($item->product_name) }}">
                        </div>
                        <div class="col-7">
                            <h6 class="card-text">{{ ($item->sales_count - $item->refunds_count) }}</h6>
                            <small class="card-text text-muted">${{ $item->earning }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif(request()->get('product') == $item->id)
        <div class="col-md-2" data-toggle="tooltip" data-placement="top" title="{{ ucwords($item->product_name) }}">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <img src="{{  $item->product_thumbnail }}" class="img-fluid rounded"
                                alt="{{ ucwords($item->product_name) }}">
                        </div>
                        <div class="col-7">
                            <h6 class="card-text">{{ ($item->sales_count - $item->refunds_count) }}</h6>
                            <small class="card-text text-muted">${{ $item->earning }}</small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach

        @if(!request()->filled('product') || (request()->get('product') == 'other'))
        <div class="col-md-2" data-toggle="tooltip" data-placement="top" title="Deleted Products">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <i class="fa fa-trash f-21"></i>
                        </div>
                        <div class="col-7">
                            <h6 class="card-text">{{ $otherCount }}</h6>
                            <small class="card-text text-muted">${{ $otherSum }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3>Sales</h3>
                </div>

                <div class="card-body">
                    <h5><i class="ti-filter"></i> Filters</h5>
                    <form class="ajax-form" method="GET" id="create-form" autocomplete="off">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="product" id="product_id" class="form-control">
                                        <option value="">Show All Products</option>
                                        @foreach ($products as $item)
                                        <option @if(request()->filled('product') && request()->input('product') ==
                                            $item->id) selected @endif
                                            value="{{ $item->id }}">{{ ucwords($item->product_name) }}</option>
                                        @endforeach
                                        <option value="other" @if(request()->filled('product') &&
                                            request()->input('product') == 'other') selected @endif
                                            >Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="sort_date" id="sort_date" class="form-control">
                                        <option @if(request()->filled('date') && request()->input('date') == 'desc')
                                            selected @endif
                                            value="desc">Sort by Date (DESC)</option>
                                        <option @if(request()->filled('date') && request()->input('date') == 'asc')
                                            selected @endif
                                            value="asc">Sort by Date (ASC)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="input-sm form-control" name="from_date" id="from_date"
                                        value="{{ request()->input('from_date') }}" placeholder="From Date" />
                                    <span class="input-group-addon ml-1 mr-1 pt-2"> <i class="ti-arrows-horizontal"></i>
                                    </span>
                                    <input type="text" class="input-sm form-control"
                                        value="{{ request()->input('to_date') }}" name="to_date" id="to_date"
                                        placeholder="To Date" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="button" id="apply-filter" class="btn btn-primary">Apply
                                        Filter</button>

                                    @if(request()->input('product') != '' || request()->input('code') != '' ||
                                    request()->input('from_date') != '' || request()->input('to_date') != '')
                                    <button type="button" id="reset-filter" class="btn btn-danger ml-2">Reset
                                        Filter</button>
                                    @endif
                                </div>
                            </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Image</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $count = $sales->firstItem();
                                @endphp

                                @foreach ($sales as $key=>$item)
                                <tr id="row-{{ $item->id }}">
                                    <td scope="row">{{ $count }}</td>
                                    <td>
                                        @if(!is_null($item->product))
                                        <img src="{{  $item->product->product_thumbnail }}" class="img-fluid rounded"
                                            alt="{{ ucwords($item->product->product_name) }}">
                                        @else
                                        <img src="{{  asset('froiden.jpg') }}" class="img-fluid rounded"
                                            alt="{{ ucwords($item->detail) }}">
                                        @endif
                                    </td>
                                    <td>
                                        @if(!is_null($item->product))
                                        <h6 class="text-uppercase">{{ ucwords($item->product->product_name) }}</h6>
                                        @else
                                        <h6 class="text-uppercase">{{ ucwords($item->detail) }}</h6>
                                        @endif
                                        <span class="text-muted">Order ID: {{ $item->order_id }}</span>

                                        @if ($item->refunded())
                                            <span class="badge badge-danger">Refunded</span>                                            
                                        @elseif ($item->reversed())
                                            <span class="badge badge-danger">Reversed</span>                                            
                                        @endif

                                    </td>
                                    <td>${{ $item->sums }}</td>
                                    <td>{{ $item->date->timezone(envatoSetting()->timezone)->format('d M, Y h:i A') }}</td>
                                    <td>{{ (!is_null($item->other_party_country)) ? $item->other_party_country.', '.$item->other_party_city : '--' }}</td>
                                </tr>
                                @php
                                $count = $count+1;
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        @if(!request()->filled('product'))
                        {{ $sales->links() }}
                        @else
                        {{ $sales->appends(['product' => request()->input('product')])->links() }}
                        @endif
                    </div>

                    <div class="text-muted col-md-12">
                        Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }}
                        of total {{$sales->total()}} entries
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('footer-script')
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
            let sortDate = $('#sort_date').val();
            let fromDate = $('#from_date').val();
            let toDate = $('#to_date').val();
            let currentUrl = "{{ route('sales.index') }}";
            let pageParam = "page=1";
            let productParam = "product="+productId;
            let dateParam = "date="+sortDate;
            let fromDateParam = "from_date="+fromDate;
            let toDateParam = "to_date="+toDate;
            let url = currentUrl+'?'+[pageParam, productParam, dateParam, fromDateParam, toDateParam].filter(Boolean).join('&');
            window.location = url;
        });

        $('body').on('click', '#reset-filter', function() {
            let currentUrl = "{{ route('sales.index') }}";
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
@extends('layouts.app')

@push('head-script')
    <link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col">
                        <a href="{{ route('products.index') }}" class="nounderline">
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h5 class="card-title fa-1x text-secondary">Total Products</h5>
                                    <h5 class="card-text text-primary">{{ $totalProducts }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('sales.index') }}" class="nounderline">
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h5 class="card-title fa-1x text-secondary">Total Sales</h5>
                                    <h5 class="card-text text-primary">{{ $totalSales }}</h5>
                                </div>
                            </div>
                        </a>

                    </div>
                    <div class="w-100 d-lg-none"></div>
                    <div class="col">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <h5 class="card-title fa-1x text-secondary">Today Sales</h5>
                                <h5 class="card-text text-primary">{{ count($todaySales) }}
                                    @php
                                        $saleDifference = count($todaySales) - count($yesterDaySales);
                                    @endphp

                                    @if ($saleDifference > 0)
                                        <span class="text-success f-11 ml-2" data-toggle="tooltip" data-placement="top"
                                            title="Difference from yesterday">{{ $saleDifference }}<i
                                                class="fa fa-arrow-up ml-1"></i></span>
                                    @else
                                        <span class="text-danger f-11 ml-2" data-toggle="tooltip" data-placement="top"
                                            title="Difference from yesterday">{{ $saleDifference }}<i
                                                class="fa fa-arrow-down ml-1"></i></span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <h5 class="card-title fa-1x text-secondary">
                                    {{ now(envatoSetting()->timezone)->format('F') }} Sales</h5>
                                <h5 class="card-text text-primary">{{ count($monthSales) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <a href="{{ route('licenses.index') }}" class="nounderline">
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h5 class="card-title fa-1x text-secondary">Total Licenses</h5>
                                    <h5 class="card-text text-primary">{{ $totalLicenses }}</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    @foreach ($products as $item)
                        <div class="col-md-4 col-lg-3">
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-5">
                                            <img src="{{ $item->product_thumbnail }}" class="img-fluid rounded"
                                                alt="{{ ucwords($item->product_name) }}" data-toggle="tooltip"
                                                data-placement="top" title="{{ ucwords($item->product_name) }}">
                                        </div>
                                        <div class="col-7">
                                            @if (!is_null($item->rating))
                                                <p class="card-text mb-0" data-toggle="tooltip" data-placement="top"
                                                    title="{{ $item->rating_count }} Ratings"><i
                                                        class="fa fa-star text-warning"></i>
                                                    {{ number_format($item->rating, 1) }}</p>
                                            @endif
                                            <small class="card-text text-muted">{{ $item->number_of_sales }}
                                                Sales</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Today's Sales</h5>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count = 1;
                                    @endphp

                                    @forelse ($todaySales as $key => $item)
                                        <tr id="row-{{ $item->id }}">
                                            <td scope="row">{{ $count }}</td>
                                            <td>
                                                @if (!is_null($item->product))
                                                    <div>{{ ucwords($item->product->product_name) }}</div>
                                                @else
                                                    <div>{{ ucwords($item->detail) }}<div>
                                                @endif
                                                <span class="text-muted f-11">Order ID: {{ $item->order_id }}</span>
                                            </td>
                                            <td>${{ $item->sums }}</td>
                                            <td>{{ $item->date->timezone('Asia/Kolkata')->format('d M, Y h:i A') }}
                                            </td>
                                        </tr>
                                        @php
                                            $count = $count + 1;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="4">No Record Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 order-md-2 mb-4">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <img src="{{ $envatoSetting->image }}" alt="">
                                </div>
                                <div class="align-self-center fa-lg">
                                    <h6 class="text-right">{{ $envatoSetting->envato_username }}</h6>
                                    <small
                                        class="text-muted">{{ $envatoSetting->firstname . ' ' . $envatoSetting->surname }}</small>
                                </div>
                            </li>
                            <li class="list-group-item lh-condensed">
                                @foreach ($userBadges as $item)
                                    <span>
                                        <img src="{{ $item->image }}" width="30" height="34" class="mr-1 my-1"
                                            data-toggle="tooltip" data-placement="top"
                                            title="{{ ucwords($item->label) }}">
                                    </span>
                                @endforeach
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Location</h6>
                                </div>
                                <span
                                    class="text-muted">{{ $envatoSetting->location . ', ' . $envatoSetting->country }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Followers</h6>
                                </div>
                                <span class="text-muted">{{ $envatoSetting->followers }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <h6 class="my-0">Balance</h6>
                                <div><strong class="blurry-text">${{ $envatoSetting->balance }}</strong> <a
                                        href="javascript:;" class="text-secondary toggle-balance"><i
                                            class="fa fa-eye-slash"></i></a></div>
                            </li>
                        </ul>

                    </div>
                    <div class="col-md-12 mb-3 mt-2">
                        <h6>Top Sales by Country</h6>
                        <ul class="list-group">
                            @forelse ($countryWiseSales as $item)
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div><span class="fi fi-{{ strtolower($item->country_code) }} mr-1"></span>
                                        {{ $item->other_party_country }}</div>
                                    <div>{{ $item->sales_count }}</div>
                                </li>
                            @empty
                                <li class="list-group-item d-flex justify-content-between lh-condensed">No Record Found</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="col-md-12 mb-3 mt-2">
                        <h6>Product Sales Distribution</h6>
                        <div id="chart_div" class="shadow-sm"></div>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection

@push('footer-script')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        $('body').on('click', '.toggle-balance', function() {
            $(this).find('i').toggleClass('fa-eye-slash fa-eye');
            $(this).parent().find('strong').toggleClass('blurry-text');
        });
    </script>
    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        google.charts.load('current', {
            'packages': ['corechart']
        });

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Product');
            data.addColumn('number', 'Sales');
            data.addRows([
                @foreach ($products as $item)
                    ["{{ ucwords($item->product_name) }}", {{ $item->number_of_sales }}],
                @endforeach
            ]);

            // Set chart options
            var options = {
                'height': 300,
                'legend': {
                    position: 'none'
                }
            };

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
@endpush

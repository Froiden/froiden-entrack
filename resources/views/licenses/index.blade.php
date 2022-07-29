@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">


            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="float-left">Licenses</h3>
                        <div class="text-right mb-2">
                            <a href="{{ route('licenses.verify') }}" class="btn btn-sm btn-primary"><span
                                    class="ti-check"></span> Verify
                                License</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <h5><i class="ti-filter"></i> Filters</h5>
                        <form class="ajax-form" method="GET" id="create-form" autocomplete="off">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select name="product" id="product_id" class="form-control">
                                            <option value="">Show All Products</option>
                                            @foreach ($products as $item)
                                                <option @if (request()->filled('product') && request()->input('product') == $item->id) selected @endif
                                                    value="{{ $item->id }}">{{ ucwords($item->product_name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="purchase_code" id="purchase_code"
                                            value="{{ request()->input('code') }}" placeholder="Purchase Code"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" id="domain_url" name="url"
                                            value="{{ request()->input('domain') }}" placeholder="Domain/App URL"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" id="username" name="username"
                                            value="{{ request()->input('username') }}" placeholder="Buyer Username"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="supported" id="supported" class="form-control">
                                            <option value="">Supported + Not Supported</option>
                                            <option @if (request()->filled('supported') && request()->input('supported') == 'yes') selected @endif value="yes">Supported
                                            </option>
                                            <option @if (request()->filled('supported') && request()->input('supported') == 'no') selected @endif value="no">Not
                                                Supported
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button" id="apply-filter" class="btn btn-primary">Apply
                                            Filter
                                        </button>

                                        @if (request()->input('product') != '' || request()->input('code') != '' || request()->input('domain') != '' || request()->input('username') != '' || request()->input('version') != '' || request()->input('rating') != '')
                                            <button type="button" id="reset-filter" class="btn btn-danger ml-2">Reset
                                                Filter
                                            </button>
                                        @endif
                                    </div>


                                </div>
                        </form>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">APP URL</th>
                                    <th scope="col">Purchase Code</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $count = $licenses->firstItem();
                                @endphp

                                @foreach ($licenses as $key => $item)
                                    <tr id="row-{{ $item->id }}">
                                        <td scope="row">{{ $count }}</td>
                                        <td>
                                            <img src="{{ $item->product->product_thumbnail }}" class="img-fluid rounded"
                                                alt="{{ ucwords($item->product->product_name) }}">
                                        </td>
                                        <td>
                                            @if (!is_null($item->app_url) && $item->app_url != '')
                                                <a href="{{ $item->app_url }}" target="_blank">
                                                    {{ $item->app_url }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <h6 id="code-{{ $key }}" class="copy clippy"
                                                data-clipboard-target="#code-{{ $key }}">
                                                {{ $item->purchase_code }}<i class="fa fa-copy"></i>
                                            </h6>

                                            <span class="text-muted">Buyer: <a
                                                    href="https://codecanyon.net/user/{{ $item->buyer_username }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer">{{ $item->buyer_username }}</a></span>
                                            &nbsp;
                                            <span class="text-muted">Purchased On:
                                                {{ $item->purchased_on->format('d M, Y') }}</span>

                                            @if ($item->supported_until->isPast())
                                                <h6><span class="badge badge-pill badge-danger">Support Expired:
                                                        {{ $item->supported_until->format('d M, Y') }}</span></h6>
                                            @else
                                                <p>
                                                    @if ($item->supported_until->diff(\Carbon\Carbon::now())->days > 1 && $item->supported_until->diff(\Carbon\Carbon::now())->days < 7)
                                                        <span class="badge badge-pill badge-info">Expiry:
                                                            {{ $item->supported_until->format('d M, Y') }}</span>
                                                    @elseif($item->supported_until->diff(\Carbon\Carbon::now())->days > 1)
                                                        <span class="badge badge-pill badge-success">Expiry:
                                                            {{ $item->supported_until->format('d M, Y') }}</span>
                                                    @endif

                                                    <span
                                                        class="badge badge-pill badge-success ml-15">{{ $item->supported_until->diff(\Carbon\Carbon::now())->days < 1? 'TODAY': 'in ' . $item->supported_until->diffInDays(\Carbon\Carbon::now()) . ' days' }}
                                                    </span>
                                                </p>
                                            @endif
                                            @if ($item->license_type == 'Extended License')
                                                <span class="badge badge-pill badge-primary">Extended</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    id="dropdownMenuButton{{ $key }}" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <span class="ti-more f-21"></span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right"
                                                    aria-labelledby="dropdownMenuButton{{ $key }}">
                                                    <a class="dropdown-item"
                                                        href="{{ route('licenses.edit', $item->id) }}">Edit</a>
                                                    <a class="dropdown-item delete-row" data-row-id="{{ $item->id }}"
                                                        href="javascript:;">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $count = $count + 1;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>

                        <div class="col-md-12">
                            @if (!request()->filled('product') && !request()->filled('version') && !request()->input('code') && !request()->input('domain') && !request()->input('username') && !request()->input('supported') && !request()->input('rating'))
                                {{ $licenses->links() }}
                            @else
                                {{ $licenses->appends([
                                        'product' => request()->input('product'),
                                        'version' => request()->input('version'),
                                        'code' => request()->input('code'),
                                        'domain' => request()->input('domain'),
                                        'username' => request()->input('username'),
                                        'supported' => request()->input('supported'),
                                        'rating' => request()->input('rating'),
                                    ])->links() }}
                            @endif

                        </div>

                        <div class="text-muted col-md-12">
                            Showing {{ $licenses->firstItem() }} to {{ $licenses->lastItem() }}
                            of total {{ $licenses->total() }} entries
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
        let currentUrl = "{{ route('licenses.index') }}";
        let pageParam = "page=1";

        $('body').on('click', '#apply-filter', function() {
            let productId = $('#product_id').val();
            let purchaseCode = $('#purchase_code').val();
            let domainUrl = $('#domain_url').val();
            let username = $('#username').val();
            let version = $('#version').val();
            let supported = $('#supported').val();
            let rating = $('#rating').val();


            let productParam = "product=" + productId;
            let codeParam = "code=" + purchaseCode;
            let domainParam = "domain=" + domainUrl;
            let usernameParam = "username=" + username;
            let versionParam = "version=" + version;
            let supportedParam = "supported=" + supported;
            let ratingParam = "rating=" + rating;
            let url = currentUrl + '?' + [pageParam, productParam, codeParam, domainParam, usernameParam,
                versionParam, supportedParam, ratingParam
            ].filter(Boolean).join('&');
            window.location = url;
        });

        $('body').on('click', '#reset-filter', function() {

            let productParam = "product=";
            let codeParam = "code=";
            let domainParam = "domain=";
            let usernameParam = "username=";
            let versionParam = "version=";
            let supportedParam = "supported=";
            let ratingParam = "rating=";
            let url = currentUrl + '?' + [pageParam, productParam, codeParam, domainParam, usernameParam,
                versionParam, supportedParam, ratingParam
            ].filter(Boolean).join('&');
            window.location = url;
        });

        function versionFilter(version, productId) {
            let purchaseCode = $('#purchase_code').val();
            let domainUrl = $('#domain_url').val();
            let username = $('#username').val();
            let supported = $('#supported').val();
            let rating = $('#rating').val();

            let currentUrl = "{{ route('licenses.index') }}";
            let pageParam = "page=1";
            let codeParam = "code=" + purchaseCode;
            let domainParam = "domain=" + domainUrl;
            let usernameParam = "username=" + username;
            let supportedParam = "supported=" + supported;
            let ratingParam = "rating=" + rating;

            let versionParam = "version=" + version;
            let productParam = "product=" + productId;
            let url = currentUrl + '?' + [pageParam, productParam, codeParam, domainParam, usernameParam, versionParam,
                supportedParam, ratingParam
            ].filter(Boolean).join('&');
            window.location = url;
        }

        $('body').on('click', '.delete-row', function() {
            var id = $(this).data('row-id');
            swal({
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    title: "Are you sure?",
                    text: "This action will delete License and its related data.",
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var url = "{{ route('licenses.destroy', ':id') }}";
                        url = url.replace(':id', id);

                        var token = "{{ csrf_token() }}";

                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {
                                '_token': token,
                                '_method': 'DELETE'
                            },
                            success: function(response) {
                                if (response.status == "success") {
                                    $.unblockUI();
                                    $('#row-' + id).remove();
                                }
                            }
                        });
                    }
                });
        });

        $('.clippy').tooltip({
            trigger: 'click',
            placement: 'bottom'
        });

        function setTooltip(btn, message) {
            $(btn).tooltip('hide')
                .attr('data-original-title', message)
                .tooltip('show');
        }

        function hideTooltip(btn) {
            setTimeout(function() {
                $(btn).tooltip('hide');
            }, 1000);
        }

        var clipboard = new ClipboardJS('.copy');

        clipboard.on('success', function(e) {
            setTooltip(e.trigger, 'Copied!');
            hideTooltip(e.trigger);
        });

        clipboard.on('error', function(e) {
            setTooltip(e.trigger, 'Failed!');
            hideTooltip(e.trigger);
        });
    </script>
@endpush

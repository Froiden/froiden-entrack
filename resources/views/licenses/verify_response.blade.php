<hr>
<div class="row">
    @if (!isset($response['error']))
        <form id="license-form" class="ajax-form">
            @csrf
            <input type="hidden" name="purchase_code" value="{{ $response['purchase_code'] }}">
            <input type="hidden" name="purchased_on"
                value="{{ date('Y-m-d h:i:s', strtotime($response['sold_at'])) }}">
            <input type="hidden" name="supported_until"
                value="{{ date('Y-m-d h:i:s', strtotime($response['supported_until'])) }}">
            <input type="hidden" name="license_type" value="{{ $response['license'] }}">
            <input type="hidden" name="envato_item_id" value="{{ $response['item']['id'] }}">
            <input type="hidden" name="buyer_username" value="{{ $response['buyer'] }}">
            <input type="hidden" name="earning" value="{{ $response['amount'] }}">
        </form>
        <div class="col-md-4 mb-3">
            Product: {{ $response['item']['name'] }}
        </div>
        <div class="col-md-4 mb-3">
            Amount: ${{ $response['amount'] }}
        </div>
        <div class="col-md-4 mb-3">
            Username: {{ $response['buyer'] }}
        </div>
        <div class="col-md-4 mb-3">
            License Type: {{ $response['license'] }}
        </div>
        <div class="col-md-4 mb-3">
            Sold On: {{ date('d M, Y h:i A', strtotime($response['sold_at'])) }}
        </div>
        <div class="col-md-4 mb-3">
            Supported Until: {{ $response['supported_until'] }}
        </div>
        <div class="col-md-4 mb-3">
            Support Amount: ${{ $response['support_amount'] }}
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary" id="save-license">Save License Info</button>
        </div>
    @else
        <div class="col-md-12">
            <span class="text-danger">{{ $response['description'] }}</span>
        </div>
    @endif
</div>

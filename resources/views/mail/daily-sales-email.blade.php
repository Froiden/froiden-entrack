@component('mail::message')
# Envato Sales on {{ now(envatoSetting()->timezone)->subDay()->format('d M, Y') }}

@component('mail::table')
|Item     |               | Sales | Price|
|:----------- |:------------- |:--------:| --------:|
@php
    $total = 0;
    $totalEarning = 0;
@endphp
@foreach ($yesterdaySales as $item)
@if ($item->sales_count > 0)
|<img src="{{ $item->product_thumbnail }}" width="40">   | {{ ucwords($item->product_name) }} | {{ $item->sales_count }} |  ${{ $item->sales->sum('amount') }}  |
@endif
@php
    $total = $total+$item->sales_count;
    $totalEarning = $totalEarning+$item->sales->sum('amount');
@endphp
@endforeach
| | <strong>Total</strong> | <strong>{{ $total }}</strong>| <strong>${{ $totalEarning }}</strong>|
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

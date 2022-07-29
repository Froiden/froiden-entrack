@foreach ($products as $item)
    <div class="col-md-4 col-lg-3 {{ !in_array($item->id, $showProducts) ? 'd-none': '' }}" id="product-{{ $item->id }}">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <img src="{{ $item->product_thumbnail }}" class="img-fluid rounded"
                            alt="{{ ucwords($item->product_name) }}" data-toggle="tooltip" data-placement="top"
                            title="{{ ucwords($item->product_name) }}">
                    </div>
                    <div class="col-7">
                        @if (!is_null($item->rating))
                            <p class="card-text mb-0 f-21" data-toggle="tooltip" data-placement="top"
                                title="{{ $item->rating_count }} Ratings"><i class="fa fa-star text-warning"></i>
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

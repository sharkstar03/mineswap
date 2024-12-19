@extends($extend_blade)
@section('title',trans('Plan'))

@section('content')

    <!-- Pricing Section Starts Here -->
    <section class="pricing-section padding-bottom padding-top">
        <div class="container">

            <ul class="nav nav-tabs nav--tabs">
                @foreach($packages as $k=> $miner)
                    <li>
                        <a href="#tab-{{$k}}" @if($k ==0)class="active" @endif data-bs-toggle="tab">{{$miner->name}}</a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach($packages as $k=> $miner)
                    <div class="tab-pane show fade @if($k ==0) active @endif" id="tab-{{$k}}">
                        <div class="row g-4 justify-content-center">
                            @foreach($miner->plans as $key => $item)


                                <div class="col-lg-4 col-sm-6">
                                    <div class="pricing-card">
                                        <div class="pricing-card__icon">
                                            <img src="{{getFile(config('location.plan.path').$item->image)}}"
                                                 alt="images">
                                        </div>
                                        <h5 class="pricing-card__title">@lang($item->name)</h5>
                                        <div class="price-body">
                                            <span>@lang('Price')</span>
                                            <h3 class="price">{{getAmount($item->price)}} @lang(config('basic.currency'))</h3>
                                        </div>
                                        <ul class="info">
                                            <li>
                                                <span class="sub-info-title">@lang('Duration')</span>
                                                <h6 class="sub-info">@lang('For') {{$item->duration}} {{$item->periodText}}</h6>
                                            </li>
                                            @if(config('basic.plan_sale_commission') == 1)
                                            <li>
                                                <span class="sub-info-title">@lang('Referral')</span>
                                                <h6 class="sub-info">{{$item->referral}}%</h6>
                                            </li>
                                            @endif
                                            <li>
                                                <span class="sub-info-title">@lang('Hashrate')</span>
                                                <h6 class="sub-info">{{$item->hash_rate_speed.' '.trans($item->hash_rate_unit)}}</h6>
                                            </li>
                                        </ul>
                                        <a href="javascript:void(0)"
                                           data-bs-backdrop='static' data-keyboard='false'
                                           data-bs-toggle="modal" data-bs-target="#addFundModal"
                                           data-id="{{$item->id}}"
                                           data-name="{{$item->name}}"
                                           data-price="{{$item->price}}"
                                           class="cmn--btn active shadow-base addFund">@lang('Buy Now')</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Pricing Section Ends Here -->



    @push('loadModal')
        <div id="addFundModal" class="modal fade addFundModal custom--modal modal-danger" tabindex="-1" role="dialog"
             data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content form-block gradient-bg">
                    <form action="{{route('user.plan-order')}}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h6 class="modal-title plan-name"></h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="white-text">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body ">
                            <div class="payment-form  deposit-preview-body ">

                                <input type="hidden" class="plan_id" name="plan_id" value="{{old('plan_id')}}" >
                                <div class="form-group mt-3">
                                    <label>@lang('Plan Price')</label>
                                    <div class="input-group input-group-lg">
                                        <input type="text" class="amount form-control form--control style--two"
                                               name="amount" value="{{old('amount')}}" readonly>
                                        <div class="input-group-append ">
                                            <span
                                                class="input-group-text form--control style--two show-currency"></span>
                                        </div>
                                    </div>
                                    <pre class="text-danger errors"></pre>
                                </div>

                                <div class="form-group mt-3">
                                    <label>@lang('Payment Type')</label>
                                    <select class="form-control form--control style--two" name="payment_type">
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        <option value="0" {{old('payment_type') == '0' ? 'select' : ''}}>@lang('Pay Via Online')</option>
                                        <option value="1" {{old('payment_type') == '1' ? 'select' : ''}}>@lang('Pay Via Fund')</option>

                                    </select>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="submit" class="btn btn--md btn--success checkCalc">@lang('Pay Now')</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endpush
@endsection


@push('script')

    <script>
        $('#loading').hide();
        "use strict";
        var id, name, price, baseSymbol, currency;
        $('.addFund').on('click', function () {
            id = $(this).data('id');
            name = $(this).data('name');
            price = $(this).data('price');
            baseSymbol = "{{config('basic.currency_symbol')}}";
            $('.plan-name').text(`${name}`);
            $('.show-currency').text("{{config('basic.currency')}}");
            $('.amount').val(price);
            $('.plan_id').val(id);
        });


        $('.close').on('click', function (e) {
            $("#addFundModal").modal("hide");
        });

    </script>


    @if(count($errors) > 0 )
        <script>
            @foreach($errors->all() as $key => $error)
            Notiflix.Notify.Failure("@lang($error)");
            @endforeach
        </script>
    @endif


@endpush

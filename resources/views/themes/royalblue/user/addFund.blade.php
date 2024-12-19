@extends($theme.'layouts.user')
@section('title',trans('Add Fund'))


@section('content')
    <div class="dashboard-section padding-top padding-bottom overflow-hidden">
        <div class="container">
            <div class="row gy-5 align-items-center">
                @foreach($gateways as $key => $gateway)
                    <div class="col-xl-2 col-lg-3 col-md-4  col-sm-6 col-6">
                        <div class="method__card padding10 text-center">

                            <div class="method__icon">
                                <img src="{{ getFile(config('location.gateway.path').$gateway->image)}}"
                                     alt="{{$gateway->name}}" class="w-100 h-100">
                            </div>

                            <button type="button"
                                    data-id="{{$gateway->id}}"
                                    data-name="{{$gateway->name}}"
                                    data-currency="{{$gateway->currency}}"
                                    data-gateway="{{$gateway->code}}"
                                    data-min_amount="{{getAmount($gateway->min_amount)}}"
                                    data-max_amount="{{getAmount($gateway->max_amount)}}"
                                    data-percent_charge="{{getAmount($gateway->percentage_charge)}}"
                                    data-fix_charge="{{getAmount($gateway->fixed_charge)}}"
                                    class="btn btn--base btn--md w-100 radius-5 addFund"
                                    data-bs-backdrop='static' data-keyboard='false'
                                    data-bs-toggle="modal" data-bs-target="#addFundModal">@lang('Pay Now')</button>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>





    @push('loadModal')
        <div id="addFundModal" class="modal fade addFundModal custom--modal modal-danger" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content form-block gradient-bg">
                    <div class="modal-header">
                        <h6 class="modal-title method-name"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="white-text">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body ">
                        <div class="payment-form  deposit-preview-body p-4">
                            @if(0 == $totalPayment)
                                <p class="title depositLimit"></p>
                                <p class="title depositCharge"></p>
                            @endif

                            <input type="hidden" class="gateway" name="gateway" value="">


                            <div class="form-group mt-3">
                                <label>@lang('Amount')</label>
                                <div class="input-group input-group-lg">
                                    <input type="text" class="amount form-control form--control style--two" name="amount"
                                           @if($totalPayment != null) value="{{$totalPayment}}"  readonly @endif>
                                    <div class="input-group-append ">
                                        <span class="input-group-text form--control style--two show-currency"></span>
                                    </div>
                                </div>
                                <pre class="text-danger errors"></pre>
                            </div>


                        </div>

                        <div class="payment-info text-center">
                            <img id="loading" src="{{asset('assets/admin/images/loading.gif')}}" alt="..."
                                 class="w-25"/>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn--md btn--success checkCalc">@lang('Next')</button>
                    </div>

                </div>
            </div>
        </div>
    @endpush


@endsection



@push('script')

    <script>
        $('#loading').hide();
        "use strict";
        var id, minAmount, maxAmount, baseSymbol, fixCharge, percentCharge, currency, amount, gateway;
        $('.addFund').on('click', function () {
            id = $(this).data('id');
            gateway = $(this).data('gateway');
            minAmount = $(this).data('min_amount');
            maxAmount = $(this).data('max_amount');
            baseSymbol = "{{config('basic.currency')}}";
            fixCharge = $(this).data('fix_charge');
            percentCharge = $(this).data('percent_charge');
            currency = $(this).data('currency');
            $('.depositLimit').text(`@lang('Transaction Limit:') ${minAmount} - ${maxAmount}  ${baseSymbol}`);

            var depositCharge = `@lang('Charge:') ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' + percentCharge + ' % ' : ''}`;
            $('.depositCharge').text(depositCharge);
            $('.method-name').text(`@lang('Payment By') ${$(this).data('name')} - ${currency}`);
            $('.show-currency').text("{{config('basic.currency')}}");
            $('.gateway').val(gateway);
            // amount
        });


        $(".checkCalc").on('click', function () {
            $('.payment-form').addClass('d-none');

            $('#loading').show();
            $('.modal-backdrop.fade').addClass('show');
            amount = $('.amount').val();
            $.ajax({
                url: "{{route('user.addFund.request')}}",
                type: 'POST',
                data: {
                    amount,
                    gateway
                },
                success(data) {

                    $('.payment-form').addClass('d-none');
                    $('.checkCalc').closest('.modal-footer').addClass('d-none');

                    var htmlData = `
                     <ul class="list-group text-center text-white">
                        <li class="list-group-item bg-transparent">
                            <img src="${data.gateway_image}" class="mx-auto w-25"/>
                        </li>
                         <li class="list-group-item bg-transparent deposit-group">
                                <h6 class="title">@lang('Amount'):</h6>
                                <div>
                                <strong class="text-white">${data.amount}</strong>
                                </div>
                        </li>

                        <li class="list-group-item bg-transparent deposit-group">
                                <h6 class="title">@lang('Charge'):</h6>
                                <div>
                                <strong class="text-white">${data.charge}</strong>
                                </div>
                        </li>

                         <li class="list-group-item bg-transparent deposit-group">
                                <h6 class="title">@lang('Payable'):</h6>
                                <div>
                                <strong class="text-white">${data.payable}</strong>
                                </div>
                        </li>

                         <li class="list-group-item bg-transparent deposit-group">
                                <h6 class="title">@lang('Conversion Rate'):</h6>
                                <div>
                                <strong class="text-white">${data.conversion_rate}</strong>
                                </div>
                        </li>

                         <li class="list-group-item bg-transparent deposit-group">
                                <h6 class="title">${data.in_currency}:</h6>
                                <div>
                                <strong class="text-white">${data.in_amount}</strong>
                                </div>
                        </li>


                        ${(data.isCrypto == true) ? `
                        <li class="list-group-item bg-transparent deposit-group">
                                <div>
                                <strong class="text-white">${data.conversion_with}</strong>
                                </div>
                        </li>
                        ` : ``}

                        <li class="list-group-item bg-transparent deposit-group">
                        <a href="${data.payment_url}" class="btn btn--base active justify-content-center btn--block mx-auto addFund ">@lang('Pay Now')</a>
                        </li>
                        </ul>`;

                    $('.payment-info').html(htmlData)
                },
                complete: function () {
                    $('#loading').hide();
                },
                error(err) {
                    var errors = err.responseJSON;
                    for (var obj in errors) {
                        $('.errors').text(`${errors[obj]}`)
                    }

                    $('.payment-form').removeClass('d-none');
                }
            });
        });


        $('.close').on('click', function (e) {
            $('#loading').hide();
            $('.payment-form').removeClass('d-none');
            $('.checkCalc').closest('.modal-footer').removeClass('d-none');
            $('.payment-info').html(``)
            $('.amount').val(``);
            $('.gateway').val('');
            $("#addFundModal").modal("hide");
        });

    </script>
@endpush


@extends($theme.'layouts.user')
@section('title', trans($title))
@section('content')

    <!-- Dashboard Starts Here -->
    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center g-4">

                @foreach($wallets as $wallet)
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-10">
                        <div class="dashboard__card dashboard__card-{{strtoupper($wallet->code)}}">
                            <div class="w-100 text-center">
                                <h4 class="title text-white mb-4">{{trans(optional($wallet->miner)->name)}} @lang('Wallet')</h4>

                            </div>
                            <div class="dashboard__card-content">
                                <h2 class="price">{{getAmount($wallet->balance,8)}}</h2>
                                <p class="info">{{$wallet->code}} @lang('Balance')</p>
                            </div>
                            <div class="dashboard__card-icon dashboard__card-icon-{{strtoupper($wallet->code)}}">
                                @if(file_exists('assets/crypto/'.strtoupper($wallet->code).'.png') )
                                    <img src="{{asset('assets/crypto/'.strtoupper($wallet->code).'.png')}}" alt="{{$wallet->code}}" >
                                @else
                                    <i class="ci ci-{{strtolower($wallet->code)}}"></i>
                                @endif
                            </div>

                            <div class="deposit-preview-body px-1 py-4">

                                <div class="deposit-group">
                                    <h6 class="title">@lang('Address'):</h6>
                                    <div class="value">
                                        @if($wallet->wallet_address)
                                            <span class="walletAddress"> {{$wallet->wallet_address}} </span>
                                        @else
                                            <span class="emptyAddress">@lang("Please update your wallet address")</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="deposit-group">
                                    <h6 class="title">@lang('Min. Limit') :</h6>
                                    <div class="value">
                                        {{getAmount(optional($wallet->miner)->minimum_amount,8)}} {{strtoupper($wallet->code)}}
                                    </div>
                                </div>
                                <div class="deposit-group">
                                    <h6 class="title">@lang('Max. Limit') :</h6>
                                    <div class="value">
                                        {{getAmount(optional($wallet->miner)->maximum_amount,8)}}  {{strtoupper($wallet->code)}}
                                    </div>
                                </div>


                                <div class="text-center mt-4">
                                    @if($wallet->wallet_address)
                                    <a href="javascript:void(0)"
                                       data-bs-backdrop='static' data-keyboard='false'
                                       data-bs-toggle="modal" data-bs-target="#addFundModal"
                                       data-wallet="{{optional($wallet->miner)->name}}"
                                       data-code="{{$wallet->code}}"
                                       data-id="{{$wallet->id}}"

                                       class="btn btn--base justify-content-center btn--block addFund">@lang('Payout Now')</a>
                                    @else
                                        <a href="{{route('user.wallet')}}" class="btn btn--base justify-content-center btn--block">@lang('Upgrade Wallet Address')</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>

        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>
    <!-- Dashboard Ends Here -->





    @push('loadModal')
        <div id="addFundModal" class="modal fade addFundModal custom--modal modal-danger" tabindex="-1" role="dialog"
             data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content form-block gradient-bg">
                    <form action="{{route('user.payout.moneyRequest')}}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h6 class="modal-title"></h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="white-text">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body ">
                            <div class="payment-form  deposit-preview-body ">
                                <input type="hidden" class="wallet" name="wallet" value="{{old('wallet')}}" >
                                <div class="form-group mt-3">
                                    <label>@lang('Enter Amount')</label>
                                    <div class="input-group input-group-lg">
                                        <input type="text" class="amount form-control form--control style--two" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                               name="amount" value="{{old('amount')}}" placeholder="0.00">
                                        <div class="input-group-append ">
                                            <span
                                                class="input-group-text form--control  show-currency"></span>
                                        </div>
                                    </div>
                                    <pre class="text-danger errors"></pre>
                                </div>



                            </div>

                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn--md btn--danger close">@lang('Cancel')</button>
                            <button type="submit" class="btn btn--md btn--success checkCalc">@lang('Pay Now')</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endpush


@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{asset($themeTrue.'css/coinicon.css')}}">
@endpush


@push('script')

    <script>

        "use strict";
        var wallet;
        $('.addFund').on('click', function () {
            wallet = $(this).data('wallet');
            $('.modal-title').text(`Withdraw from ${wallet} wallet`)
            $('.wallet').val($(this).data('id'))
            $('.show-currency').text($(this).data('code'))
        });

        $('.close').on('click', function (e) {
            $("#addFundModal").modal("hide");
        });


    </script>
@endpush


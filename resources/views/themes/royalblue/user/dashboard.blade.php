@extends($theme.'layouts.user')
@section('title',trans('Dashboard'))
@section('content')


    <!-- Dashboard Starts Here -->
    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">

            <div class="row justify-content-center g-4">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10">
                    <div class="dashboard__card">
                        <div class="dashboard__card-content">
                            <h2 class="price"><sup>@lang(config('basic.currency_symbol'))</sup>{{$walletBalance}}</h2>
                            <p class="info">@lang(config('basic.currency')) @lang('Balance')</p>
                        </div>
                        <div class="dashboard__card-icon">
                            <img src="{{asset('assets/crypto/WALLET.png')}}" alt="...">
                        </div>
                    </div>
                </div>

                @foreach($wallets as $wallet)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10">
                            <div class="dashboard__card dashboard__card-{{strtoupper($wallet->code)}}">
                                <div class="dashboard__card-content">
                                    <h2 class="price">{{getAmount($wallet->balance,8)}}</h2>
                                    <p class="info">{{$wallet->code}} @lang('Balance')</p>
                                </div>
                                <div class="dashboard__card-icon dashboard__card-icon-{{strtoupper($wallet->code)}}">
                                    @if(file_exists('assets/crypto/'.strtoupper($wallet->code).'.png') )
                                    <img src="{{asset('assets/crypto/'.strtoupper($wallet->code).'.png')}}" alt="{{$wallet->code}}">
                                    @else
                                        <img src="{{asset('assets/crypto/.png')}}" alt="{{$wallet->code}}">
                                    @endif
                                </div>
                            </div>
                        </div>
                @endforeach
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10">
                    <div class="dashboard__card">
                        <div class="dashboard__card-content">
                            <h2 class="price">{{$referralMember}}</h2>
                            <p class="info"> @lang('Referral Member')</p>
                        </div>
                        <div class="dashboard__card-icon">
                            <img src="{{asset('assets/crypto/Users.png')}}" alt="...">
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10">
                    <div class="dashboard__card">
                        <div class="dashboard__card-content">
                            <h2 class="price"><sup>@lang(config('basic.currency_symbol'))</sup>{{getAmount($referralBonus, config('basic.fraction_number'))}}</h2>
                            <p class="info">@lang('Bonus Earned')</p>
                        </div>
                        <div class="dashboard__card-icon">
                            <img src="{{asset('assets/crypto/money.png')}}" alt="...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-50 mb-50">
                <div class="col-xl-8">
                    <div id="container" class="apexcharts-canvas"></div>
                </div>
                <div class="col-xl-4">
                    <div class="card custom--card">
                        <div class="card--header gradient-bg text-center p-3 py-sm-4 px-sm-4">
                            <h4 class="title text-start m-0">@lang("Referral link")</h4>
                        </div>
                        <div class="card--body gradient-bg p-0">
                            <div class="deposit-preview-body p-4">
                                <p>
                                    @lang("Automatically top up your account balance by sharing your referral link, Earn a percentage of whatever plan your referred user buys.")
                                </p>
                                <div class="deposit-group">
                                    <div class="input-group mb-50">
                                        <input type="text"
                                               value="{{route('register.sponsor',[Auth::user()->username])}}"
                                               class="form--control form-control style--two"
                                               id="sponsorURL"
                                               readonly>
                                        <div class="input-group-append">
                                            <span class="input-group-text form--control copytext" id="copyBoard" onclick="copyFunction()">
                                                    <i class="fa fa-copy"></i>
                                            </span>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>
    <!-- Dashboard Ends Here -->

@endsection

@push('script')

    <script src="{{asset($themeTrue.'js/apexcharts.js')}}"></script>


    <script>
        "use strict";

        var options = {
            theme: {
                mode: 'dark',
            },

            series: [
                {
                    name: "{{trans('Referral Bonus')}}",
                    color: 'rgba(255, 72, 0, 1)',
                    data: {!! $monthly['bonus']->flatten() !!}
                },
                {
                    name: "{{trans('Investment')}}",
                    color: 'rgb(0,85,255)',
                    data: {!! $monthly['invest']->flatten() !!}
                }
            ],
            chart: {
                type: 'bar',
                // height: ini,
                background: '#131e51',
                toolbar: {
                    show: false
                }

            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: {!! $monthly['bonus']->keys() !!},

            },
            yaxis: {
                title: {
                    text: ""
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                colors: ['#000'],
                y: {
                    formatter: function (val) {
                        return "{{trans($basic->currency_symbol)}}" + val + ""
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#container"), options);
        chart.render();

        function copyFunction() {
            var copyText = document.getElementById("sponsorURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.Success(`Copied: ${copyText.value}`);
        }
    </script>
@endpush

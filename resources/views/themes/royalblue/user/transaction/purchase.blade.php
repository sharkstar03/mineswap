@extends($theme.'layouts.user')
@section('title',trans($title))
@section('content')


    <!-- Dashboard Starts Here -->
    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">

            <table class="table transection__table mt-5">
                <thead>
                <tr>
                    <th>@lang('SL No.')</th>
                    <th>@lang('REF')</th>
                    <th>@lang('PLAN')</th>
                    <th>@lang('PRICE')</th>
                    <th>@lang('PROFIT') <small>( @lang('per day') )</small></th>
                    <th>@lang('TOTAL DAYS')</th>
                    <th>@lang('REMAIN DAYS')</th>
                    <th>@lang('STATUS')</th>
                    <th>@lang('MORE')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($records as $data)
                    <tr>
                        <td data-label="@lang('SL No.')"><span
                                class="transection-id">{{loopIndex($records) + $loop->index}}</span></td>
                        <td data-label="@lang('REF')">@lang($data->transaction)</td>
                        <td data-label="@lang('PLAN')">@lang(@$data->plan_info->Name)</td>
                        <td data-label="@lang('PRICE')">@lang(config('basic.currency_symbol')){{getAmount($data->price)}}</td>
                        <td data-label="@lang('PROFIT')">
                        <span class="cost text--success">
                            {{getAmount($data->maximum_profit ?? $data->minimum_profit,8)}} @lang($data->code)
                        </span>
                        </td>
                        <td data-label="@lang('TOTAL DAYS')" class="text-center">{{$data->profitable_cycle}}</td>
                        <td data-label="@lang('REMAIN DAYS')" class="text-center">{{$data->remaining_cycle}}</td>
                        <td data-label="@lang('STATUS')">
                            @if($data->status == 1)
                                <span class="badge bg--success">@lang('Active')</span>
                            @elseif($data->status == 2)
                                <span class="badge bg--danger">@lang('Complete')</span>
                            @endif
                        </td>
                        <td data-label="@lang('MORE')">
                            <button type="button" class="btn btn--primary btn--sm infoButton"
                                    data-bs-backdrop='static' data-keyboard='false'
                                    data-bs-toggle="modal" data-bs-target="#infoModal"
                                    data-plan="{{@$data->plan_info->Name}}"
                                    data-mining="{{@$data->plan_info->Mining}}"
                                    data-hashrate="{{@$data->plan_info->Hashrate}}"
                                    data-duration="{{@$data->plan_info->Duration}}"
                                    data-price="{{@$data->price}}"
                                    data-totaldays="{{@$data->profitable_cycle}}"
                                    data-remaindays="{{@$data->remaining_cycle}}"
                                    data-transaction="{{@$data->transaction}}"
                                    data-code="{{@$data->code}}"
                                    data-status="{{($data->status == 1) ? trans('Active') : trans('Complete')}}"
                                    data-profit="{{getAmount($data->maximum_profit ?? $data->minimum_profit,8)}} @lang($data->code)"
                                    data-purchase_at="{{$data->created_at->format('d M, Y H:i')}}">
                                <i class="fas fa-desktop"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="100%">{{trans('No Data Found!')}}</td>
                    </tr>
                @endforelse

                </tbody>
            </table>

            {{ $records->appends($_GET)->links($theme.'partials.pagination') }}
        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>
    <!-- Dashboard Ends Here -->


    @push('loadModal')
        <div id="infoModal" class="modal fade infoModal custom--modal modal-danger" tabindex="-1" role="dialog"
             data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content form-block gradient-bg">

                    <div class="modal-header">
                        <h6 class="modal-title plan"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="white-text">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body  gradient-bg p-0">
                        <div class="deposit-preview-body p-4">
                            <div class="deposit-group">
                                <h6 class="title">@lang("REF") :</h6>
                                <div class="value transaction">
                                </div>
                            </div>
                            <div class="deposit-group">
                                <h6 class="title">@lang("Miner") :</h6>
                                <div class="value mining">
                                </div>
                            </div>

                            <div class="deposit-group">
                                <h6 class="title">@lang('Price') :</h6>
                                <div class="value price">
                                </div>
                            </div>

                            <div class="deposit-group">
                                <h6 class="title">@lang('Profit') :</h6>
                                <div class="value profit">
                                </div>
                            </div>

                            <div class="deposit-group">
                                <h6 class="title">@lang('Hashrate') :</h6>
                                <div class="value hashrate">
                                </div>
                            </div>

                            <div class="deposit-group">
                                <h6 class="title">@lang('Total Days') :</h6>
                                <div class="value totaldays">
                                </div>
                            </div>

                            <div class="deposit-group">
                                <h6 class="title">@lang('Remain Days') :</h6>
                                <div class="value remaindays">
                                </div>
                            </div>
                            <div class="deposit-group">
                                <h6 class="title">@lang('Purchased At') :</h6>
                                <div class="value purchase_at">
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn--md btn--danger close">@lang('Close')</button>
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

        $('.infoButton').on('click', function () {
            data = $(this).data();


            $('.plan').text(`${data.plan} @lang('Details')`);
            $('.mining').text(data.mining);
            $('.price').text(`${data.price} @lang(config('basic.currency'))`);

            $('.hashrate').text(data.hashrate);
            $('.duration').text(data.duration);
            $('.totaldays').text(data.totaldays);
            $('.remaindays').text(data.remaindays);
            $('.transaction').text(data.transaction);
            $('.status').text(data.status);
            $('.profit').text(`${data.profit}`);
            $('.purchase_at').text(data.purchase_at);


        });


        $('.close').on('click', function (e) {
            $("#infoModal").modal("hide");
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

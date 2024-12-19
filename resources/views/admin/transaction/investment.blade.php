@extends('admin.layouts.app')
@section('title',trans(@$title))
@section('content')

    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <div class="row justify-content-between">
            <div class="col-md-12">
                <form action="{{route('admin.investment.search')}}" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="transaction" value="{{@request()->transaction}}" class="form-control get-trx-id"
                                       placeholder="@lang('Search for Order ID')">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" name="user_name" value="{{@request()->user_name}}" class="form-control get-username"
                                       placeholder="@lang('Username')">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="date" class="form-control" name="datetrx" id="datepicker"/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="submit" class="btn waves-effect waves-light btn-primary"><i class="fas fa-search"></i> @lang('Search')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>





    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <table class="categories-show-table table table-hover table-striped table-bordered">
                <thead  class="thead-dark">
                <tr>
                    <th>@lang('SL No.')</th>
                    <th>@lang('User')</th>
                    <th>@lang('ORDER ID')</th>
                    <th>@lang('PLAN')</th>
                    <th>@lang('MINER')</th>
                    <th>@lang('PRICE')</th>
                    <th>@lang('PROFIT') <small>( @lang('per day') )</small></th>
                    <th>@lang('TOTAL DAYS')</th>
                    <th>@lang('REMAIN DAYS')</th>
                    <th>@lang('STATUS')</th>
                    <th>@lang('MORE')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($transaction as $k => $data)
                    <tr>
                        <td data-label="@lang('No.')">{{loopIndex($transaction) + $k}}</td>
                        <td data-label="@lang('User')">
                            <a href="{{route('admin.user-edit',$data->user_id)}}">{{optional($data->user)->username}}</a>
                        </td>
                        <td data-label="@lang('ORDER ID')">@lang($data->transaction)</td>
                        <td data-label="@lang('PLAN')" class="font-weight-bold">@lang(@$data->plan_info->Name)</td>
                        <td data-label="@lang('MINER')" class="font-weight-bold">@lang(@$data->plan_info->Mining)</td>
                        <td data-label="@lang('PRICE')">@lang(config('basic.currency_symbol')){{getAmount($data->price)}}</td>
                        <td data-label="@lang('PROFIT')">
                        <span class="cost text-dark">
                            {{getAmount($data->maximum_profit ?? $data->minimum_profit,8)}} @lang($data->code)
                        </span>
                        </td>
                        <td data-label="@lang('TOTAL DAYS')" class="text-center">{{$data->profitable_cycle}}</td>
                        <td data-label="@lang('REMAIN DAYS')" class="text-center">{{$data->remaining_cycle}}</td>
                        <td data-label="@lang('STATUS')">
                            @if($data->status == 1)
                                <span class="badge badge-success badge-pill">@lang('Active')</span>
                            @elseif($data->status == 2)
                                <span class="badge badge-danger badge-pill">@lang('Complete')</span>
                            @endif
                        </td>
                        <td data-label="@lang('MORE')">
                            <button type="button" class="btn btn--primary btn--sm infoButton"
                                    data-backdrop='static' data-keyboard='false'
                                    data-toggle="modal" data-target="#infoModal"
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
                                    data-purchase_at="{{$data->created_at->format('d M, Y H:i')}}"
                            >
                                <i class="fas fa-desktop"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-danger" colspan="8">@lang('No Data Found!')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $transaction->links('partials.pagination') }}
        </div>
    </div>



    <div id="infoModal" class="modal fade infoModal  modal-danger" tabindex="-1" role="dialog"
         data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content  ">

                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title plan"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>

                <div class="modal-body  ">

                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang("REF") :
                            <span class="transaction"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang("Miner") :
                            <span class="mining"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Price') :
                            <span class="price"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang("Profit") :
                            <span class="profit"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang("Hashrate") :
                            <span class="hashrate"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Total Days') :
                            <span class="totaldays"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Remain Days') :
                            <span class="remaindays"></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Purchased At') :
                            <span class="purchase_at"></span>
                        </li>

                    </ul>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
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

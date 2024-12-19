@extends($theme.'layouts.user')
@section('title',trans($title))
@section('content')
    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">
            <div class="card gradient-bg form-block p-0 br-4">
                <div class="card-body">

                    <form action="{{ route('user.payout.history.search') }}" method="get">
                        <div class="row justify-content-between">
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <input type="text" name="name" value="{{@request()->name}}"
                                           class="form-control form--control style--two"
                                           placeholder="@lang('Type Here')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <select name="status" class="form-control  form--control style--two">
                                        <option value="">@lang('All Payment')</option>
                                        <option value="1"
                                                @if(@request()->status == '1') selected @endif>@lang('Pending Payment')</option>
                                        <option value="2"
                                                @if(@request()->status == '2') selected @endif>@lang('Complete Payment')</option>
                                        <option value="3"
                                                @if(@request()->status == '3') selected @endif>@lang('Rejected Payment')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <input type="date" class="form-control  form--control style--two" name="date_time"
                                           id="datepicker"/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0 h-fill">
                                    <button type="submit" class="cmn--btn ">
                                        <i
                                            class="fas fa-search"></i> @lang('Search')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <table class="table transection__table mt-5">
                <thead>
                <tr>
                    <th scope="col">@lang('Transaction ID')</th>
                    <th scope="col">@lang('Wallet')</th>
                    <th scope="col">@lang('Amount')</th>
                    <th scope="col">@lang('Status')</th>
                    <th scope="col">@lang('Time')</th>
                    <th scope="col">@lang('More')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($payoutLog as $item)
                    <tr>
                        <td data-label="#@lang('Transaction ID')">{{$item->trx_id}}</td>
                        <td data-label="@lang('Wallet')">{{$item->information}}</td>
                        <td data-label="@lang('Amount')">
                            <strong>{{getAmount($item->amount,8)}} @lang($item->code)</strong>
                        </td>

                        <td data-label="@lang('Status')">
                            @if($item->status == 1)
                                <span class="badge bg--warning">@lang('Pending')</span>
                            @elseif($item->status == 2)
                                <span class="badge bg--success">@lang('Complete')</span>
                            @elseif($item->status == 3)
                                <span class="badge bg--danger">@lang('Cancel')</span>
                            @endif
                        </td>

                        <td data-label="@lang('Time')">
                            {{ dateTime($item->created_at, 'd M Y h:i A') }}
                        </td>
                        <td data-label="@lang('More')" class="text-center">
                            @if($item->feedback)
                            <button type="button" class="btn btn--primary btn--sm infoButton"
                                    data-bs-backdrop='static' data-keyboard='false'
                                    data-bs-toggle="modal" data-bs-target="#infoModal"
                                    data-feedback="{{$item->feedback}}">
                                <i class="fas fa-desktop"></i>
                            </button>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty

                    <tr class="text-center">
                        <td colspan="100%">{{trans('No Data Found!')}}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $payoutLog->appends($_GET)->links($theme.'partials.pagination') }}
        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>



    @push('loadModal')
        <div id="infoModal" class="modal fade infoModal custom--modal modal-danger" tabindex="-1" role="dialog"
             data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content form-block gradient-bg">

                    <div class="modal-header">
                        <h6 class="modal-title">@lang('Admin Feedback')</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="white-text">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body  gradient-bg p-0">
                        <div class="deposit-preview-body p-4">
                            <p class="feedback"></p>

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
        "use strict";
        $(document).on('click','.infoButton', function () {
            var data = $(this).data();
            if(data.feedback == ''){
                $('.feedback').text(`@lang('No response from Admin')`);
            }else{
                $('.feedback').text(data.feedback);
            }
        });


        $(document).on('click', '.close', function (e) {
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


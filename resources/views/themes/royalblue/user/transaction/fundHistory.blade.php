@extends($theme.'layouts.user')
@section('title',trans('Payment Log'))
@section('content')

    <div class="dashboard-section padding-top padding-bottom">
        <div class="container">
            <div class="card gradient-bg form-block p-0 br-4">
                <div class="card-body">

                    <form action="{{ route('user.fund-history.search') }}" method="get">
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
                                                @if(@request()->status == '1') selected @endif>@lang('Complete Payment')</option>
                                        <option value="2"
                                                @if(@request()->status == '2') selected @endif>@lang('Pending Payment')</option>
                                        <option value="3"
                                                @if(@request()->status == '3') selected @endif>@lang('Cancel Payment')</option>
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
                    <th scope="col">@lang('Gateway')</th>
                    <th scope="col">@lang('Amount')</th>
                    <th scope="col">@lang('Charge')</th>
                    <th scope="col">@lang('Status')</th>
                    <th scope="col">@lang('Time')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($funds as $data)
                    <tr>

                        <td data-label="#@lang('Transaction ID')">{{$data->transaction}}</td>
                        <td data-label="@lang('Gateway')">@lang(optional($data->gateway)->name)</td>
                        <td data-label="@lang('Amount')">
                            <strong>{{getAmount($data->amount)}} @lang($basic->currency)</strong>
                        </td>

                        <td data-label="@lang('Charge')">
                            <strong>{{getAmount($data->charge)}} @lang($basic->currency)</strong>
                        </td>

                        <td data-label="@lang('Status')">
                            @if($data->status == 1)
                                <span class="badge bg--success">@lang('Complete')</span>
                            @elseif($data->status == 2)
                                <span class="badge bg--warning">@lang('Pending')</span>
                            @elseif($data->status == 3)
                                <span class="badge bg--danger">@lang('Cancel')</span>
                            @endif
                        </td>

                        <td data-label="@lang('Time')">
                            {{ dateTime($data->created_at, 'd M Y h:i A') }}
                        </td>
                    </tr>
                @empty

                    <tr class="text-center">
                        <td colspan="100%">{{__('No Data Found!')}}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{ $funds->appends($_GET)->links($theme.'partials.pagination') }}
        </div>
        <div class="shape shape1">
            <img src="{{getFile($themeTrue.'images/about/shape2.png')}}" alt="about">
        </div>
    </div>
@endsection


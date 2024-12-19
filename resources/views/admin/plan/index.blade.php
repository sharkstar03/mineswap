@extends('admin.layouts.app')
@section('title')
    {{ trans($page_title) }}
@endsection
@section('content')

    <div class="container-fluid">


        <div class="row">
            <div class="col-12">
                <div class="card card-primary shadow">
                    <div class="card-body">
                        @if(adminAccessRoute(config('role.mining_operation.access.add')))
                        <a href="{{route('admin.manage-plan.create')}}"
                                class="btn btn-success btn-sm float-right mb-3"><i
                                class="fa fa-plus-circle"></i> {{trans('Add New')}}
                        </a>
                        @endif

                        <div class="table-responsive p-3">
                        <table class="table table-striped" @if(count($list) > 0) id="dataTable" @endif>
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">@lang('Image')</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Miner')</th>
                                <th scope="col">@lang('Price')</th>
                                <th scope="col">@lang('Hashrate')</th>
                                <th scope="col">@lang('Duration')</th>
                                <th scope="col">@lang('Profit') <small>(@lang('per day'))</small></th>
                                <th scope="col">@lang('Referral Bonus')</th>
                                <th scope="col">@lang('Status')</th>
                                @if(adminAccessRoute(config('role.mining_operation.access.edit')))
                                <th scope="col">@lang('Action')</th>
                                @endif
                            </tr>

                            </thead>
                            <tbody id="sortable">
                            @if(count($list) > 0)
                                @foreach($list as $obj)
                                    <tr>
                                        <td data-label="@lang('Image')"><img src="{{ getFile(config('location.plan.path'). $obj->image) }}"  alt="{{$obj->name}}" class="max-width-40 img-circle"> </td>
                                        <td data-label="@lang('Name')">{{ $obj->name }} </td>
                                        <td data-label="@lang('Miner')" class="font-weight-bold">{{ optional($obj->miner)->code }} </td>
                                        <td data-label="@lang('Price')">@lang(config('basic.currency_symbol')){{ $obj->price }} </td>
                                        <td data-label="@lang('Hashrate')">{{ $obj->hash_rate_speed }} {{ $obj->hash_rate_unit }} </td>
                                        <td data-label="@lang('Duration')">{{ $obj->duration }} {{$obj->periodText }} </td>
                                        <td data-label="@lang('Profit')">{{getAmount($obj->minimum_profit,8)}} - {{getAmount($obj->minimum_profit,8)}} </td>
                                        <td data-label="@lang('Referral Bonus')">{{ $obj->referral }}@lang('%') </td>

                                        <td data-label="@lang('Status')">
                                            {!!  $obj->status == 1 ? '<span class="badge badge-success badge-pill">'.trans('Active').'</span>' : '<span class="badge badge-danger badge-pill">'.trans('Inactive').'</span>' !!}
                                        </td>

                                        @if(adminAccessRoute(config('role.mining_operation.access.edit')))
                                        <td data-label="@lang('Action')">
                                            <a href="{{route('admin.manage-plan.edit',$obj)}}"
                                                    class="btn btn-sm btn-primary btn-circle"><i
                                                    class="fa fa-pencil-alt"></i>
                                            </a>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center text-danger" colspan="100%">
                                        @lang('No Data Found')
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection


@push('style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/dataTables.bootstrap4.min.css')}}">
@endpush

@push('js-lib')
    <script src="{{asset('assets/admin/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/dataTables.bootstrap4.min.js')}}"></script>
@endpush

@push('js')

    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable(); // ID From dataTable

        });
    </script>
@endpush

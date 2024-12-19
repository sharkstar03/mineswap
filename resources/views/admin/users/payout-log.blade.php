@extends('admin.layouts.app')
@section('title')
     @lang('Payout Log') : @lang($user->username)
@endsection
@section('content')
    <div class="page-header card card-primary m-0 m-md-4 my-4 m-md-0 p-5 shadow">
        <form action="{{ route('admin.payout-log.search') }}" method="get">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="name" value="{{@request()->name}}" class="form-control"
                               placeholder="@lang('Email/ Username/ Trx')">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="">@lang('All Payment')</option>
                            <option value="1"
                                    @if(@request()->status == '1') selected @endif>@lang('Pending Payment')</option>
                            <option value="2"
                                    @if(@request()->status == '2') selected @endif>@lang('Complete Payment')</option>
                            <option value="3"
                                    @if(@request()->status == '3') selected @endif>@lang('Cancel Payment')</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="date" class="form-control" name="date_time" id="datepicker"/>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn waves-effect waves-light btn-primary"><i
                                class="fas fa-search"></i> @lang('Search')</button>
                    </div>
                </div>
            </div>
        </form>

    </div>


    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">

            <div class="table-responsive">
                <table class="categories-show-table table table-hover table-striped table-bordered">
                    <thead class="thead-primary">
                    <tr>
                        <th scope="col">@lang('Date')</th>
                        <th scope="col">@lang('Trx Number')</th>
                        <th scope="col">@lang('Username')</th>
                        <th scope="col">@lang('Amount')</th>
                        <th scope="col">@lang('Wallet Address')</th>
                        <th scope="col">@lang('Status')</th>
                        <th scope="col">@lang('More')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($records as $key => $item)
                        <tr>
                            <td data-label="@lang('Date')"> {{ dateTime($item->created_at,'d M,Y H:i') }}</td>
                            <td data-label="@lang('Trx Number')"
                                class="font-weight-bold text-uppercase">{{ $item->trx_id }}</td>
                            <td data-label="@lang('Username')"><a
                                    href="{{route('admin.user-edit', $item->user_id)}}"
                                    target="_blank">{{ optional($item->user)->username }}</a>
                            </td>
                            <td data-label="@lang('Amount')" class="font-weight-bold">
                                <strong>{{getAmount($item->amount,8)}} @lang($item->code)</strong>
                            </td>

                            <td data-label="@lang('Wallet Address')">{{ $item->information }}</td>

                            <td data-label="@lang('Status')">
                                @if($item->status == 2)
                                    <span class="badge badge-success badge-pill">@lang('Approved')</span>
                                @elseif($item->status == 1)
                                    <span class="badge badge-warning badge-pill">@lang('Pending')</span>
                                @elseif($item->status == 3)
                                    <span class="badge badge-danger badge-pill">@lang('Rejected')</span>
                                @endif
                            </td>
                            <td data-label="@lang('More')">
                                <button type="button" class="btn btn-primary btn-circle edit_button"
                                        data-toggle="modal" data-target="#myModal"
                                        data-backdrop='static' data-keyboard='false'
                                        data-route="{{route('admin.payout-action',$item->id)}}"
                                        data-feedback="{{$item->feedback}}"
                                        data-id="{{$item->id}}"
                                        data-status="{{$item->status}}">
                                    @if(Request::routeIs('admin.payout-request'))
                                        <i class="fa fa-pencil-alt"></i>
                                    @else
                                        <i class="fa fa-eye"></i>
                                    @endif
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%">
                                <p class="text-dark">@lang('No Data Found')</p>
                            </td>
                        </tr>

                    @endforelse
                    </tbody>
                </table>
                {{ $records->appends($_GET)->links('partials.pagination') }}
            </div>
        </div>
    </div>



    <!-- Modal for Edit button -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="myModalLabel">@lang('Payout Information')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <form role="form" method="POST" class="actionRoute" action="" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="withdraw-detail"></div>

                        @if(Request::routeIs('admin.payout-request'))
                            <div class="form-group addForm">

                            </div>
                        @endif

                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-light" data-dismiss="modal">@lang('Close')
                        </button>
                        @if(Request::routeIs('admin.payout-request'))
                            <input type="hidden" class="action_id" name="id">
                            <button type="submit" class="btn btn-primary" name="status"
                                    value="2">@lang('Approve')</button>
                            <button type="submit" class="btn btn-danger" name="status"
                                    value="3">@lang('Reject')</button>
                        @endif
                    </div>

                </form>


            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        "use strict";
        $(document).on("click", '.edit_button', function (e) {
            var id = $(this).data('id');
            $(".action_id").val(id);
            $(".actionRoute").attr('action', $(this).data('route'));
            var list = null;

            if ($(this).data('status') != '1') {
                list = `<p><span class="font-weight-bold">@lang('Admin Feedback')</span> : <br> <span">${$(this).data('feedback')}</span></p>`;
                $('.addForm').html(``)
            } else {
                list = ``;
                $('.addForm').html(`<div class="form-group">
                                <label for="feedback">@lang('Feedback')</label>
                                <textarea class="form-control" name="feedback"></textarea>
                                </div>`);
            }
            $('.withdraw-detail').html(list);
        });


        $(document).ready(function () {
            $('select').select2({
                selectOnClose: true
            });
        });
    </script>
@endpush

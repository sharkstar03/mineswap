@extends('admin.layouts.app')
@section('title')
    {{ trans($page_title) }}
@endsection
@section('content')

    <div class="container-fluid">


        <div class="row">
            <div class="col-12">
                @include('errors.error')
                <div class="card card-primary shadow">
                    <div class="card-body">
                        @if(adminAccessRoute(config('role.mining_operation.access.add')))
                        <button type="button"
                                class="btn btn-success btn-sm float-right mb-3"
                                data-toggle="modal" data-target="#newModal"><i
                                class="fa fa-plus-circle"></i> {{trans('Add New')}}
                        </button>
                        @endif

                        <div class="table-responsive p-3">
                            <table class="table table-striped" @if(count($list) > 0) id="dataTable" @endif>
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Miner Code')</th>
                                <th scope="col">@lang('Total Plans')</th>
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
                                        <td data-label="@lang('Name')">{{ $obj->name }} </td>
                                        <td data-label="@lang('Miner Code')">{{ $obj->code }} </td>
                                        <td data-label="@lang('Total Plan')">
                                            <span class="badge badge-light">{{$obj->plans_count}}</span>
                                        </td>
                                        <td data-label="@lang('Status')">
                                            {!!  $obj->status == 1 ? '<span class="badge badge-success badge-pill">'.trans('Active').'</span>' : '<span class="badge badge-danger badge-pill">'.trans('Inactive').'</span>' !!}
                                        </td>

                                        @if(adminAccessRoute(config('role.mining_operation.access.edit')))
                                        <td data-label="@lang('Action')">
                                            <button type="button"
                                                    data-route="{{ route('admin.mining.update',$obj) }}"
                                                    data-resource="{{$obj}}"
                                                    class="btn btn-sm btn-primary  btn-circle changeBtn"
                                                    data-toggle="modal" data-target="#updateModal"><i
                                                    class="fa fa-pencil-alt"></i>
                                            </button>
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


    <div id="newModal" data-backdrop="static" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-plus-circle"></i> @lang('Add New Miner')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.mining.add') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">@lang('Miner Name')</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}"
                                   placeholder="@lang('Enter Miner Name')">
                        </div>
                        <div class="form-group">
                            <label for="code">@lang('Miner Code')</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{old('code')}}"
                                   placeholder="@lang('Enter Miner Code')">
                        </div>

                        <div class="form-group">
                            <label for="minimum_amount">@lang('Minimum Payout Amount')</label>
                            <input type="text" class="form-control" id="minimum_amount" name="minimum_amount"
                                   value="{{old('minimum_amount')}}" placeholder="@lang('Minimum Amount')">
                        </div>

                        <div class="form-group">
                            <label for="maximum_amount">@lang('Maximum Payout Amount')</label>
                            <input type="text" class="form-control" id="maximum_amount" name="maximum_amount"
                                   value="{{old('maximum_amount')}}" placeholder="@lang('Maximum Amount')">
                        </div>

                        <div class="form-group">
                            <label for="status">@lang('Status')</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1">
                                <label class="custom-control-label" for="status">@lang('Active')</label>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="updateModal"  data-backdrop="static" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h5 class="modal-title"><i class="fa fa-pencil-alt"></i> @lang('Edit Miner')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <form action="" method="POST" class="updateForm">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">@lang('Miner Name')</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}"
                                       placeholder="@lang('Enter Miner Name')">
                            </div>
                            <div class="form-group">
                                <label for="code">@lang('Miner Code')</label>
                                <input type="text" class="form-control" id="code" name="code" value="{{old('code')}}"
                                       placeholder="@lang('Enter Miner Code')">
                            </div>

                            <div class="form-group">
                                <label for="minimum_amount">@lang('Minimum Payout Amount')</label>
                                <input type="text" class="form-control" id="minimum_amount" name="minimum_amount"
                                       value="{{old('minimum_amount')}}" placeholder="@lang('Minimum Amount')">
                            </div>

                            <div class="form-group">
                                <label for="maximum_amount">@lang('Maximum Payout Amount')</label>
                                <input type="text" class="form-control" id="maximum_amount" name="maximum_amount"
                                       value="{{old('maximum_amount')}}" placeholder="@lang('Maximum Amount')">
                            </div>

                            <div class="form-group">
                                <label for="edit_status">@lang('Status')</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="edit_status" name="status" value="1">
                                    <label class="custom-control-label" for="edit_status">@lang('Active')</label>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-dismiss="modal">@lang('Close')</button>
                            <button type="submit" class="btn btn-primary">@lang('Update')</button>
                        </div>
                    </form>
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

    <script>
        "use strict";
        $(document).on('click','.changeBtn', function () {
            var route = $(this).data('route');
            var resource = $(this).data('resource');
            var modal = $('#updateModal');
            modal.find('form').attr('action',route);
            modal.find('input[name=name]').val(resource.name);
            modal.find('input[name=code]').val(resource.code);
            modal.find('input[name=minimum_amount]').val(resource.minimum_amount);
            modal.find('input[name=maximum_amount]').val(resource.maximum_amount);
            if(resource.status == 1){
                $('#edit_status').attr('checked', true)
            }else{
                $('#edit_status').attr('checked', false)
            }
        });
    </script>
@endpush

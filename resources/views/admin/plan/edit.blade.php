@extends('admin.layouts.app')
@section('title', trans($page_title))
@section('content')

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media mb-4 justify-content-end">
                <a href="{{route('admin.manage-plan')}}" class="btn btn-sm  btn-primary mr-2">
                    <span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
                </a>
            </div>
            @include('errors.error')

            <form method="post" action="{{route('admin.manage-plan.update',$miningPlan)}}" enctype="multipart/form-data" class="form-row justify-content-between">
                @csrf
                @method('patch')
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Name')</label>
                        <input type="text" name="name" value="{{old('name',$miningPlan->name)}}" placeholder="@lang('Plan Name')"
                               class="form-control">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class=" col-md-6">
                    <div class="form-group">
                        <label for="mining_id">@lang('Mining')</label>
                        <select name="mining_id" id="mining_id" class="form-control">
                            <option value="" disabled selected>@lang('Select One')</option>
                            @foreach($list as $item)
                                <option value="{{$item->id}}"
                                        {{ old('mining_id',$miningPlan->mining_id) == $item->id ? 'selected' : '' }}  data-item="{{$item}}"> {{$item->name}}</option>
                            @endforeach
                        </select>

                        @error('mining_id')
                        <br><br>
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Price')</label>
                        <div class="input-group mb-3">
                            <input type="text" name="price" value="{{old('price',$miningPlan->price)}}" class="form-control"
                                   placeholder="0.00">
                            <div class="input-group-append">
                                <span class="input-group-text">@lang(config('basic.currency'))</span>
                            </div>
                        </div>
                        @error('price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-sm-6">
                    <div class="form-group">
                        <label>@lang('Profit Type')</label>
                        <input data-toggle="toggle" id="profit_type" data-onstyle="success"
                               data-offstyle="info" data-on="@lang('Fixed')" data-off="@lang('Random')"
                               data-width="100%"
                               value="1"
                               type="checkbox" {{(old('profit_type',$miningPlan->profit_type) == '1') ? 'checked':''}}
                               name="profit_type" >
                        @error('profit_type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="form-group col-md-6 fixedAmount d-block">
                    <label>@lang('Profit Amount') <small>(@lang('per day'))</small></label>
                    <div class="input-group mb-3">
                        <input type="text" name="profit" value="{{old('profit',$miningPlan->maximum_profit)}}" class="form-control"
                               placeholder="0.00">
                        <div class="input-group-append">
                            <span class="input-group-text profit-label">@lang(optional($miningPlan->miner)->code)</span>
                        </div>
                    </div>
                    @error('profit')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group col-md-6 rangeAmount d-none">
                    <label>@lang('Minimum Profit') <small>(@lang('per day'))</small></label>
                    <div class="input-group mb-3">
                        <input type="text" name="minimum_profit" value="{{old('minimum_profit',$miningPlan->minimum_profit)}}" class="form-control"
                               placeholder="0.00">
                        <div class="input-group-append">
                            <span class="input-group-text  profit-label">@lang(optional($miningPlan->miner)->code)</span>
                        </div>
                    </div>
                    @error('minimum_profit')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6 rangeAmount d-none">
                    <label>@lang('Maximum Profit') <small>(@lang('per day'))</small></label>
                    <div class="input-group mb-3">
                        <input type="text" name="maximum_profit" value="{{old('maximum_profit',$miningPlan->maximum_profit)}} " class="form-control"
                               placeholder="0.00">
                        <div class="input-group-append">
                            <span class="input-group-text  profit-label">@lang(optional($miningPlan->miner)->code)</span>
                        </div>
                    </div>
                    @error('maximum_profit')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group col-md-6">
                    <label>@lang('Duration')</label>
                    <div class="input-group mb-3">
                        <input type="number" name="duration" value="{{old('duration',$miningPlan->duration)}}" class="form-control"
                               placeholder="10">
                        <div class="input-group-append">
                            <select name="period" id="period" class="form-control">
                                @foreach(config('plan.period') as $key => $item)
                                    <option
                                        value="{{$item}}" {{ old('period',$miningPlan->period) == $item ? 'selected' : '' }}>@lang($key)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @error('duration')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group col-md-6">
                    <label>@lang('Hashrate')</label>
                    <div class="input-group mb-3">
                        <input type="text" name="hash_rate_speed" value="{{old('hash_rate_speed',$miningPlan->hash_rate_speed)}}" class="form-control" placeholder="20">
                        <div class="input-group-append">
                            <select name="hash_rate_unit" id="hash_rate_unit" class="form-control">
                                @foreach(config('plan.hash_rate') as $key => $item)
                                    <option
                                        value="{{$item}}" {{ old('hash_rate_unit',$miningPlan->hash_rate_unit) == $item ? 'selected' : '' }}>@lang($item)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @error('hash_rate_speed')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Referral Commission') <small>(@lang('Per Sale'))</small></label>
                        <div class="input-group mb-3">
                            <input type="text" name="referral" value="{{old('referral',$miningPlan->referral)}}"
                                   class="form-control" placeholder="0.00">
                            <div class="input-group-append">
                                <span class="input-group-text">@lang('%')</span>
                            </div>
                        </div>
                        @error('referral')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-sm-6 ">

                    <div class="form-group ">
                        <label>@lang('Status')</label>
                        <input data-toggle="toggle" id="status" data-onstyle="success"
                               data-offstyle="info" data-on="@lang('Active')" data-off="@lang('Deactive')"
                               data-width="100%"
                               type="checkbox" @if($miningPlan->status) checked @endif name="status">
                        @error('status')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                </div>

                <div class=" col-sm-6">
                    <div class="form-group ">

                        <label>@lang('Featured')</label>
                        <input data-toggle="toggle" id="featured" data-onstyle="success" data-offstyle="info"
                               data-on="@lang('YES')"
                               data-off="@lang('NO')" data-width="100%" type="checkbox" @if($miningPlan->featured) checked @endif name="featured">
                        @error('featured')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class=" col-sm-12">
                    <div class="form-row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>@lang('Image')</label>

                                <div class="image-input ">
                                    <label for="image-upload" id="image-label"><i class="fas fa-upload"></i></label>
                                    <input type="file" name="image" placeholder="@lang('Choose image')" id="image">
                                    <img id="image_preview_container" class="preview-image"
                                         src="{{ getFile(config('location.plan.path').$miningPlan->image)}}"
                                         alt="preview image">
                                </div>

                                @if(config("location.plan.size"))
                                    <span
                                        class="text-muted mb-2">{{trans('Image size should be')}} {{config("location.plan.size")}} {{trans('px')}}</span>
                                @endif
                                @error('image')
                                <span class="text-danger">{{ trans($message) }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>


                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                            class="fas fa-save pr-2"></i> @lang('Save Changes')</span></button>

            </form>
        </div>
    </div>
@endsection


@push('js')
    <script>
        "use strict";
        checkToggle($('#profit_type').prop('checked'));
        $(document).on('change', '#profit_type', function () {
            checkToggle($(this).prop('checked'))
        });

        $(document).ready(function () {
            $('select[name=mining_id]').select2({
                selectOnClose: true
            });
        });

        $(function () {

            $('select[name=mining_id]').on('change', function () {
                var data = $("select[name=mining_id] option:selected").data('item');
                $('.profit-label').text(data.code)
            })
        });

        $('#image').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#image_preview_container').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        function checkToggle (isCheck){
            if (isCheck == false) {
                $('.rangeAmount').addClass('d-block');
                $('.fixedAmount').removeClass('d-block');
                $('.fixedAmount').addClass('d-none');
            } else {
                $('.rangeAmount').removeClass('d-block');
                $('.fixedAmount').addClass('d-block');
            }
        }
    </script>



    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp

    @endif
@endpush

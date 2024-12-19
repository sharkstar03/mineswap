@if(isset($templates['calculation'][0]) && $calculation = $templates['calculation'][0])


    <!-- Calculation Section Starts Here -->
    <section class="calculation-section padding-top padding-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-10">
                    <div class="section-header text-center">
                        <span class="section-header_subtitle">@lang(@$calculation['description']->sub_title)</span>
                        <h2 class="section-header_title">@lang(@$calculation['description']->heading) <span
                                class="text--base">@lang(@$calculation['description']->highlight_heading)</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="row gy-5 align-items-center">
                <div class="col-lg-6">
                    <div class="section-thumb rtl me-lg-5">
                        <img src="{{getFile($themeTrue.'images/account/thumb2.png')}}" alt="calculation">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="calculation-wrapper">
                        <h4 class="title mb-4">@lang(@$calculation['description']->calculation_title)</h4>
                        <form class="profit-cal-form row gy-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="coin">@lang('Select Coin')</label>
                                    <select id="coin" class=" form--control w-100 px-3">
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        @foreach($packages as $obj)
                                            <option value="{{$obj->id}}">{{trans($obj->name)}}</option>
                                        @endforeach
                                    </select>

                                    <span class="text-danger package-err"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="package">@lang('Select Plan')</label>
                                    <select id="package" class=" form--control w-100 px-3">
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="invest-amount">@lang('Estimated Revenue')</label>
                                    <input id="invest-amount" type="text" class="form-control form--control revenue"
                                           placeholder="0">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Calculation Section Ends Here -->

@endif
@push('script')
    <script>
        $(document).ready(function () {
            $('.package-err').text('');
            $('.revenue').val('');
            $("#coin").on('change', function () {
                $('.revenue').val('');
                $('.package-err').text('');
                let coinId = $(this).find("option:selected").val();
                ajaxPlansByCoin(coinId)
            });
        });

        $(document).on('change', "#package", function () {
            $('.revenue').val('');
            let resource = $(this).find("option:selected").data();
            $('.revenue').val(`${resource.profit} ${resource.code}`);
        });

        function ajaxPlansByCoin(coinId) {
            $.ajax({
                method: "POST",
                url: "{{route('ajaxPlansByCoin')}}",
                data: { mining_id: coinId},
                success: (function(res) {
                    $('#package').html(``);
                    $('#package').append(`<option value="" selected disabled>@lang('Select One')</option>`);
                    $.each(res, function (index, element) {
                        $('#package').append(`<option value="${element.id}" data-profit="${element.profit}" data-code="${element.code}">${element.name}</option>`);
                    });
                }),
                error: (function(err) {
                    if(err.status == 422){
                        $('.package-err').text(err.responseJSON.mining_ida[0]);
                    }
                })
            })


        }

    </script>
@endpush

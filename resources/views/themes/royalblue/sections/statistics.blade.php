@if(isset($contentDetails['statistics']))
<!-- Statistics Section Starts Here -->
<div class="statistics-section padding-top padding-bottom">
    <div class="container">
        <div class="row gy-5">

            @foreach($contentDetails['statistics'] as $key => $obj)
            <div class="col-md-3 col-sm-6">
                <div class="counter-item">
                    <div class="counter-item_icon">
                        <i class="{{@$obj->content->contentMedia->description->icon}}"></i>
                    </div>
                    <h2 class="counter-item_title">
                        <span class="title odometer" data-odometer-final="{{@$obj['description']->number_of_data}}"></span> +
                    </h2>
                    <span class="info">@lang(@$obj['description']->title)</span>
                </div>
            </div>
            @endforeach

        </div>
    </div>
    <div class="shape shape1">
        <img src="{{getFile($themeTrue.'images/counter/bg.png')}}" alt="counter">
    </div>
</div>
<!-- Statistics Section Ends Here -->
@endif

@if(isset($templates['news-letter'][0]) && $newsLetter = $templates['news-letter'][0])

    <section class="newsletter padding-top padding-bottom" id="subscribe">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-10">
                    <div class="section-header text-center">
                        <span class="section-header_subtitle">@lang(@$newsLetter->description->sub_title)</span>
                        <h2 class="section-header_title"><span
                                class="text--base">@lang(@$newsLetter->description->highlight_heading)  </span> @lang(@$newsLetter->description->heading)
                        </h2>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="{{route('subscribe')}}" method="post" class="subscribe-form">
                        @csrf
                        <input type="email" name="email" class="form-control" placeholder="@lang('Email Address')">
                        <button type="submit" class="btn-subscribe">{{trans('Subscribe')}}</button>
                    </form>
                    @error('email')<span class="text-danger font-weight-bold">{{ $message }}</span>@enderror
                </div>


            </div>
        </div>
    </section>

@endif

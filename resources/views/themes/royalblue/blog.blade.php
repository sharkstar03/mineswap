@extends($theme.'layouts.app')
@section('title', trans($title))

@section('content')
    @if(isset($contentDetails['blog']))
        <!-- Blog Section Starts Here -->
        <section class="blog-section padding-top padding-bottom">
            <div class="container">

                <div class="row g-4 justify-content-center">
                    @foreach($contentDetails['blog'] as $data)
                        <div class="col-lg-4 col-md-6 col-sm-10">
                            <div class="post__item">
                                <div class="post__item-thumb">
                                    <img
                                        src="{{getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)}}"
                                        alt="blog">
                                </div>
                                <div class="post__item-content">
                                    <h4 class="title">
                                        <a href="{{route('blogDetails',[slug(@$data->description->title), $data->content_id])}}">
                                            {{Str::limit(@$data->description->title,40)}}
                                        </a>
                                    </h4>
                                    <p>{{Str::limit(strip_tags(@$data->description->description),120)}}</p>
                                    <span class="date mt-3"><i class="las la-clock"></i> {{dateTime(@$data->created_at,'d M, Y')}}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </section>
        <!-- Blog Section Ends Here -->
    @endif
@endsection

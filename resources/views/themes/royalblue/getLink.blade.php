@extends($theme.'layouts.app')
@section('title', trans($title))

@section('content')
    <section class="blog-section padding-top padding-bottom">
        <div class="container">
            <div class="row gy-5">

                <div class="post__details">
                    <div class="post__body">

                        @lang(@$description)

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<a class="col-lg-3 col-md-3 col-12" href="{{route('frontend.single-category',['slug'=> $childCategory->slug])}}"><div class="pill mb-2">{{$childCategory->name}}</div></a>
@if ($childCategory->children)
        @foreach ($childCategory->children as $childCategory)
            @include('frontend.includes.partials.child-category-pill', ['childCategory' => $childCategory])
        @endforeach
@endif

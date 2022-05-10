<a class="col-lg-3 col-md-3 col-6 @if(isset($class)){{$class}}@endif" href="{{route('frontend.single-category',['slug'=> $childCategory->slug])}}">{{$childCategory->name}}</a>
@if($childCategory instanceof App\Models\Blog\Category)
    @if ($childCategory->childrenRecursive)
            @foreach ($childCategory->childrenRecursive as $childCategory)
                @include('frontend.includes.partials.child-category', ['childCategory' => $childCategory])
            @endforeach
    @endif
@else
    @if ($childCategory->children_recursive)
            @foreach ($childCategory->children_recursive as $childCategory)
                @include('frontend.includes.partials.child-category', ['childCategory' => $childCategory])
            @endforeach
    @endif
@endif
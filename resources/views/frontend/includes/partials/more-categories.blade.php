<div class="row explore-categories">
    <div class="col-12">
        <p class="content-title">{{$heading}}</p>
        <div class="sticky-nav-wrapper">
            <div class="btn-wrapper position-relative">
                <div id="right-button" style="visibility: hidden;"><a href="#">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-left" class="svg-inline--fa fa-angle-left fa-w-8" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M31.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34z"></path></svg>
                    </a></div>
                <div id="left-button"><a href="#">
                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-right" class="svg-inline--fa fa-angle-right fa-w-8" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg>
                    </a></div>
            </div>
            <ul id="sticky-navbar" class="list-unstyled list-inline">
                <li class="list-inline-item active"><a  data-toggle="tab" id="nav-all-tab"  class=" active" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true">
                        All
                    </a>
                </li>
                @foreach($categories->where('parent_id','=',null) as $category)
                    <li class="list-inline-item"><a data-toggle="tab"   href="#nav-{{$category->slug}}" role="tab" aria-controls="nav-{{$category->slug}}" id="nav-{{$category->slug}}-tab" aria-selected="false">
                            <span>{{$category->name}}</span></a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="tab-content col-12" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
            <div class="row">
                @foreach($categories->take(12) as $category)
                    <a class="col-lg-3 col-md-3 col-6" href="{{route('frontend.single-category',['slug'=> $category->slug])}}">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>
        @foreach($categories->whereNull('parent_id') as $parentCategory)
            <div class="tab-pane fade" id="nav-{{$parentCategory->slug}}" role="tabpanel" aria-labelledby="nav-{{$parentCategory->slug}}-tab">
                <div class="row">
                    @include('frontend.includes.partials.child-category', ['childCategory' => $parentCategory])
                    {{--@foreach($parentCategory->children as $childCategory)
                        <a class="col-lg-3 col-md-3 col-6" href="#">{{$childCategory->name}}</a>
                    @endforeach--}}
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-12 text-center">
        <a href="{{url('categories')}}" class="btn btn-outline-primary">More</a>
    </div>

</div>

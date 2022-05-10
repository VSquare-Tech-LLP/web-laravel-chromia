<div class="col-lg-4 col-md-4 col-12 d-flex align-items-stretch post-card">
    <div class="card">
        @php($post_title = $post->title)
        <a href="{{url($post->slug)}}" title="{{$post_title}}">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" data-src="{{ $post->getFirstMediaUrl('featured_post_image','thumb') }}" class="card-img-top lazyload"
             alt="{{$post_title}}">
        <div class="card-body">
            <p class="card-title">{{$post_title}}</p>
        </div>
        </a>
    </div>
</div>

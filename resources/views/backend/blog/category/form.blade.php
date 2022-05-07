<x-slot name="body">
    <div>
        <div class="form-group row">
            <label for="name" class="col-md-2 col-form-label">@lang('Name')</label>

            <div class="col-md-10">
                <input type="text" name="name" class="form-control" placeholder="{{ __('Name') }}" value="{{ old('name',$category->name) }}" required />
            </div>
        </div>
        <div class="form-group row">
            <label for="slug" class="col-md-2 col-form-label">@lang('Slug')</label>

            <div class="col-md-10">
                <input type="text" name="slug" class="form-control" placeholder="{{ __('Slug') }}" value="{{ old('slug',$category->slug) }}"/>
            </div>
        </div>

        <div class="form-group row">
            <label for="image" class="col-md-2 col-form-label">@lang('Image')</label>

            <div class="col-md-10">
                <input type="file" name="image"/>
            </div>
        </div>
        @if($category->id)
            @if($category->getFirstMedia('category_image'))
            <div class="form-group row">
                <div class="col-2"></div>
                <div class="col-10">
                    <img width="300" src="{{ $category->getFirstMediaUrl('category_image') }}">
                </div>
            </div>
            @endif
        @endif
        <div class="form-group row">
            <label for="description" class="col-md-2 col-form-label">@lang('Description')</label>

            <div class="col-md-10">
                <textarea class="form-control" id="description" name="description"  placeholder="{{ __('Description') }}">{{ old('description',$category->description) }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="meta_title" class="col-md-2 col-form-label">@lang('Meta Title')</label>

            <div class="col-md-10">
                <input type="text" name="meta_title" class="form-control" placeholder="{{ __('Meta Title') }}" value="{{ old('meta_title',$category->meta_title) }}"/>
            </div>
        </div>
        <div class="form-group row">
            <label for="meta_description" class="col-md-2 col-form-label">@lang('Meta Description')</label>

            <div class="col-md-10">
                <textarea class="form-control" name="meta_description" placeholder="{{ __('Meta Description') }}">{{ old('meta_description',$category->meta_description) }}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="name" class="col-md-2 col-form-label">@lang('Parent Category')</label>
            <div class="col-md-10">
                <select class="form-control" name="parent_id">
                    <option value=""></option>
                    @forelse($parents as $parent)
                        <option
                            value="{{ $parent->id}}"
                            @if($category->parent_id == $parent->id)
                            selected
                            @endif
                        >
                            {{ $parent->name }}
                        </option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>
    </div>
</x-slot>

@push('after-scripts')
    <script src="{{asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script>
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
        };
        CKEDITOR.replace( 'description',options );
        CKEDITOR.on('instanceReady', function(ev) {
            var editor = ev.editor;
            editor.dataProcessor.htmlFilter.addRules({
                elements : {
                    a : function( element ) {
                        if ( !element.attributes.rel ){
                            //gets content's a href values
                            var url = element.attributes.href;
                            //extract host names from URLs
                            var hostname = (new URL(url)).hostname;
                            if ( hostname !== window.location.host && hostname !=="w.com") {
                                element.attributes.rel = 'nofollow';
                            }
                        }
                    }
                }
            });
        })
    </script>
@endpush

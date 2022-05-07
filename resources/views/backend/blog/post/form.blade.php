@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
@endpush

<x-slot name="body">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="title" class="form-control-label font-weight-bold">@lang('Title')</label>

                <input type="text" name="title" class="form-control" placeholder="{{ __('Title') }}"
                       value="{{ old('title',$post->title) }}" required/>
            </div>
            <div class="form-group">
                <label for="slug" class="form-control-label font-weight-bold">@lang('Slug')</label>

                <input type="text" name="slug" class="form-control" placeholder="{{ __('Slug') }}"
                       value="{{ old('slug',$post->slug) }}"/>
            </div>
            <div class="form-group">
                <label for="name" class="form-control-label font-weight-bold">@lang('Featured Category')</label>
                <select class="form-control bs-select-categories" name="main_category">
                    <option value="" disabled selected>Select Category</option>
                    @forelse($categories as $category)
                        <option
                            value="{{ $category->id}}"
                            @if($post->main_category == $category->id)
                            selected
                            @endif
                        >
                            {{ $category->name }}
                        </option>
                    @empty
                    @endforelse
                </select>
            </div>

            <div class="form-group">
                <label for="body" class="form-control-label font-weight-bold">@lang('Body')</label>

                <textarea class="form-control" id="body" name="body"
                          placeholder="{{ __('Body') }}">{{ old('body',$post->body) }}</textarea>
            </div>

            <div class="form-group">
                <label for="meta_title" class="form-control-label font-weight-bold">@lang('Meta Title')</label>

                <input type="text" name="meta_title" class="form-control" placeholder="{{ __('Meta Title') }}"
                       value="{{ old('meta_title',$post->meta_title) }}"/>
            </div>
            <div class="form-group">
                <label for="meta_description"
                       class="form-control-label font-weight-bold">@lang('Meta Description')</label>

                <textarea class="form-control" name="meta_description"
                          placeholder="{{ __('Meta Description') }}">{{ old('meta_description',$post->meta_description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="is_featured"
                       class="form-control-label font-weight-bold">@lang('Feature this post ?')</label>

                <select required class="form-control" name="is_featured">
                    <option value="0" {{($post->is_featured=='0')?'selected=selected':""}}>No</option>
                    <option value="1" {{($post->is_featured=='1')?'selected=selected':""}}>Yes</option>
                </select>
            </div>
            @if($post->id)
                <div class="form-group">
                    <label for="is_featured" class="form-control-label font-weight-bold">@lang('Post Status')</label>
                    <div>
                        <span class={{($post->status == 1 )?"text-info":"text-success"}}>
                          {{$post->post_status}}
                        </span>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label for="user" class="form-control-label font-weight-bold">@lang('Author')</label>

                <select name="user_id" required class="form-control author-select" data-live-search="true">
                    @foreach($users as $user)
                        <option value="{{$user->id}}"
                                class="text-capitalize" @if($post->id){{($user->id == $post->user->id)?"selected='selected'":""}}@endif>{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <strong>Featured Image</strong>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="feature_image"
                                               name="feature_image">
                                        <label class="custom-file-label" for="feature_image">Choose file</label>
                                    </div>
                                </div>

                                @if($post->id)
                                    @if($post->getFirstMedia('featured_post_image'))
                                        <div class="w-100">
                                            <img class="img-thumbnail"
                                                 src="{{ $post->getFirstMediaUrl('featured_post_image') }}">
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <strong>Categories</strong>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <input type="text"
                               placeholder="Search Categories"
                               class="form-control searchInput mb-3">
                        <div class="checkbox-wrapper category">
                            @if($categories->count() > 0)
                                @foreach(($categories->where('parent_id','=',0)) as $item)
                                    @if($item->children->count() == 0)
                                        <div class="checkbox"
                                             data-value="{{$item->name}}">
                                            <label>
                                                <input
                                                    type="checkbox"
                                                    name="categories[]"
                                                    id="categories[]"
                                                    value="{{ $item->id }}"
                                                @if($post->id){{(in_array($item->id,$post->categories->pluck('id')->toArray()))?'checked=checked':""}} @endif>
                                                &nbsp;
                                                {{ $item->name }}
                                            </label>
                                        </div>
                                    @else
                                        <div class="checkbox"
                                             data-value="{{$item->name}}">
                                            <label>
                                                <input
                                                    type="checkbox"
                                                    name="categories[]"
                                                    id="categories[]"
                                                    value="{{ $item->id }}"
                                                @if($post->id){{(in_array($item->id,$post->categories->pluck('id')->toArray()))?'checked=checked':""}} @endif>
                                                &nbsp;
                                                {{ $item->name }}
                                            </label>
                                        </div>
                                        @foreach(($item->children) as $sub_item)
                                            <div class="checkbox"
                                                 data-value="{{$sub_item->name}}"
                                                 style="margin-left: 20px;">
                                                <label>
                                                    <input
                                                        type="checkbox"
                                                        name="categories[]"
                                                        id="categories[]"
                                                        value="{{ $sub_item->id }}"
                                                    @if($post->id){{(in_array($item->id,$post->categories->pluck('id')->toArray()))?'checked=checked':""}} @endif>
                                                    &nbsp;
                                                    {{ $sub_item->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <strong>Tags</strong>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <select class="form-control bs-select-tags" name="tags[]" multiple>
                                @forelse($tags as $tag)
                                    <option
                                        value="{{ $tag->name}}"
                                    @if($post->id){{(in_array($tag->id,$post->tags->pluck('id')->toArray()))?'selected=selected':""}} @endif
                                    >
                                        {{ $tag->name }}
                                    </option>
                                @empty
                                @endforelse
                            </select>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-slot>

@push('after-scripts')

    <script src="{{asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script>
        var options = {
            filebrowserImageBrowseUrl: '/filemanager?type=Images',
            filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token={{csrf_token()}}',
            filebrowserBrowseUrl: '/filemanager?type=Files',
            filebrowserUploadUrl: '/filemanager/upload?type=Files&_token={{csrf_token()}}',
            extraAllowedContent: '*[*]{*}(*);blockquote(*);div(*);table(*);td(*);ul(*);li(*);span(*);img(*);svg(*);path(*);text(*)',
            //extraAllowedContent : 'blockquote(*);div(*);table(*);td(*);ul(*);li(*);span(*);img[*]{*}(*);',
        };
        CKEDITOR.replace('body', options);
        CKEDITOR.on('instanceReady', function (ev) {
            var editor = ev.editor;
            editor.dataProcessor.htmlFilter.addRules({
                elements: {
                    a: function (element) {
                        if (!element.attributes.rel) {
                            //gets content's a href values
                            var url = element.attributes.href;
                            //extract host names from URLs
                            var hostname = (new URL(url)).hostname;
                            if (hostname !== window.location.host && hostname !== "w.com") {
                                element.attributes.rel = 'nofollow';
                            }
                        }
                    }
                }
            });
        })


    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.bs-select-categories').select2({
                width: "100%",
            });


            $('.bs-select-tags').select2({
                tags: true,
                tokenSeparators: [',', ' '],
                width: "100%",
            });
        });

        //Searching inputs
        $(document).on("input", ".searchInput", function () {
            var v = $(this).val();
            var filter = v.toUpperCase();
            var elements = $(this).siblings('.checkbox-wrapper').find('.checkbox');
            console.log(elements)
            for (i = 0; i < elements.length; i++) {
                var value = elements[i].getAttribute('data-value')
                if (value.toUpperCase().indexOf(filter) > -1) {
                    elements[i].style.display = "";
                } else {
                    elements[i].style.display = "none";
                }
            }
        });

        //select all checkboxes
        $(".select_all").change(function () {
            var status = this.checked;
            var checkboxes = $(this).parents('.action-wrapper').siblings('.card-body').find('.checkbox-wrapper .checkbox input');
            checkboxes.each(function () {
                this.checked = status;
            });
        });

        //Checkbox change events
        $('.checkbox-wrapper .checkbox input').change(function () {
            var selectCheckBox = $(this).parents('.checkbox-wrapper').parents('.card-body:first').siblings('.action-wrapper').find('.select_all');
            if (this.checked == false) {
                selectCheckBox.checked = false; //change "select all" checked status to false
            }

            var checked = $(this).parents('.checkbox-wrapper').find('input:checked').length;
            var totalCheckbox = $(this).parents('.checkbox-wrapper').find('input').length;
            if (checked === totalCheckbox) {
                selectCheckBox.checked = true; //change "select all" checked status to true
            }
        });
    </script>

@endpush

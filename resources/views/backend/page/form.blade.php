@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
@endpush

<x-slot name="body">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="title" class="form-control-label font-weight-bold">@lang('Title')</label>

                <input type="text" name="title" class="form-control" placeholder="{{ __('Title') }}"
                       value="{{ old('title',$page->title) }}" required/>
            </div>
            <div class="form-group">
                <label for="slug" class="form-control-label font-weight-bold">@lang('Slug')</label>

                <input type="text" name="slug" class="form-control" placeholder="{{ __('Slug') }}"
                       value="{{ old('slug',$page->slug) }}"/>
                <p>slug is auto generate by system</p>
            </div>


            <div class="form-group">
                <label for="body" class="form-control-label font-weight-bold">@lang('Body')</label>

                <textarea class="form-control" id="body" name="body"
                          placeholder="{{ __('Body') }}">{{ old('body',$page->body) }}</textarea>
            </div>

            <div class="form-group">
                <label for="meta_title" class="form-control-label font-weight-bold">@lang('Meta Title')</label>

                <input type="text" name="meta_title" class="form-control" placeholder="{{ __('Meta Title') }}"
                       value="{{ old('meta_title',$page->meta_title) }}"/>
            </div>
            <div class="form-group">
                <label for="meta_description"
                       class="form-control-label font-weight-bold">@lang('Meta Description')</label>

                <textarea class="form-control" name="meta_description"
                          placeholder="{{ __('Meta Description') }}">{{ old('meta_description',$page->meta_description) }}</textarea>
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
                                    <input type="file" class="custom-file-input" id="feature_image" name="feature_image">
                                    <label class="custom-file-label" for="feature_image">Choose file</label>
                                </div>
                            </div>

                            @if($page->id)
                                @if($page->getFirstMedia('featured_post_image'))
                                    <div class="w-100">
                                    <img class="img-thumbnail" src="{{ $page->getFirstMediaUrl('featured_post_image') }}">
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
                    <strong>Indexing</strong>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <label for="index_status"
                                           class="form-control-label font-weight-bold">@lang('Index this page?') </label>
                                    <label class="c-switch c-switch-sm  c-switch-label c-switch-pill c-switch-opposite-primary">
                                        <input class="c-switch-input" name="index_status" type="checkbox" @if($page->meta && $page->meta->index_status) checked @endif >
                                        <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                    </label>
                                </div>
                            </div>
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
            filebrowserUploadUrl: '/filemanager/upload?type=Files&_token={{csrf_token()}}'
        };
        CKEDITOR.replace( 'body',options );
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
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

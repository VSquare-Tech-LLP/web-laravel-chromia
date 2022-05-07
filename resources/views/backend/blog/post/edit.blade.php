@extends('backend.layouts.app')

@section('title', __('Edit Post'))

@section('content')
    <x-forms.post :action="route('admin.posts.update',['post' => $post])" name="update_post" id="update_post">
        @method('PUT')
        <x-backend.card>
            <x-slot name="header">
                @lang('Edit Post')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.posts.index')" :text="__('Cancel')" />
            </x-slot>

            @include('backend.blog.post.form')

            <x-slot name="footer">
                <div class="float-left">
                    <input type="hidden" name="action" id="action">
                    <input class="btn btn-sm btn-info float-left post_submit" type="submit" name="post_action" value="Draft">
                    <input class="btn btn-sm btn-info float-left post_submit ml-2" type="submit" name="post_action" value="Save Revision And Draft">
                    {!!  $post->view_preview_link  !!}
                </div>
                <div class="float-right">
                    <input class="btn btn-sm btn-success float-right post_submit" type="submit" name="post_action" value="Publish">
                </div>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
@push('after-scripts')
    <script>
        $('.post_submit').click(function(e){
            e.preventDefault();
            $('#action').val($(this).val())
            $('#update_post').submit();
        });

    </script>
@endpush

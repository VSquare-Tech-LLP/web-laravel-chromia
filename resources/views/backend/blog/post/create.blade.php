@extends('backend.layouts.app')

@section('title', __('Create Post'))

@section('content')
    <x-forms.post :action="route('admin.posts.store')" name="store_post" id="store_post">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Post')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.posts.index')" :text="__('Cancel')" />
            </x-slot>

            @include('backend.blog.post.form',['post' => optional()])

            <x-slot name="footer">
                <input type="hidden" name="action" id="action">
                <input class="btn btn-sm btn-info float-left post_submit" type="submit" name="post_action" value="Draft">
                <input class="btn btn-sm btn-success float-right post_submit" type="submit" name="post_action" value="Publish">
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
@push('after-scripts')
<script>
    $('.post_submit').click(function(e){
     e.preventDefault();
     $('#action').val($(this).val())
     $('#store_post').submit();
   });

</script>
@endpush

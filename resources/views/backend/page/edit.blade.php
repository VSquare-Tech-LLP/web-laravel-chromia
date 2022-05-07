@extends('backend.layouts.app')

@section('title', __('Edit Page'))

@section('content')
    <x-forms.post :action="route('admin.pages.update',['page' => $page])" name="update_post" id="update_post">
        @method('PUT')
        <x-backend.card>
            <x-slot name="header">
                @lang('Edit Page')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.pages.index')" :text="__('Cancel')" />
            </x-slot>

            @include('backend.page.form')

            <x-slot name="footer">
                <div class="float-left">
                    <input type="hidden" name="action" id="action">
                    <input class="btn btn-sm btn-info float-left post_submit" type="submit" name="post_action" value="Draft">
                    {!!  $page->view_preview_link  !!}
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

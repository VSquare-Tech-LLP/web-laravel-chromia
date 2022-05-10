@extends('backend.layouts.app')

@section('title', __('Menu Manager'))

@push('after-styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        .nav-tabs .nav-link, .nav-tabs .navbar .dropdown-toggle, .navbar .nav-tabs .dropdown-toggle {
            color: #536c79;
            background-color: #f1f1f1;
            border-bottom: 1px solid #a7b7bf;
        }

        .mb-0 > .card-header {
            display: block;
            position: relative;
            outline: none;
            text-align: left;
            border: none;
        }

        .mb-0 > .card-header span:after {
            content: "\f078"; /* fa-chevron-down */
            font-family: 'Font Awesome\ 5 Free';
            font-weight: 900;
            right: 15px;
            left: 10px
        }

        .mb-0 > button.card-header:after {
            position: absolute;
            content: "\f078"; /* fa-chevron-down */
            font-family: 'Font Awesome\ 5 Free';
            font-weight: 900;
            right: 15px;
        }

        .sub-menu {
            margin-left: 30px;
        }

        .sub-sub-menu {
            margin-left: 60px;
        }

        .mb-0 > button.card-header[aria-expanded="true"]:after, .card-header span[aria-expanded="true"]:after {
            content: "\f077"; /* fa-chevron-up */
            font-weight: 900;
        }

        div.disabled {
            pointer-events: none;
            cursor: not-allowed;
            /* for "disabled" effect */
            opacity: 0.5;
            background: #CCC;
        }

        .menu-list {
            list-style-type: none;
            padding-left: 0px;
        }

        .menu-list .card-header {
            cursor: move;
        }

        .menu-list .card-header span {
            cursor: pointer;
        }

        .action-text {
            cursor: pointer;
            color: blue;
        }

        .action-text span {
            margin-right: 20px;
            white-space: nowrap;
        }

        .error {
            border-color: red;
        }

        .card-header h6 {
            color: grey;
            font-size: 0.800rem;
            margin-left: 10px;
            display: inline-block;
        }

    </style>
    <link href="{{asset('vendor/menu-builder/style.css')}}" rel="stylesheet">

@endpush


@section('content')

    <x-backend.card>
        <x-slot name="header">
            @lang('Menu Manager')
        </x-slot>
        <x-slot name="body">
            <div class="row mt-4 mb-4">
                {!! Menu::render()->with(['menu' => $menu,'menu_list' => $menu_list, 'categories' => $categories, 'posts' => $posts, 'pages' => $pages]) !!}
            </div>
        </x-slot>
    </x-backend.card>
@endsection

@push('after-scripts')
    {!! Menu::scripts() !!}
    <script type="text/javascript">

        $(document).ready(function () {


            @if(\Session::has('message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('message')}}',
                loaderBg: '#ff6849',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif


            $(document).on('click', '.btn-add', function (e) {
                e.preventDefault();

                var tableFields = $('.table-fields'),
                    currentEntry = $(this).parents('.entry:first'),
                    newEntry = $(currentEntry.clone()).appendTo(tableFields);

                newEntry.find('input').val('');
                tableFields.find('.entry:not(:last) .btn-add')
                    .removeClass('btn-add').addClass('btn-remove')
                    .removeClass('btn-success').addClass('btn-danger')
                    .html('<span class="fa fa-minus"></span>');
            }).on('click', '.btn-remove', function (e) {
                $(this).parents('.entry:first').remove();

                e.preventDefault();
                return false;
            });

        });
    </script>
@endpush

<?php
$currentUrl = url()->current();
?>
@push('before-styles')
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="{{asset('vendor/menu-builder/style.css')}}" rel="stylesheet">
    <style>
        .card-body.pb-0 {
            max-height: 160px;
            overflow-y: scroll;
        }

        .location-table .form-group {
            margin-bottom: 0px;
        }

        .location-table td, th {
            padding: 5px 0px;
        }

        .location-table td a {
            line-height: 30px;
        }
    </style>
@endpush
<div id="hwpwrap" class="col-12">
    <div class="custom-wp-admin wp-admin wp-core-ui js   menu-max-depth-0 nav-menus-php auto-fold admin-bar">
        <div id="wpwrap">
            <div id="wpcontent">
                <div id="wpbody">
                    <div id="wpbody-content">

                        <div class="wrap">

                            <div class="manage-menus">
                                <form method="get" action="{{ $currentUrl }}">
                                    <label for="menu" class="selected-menu">Select the menu you want to edit:</label>

                                    {!! Menu::select('menu', $menulist) !!}

                                    <span class="submit-btn">
										<input type="submit" class="button-secondary" value="Choose">
									</span>
                                    <span class="add-new-menu-action"> or <a href="{{ $currentUrl }}?action=edit&menu=0">Create new menu</a>. </span>
                                </form>
                            </div>
                            <div id="nav-menus-frame" class="row">

                                @if(request()->has('menu')  && !empty(request()->input("menu")))
                                    <div id="menu-settings-column" class="metabox-holder col-lg-3 col-12">

                                        <div class="clear"></div>

                                        <form id="nav-menu-meta" action="" class="nav-menu-meta" method="post" enctype="multipart/form-data">
                                            <div id="side-sortables" class="accordion-container">
                                                <ul class="outer-border">
                                                    <li class="control-section accordion-section  open add-page" id="add-page">
                                                        <h3 class="accordion-section-title hndle" tabindex="0"> Custom Link <span class="screen-reader-text">Press return or enter to expand</span></h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">
                                                                    <p id="menu-item-url-wrap">
                                                                        <label class="howto" for="custom-menu-item-url"> <span>URL</span>&nbsp;&nbsp;&nbsp;
                                                                            <input id="custom-menu-item-url" name="url" type="text" class="menu-item-textbox " placeholder="url">
                                                                        </label>
                                                                    </p>

                                                                    <p id="menu-item-name-wrap">
                                                                        <label class="howto" for="custom-menu-item-name"> <span>Label</span>&nbsp;
                                                                            <input id="custom-menu-item-name" name="label" type="text" class="regular-text menu-item-textbox input-with-default-title" title="Label menu">
                                                                        </label>
                                                                    </p>

                                                                    @if(!empty($roles))
                                                                        <p id="menu-item-role_id-wrap">
                                                                            <label class="howto" for="custom-menu-item-name"> <span>Role</span>&nbsp;
                                                                                <select id="custom-menu-item-role" name="role">
                                                                                    <option value="0">Select Role</option>
                                                                                    @foreach($roles as $role)
                                                                                        <option value="{{ $role->$role_pk }}">{{ ucfirst($role->$role_title_field) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </label>
                                                                        </p>
                                                                    @endif

                                                                    <p class="button-controls">

                                                                        <a  href="#" onclick="addcustommenu()"  class="button-secondary submit-add-to-menu right"  >Add menu item</a>
                                                                        <span class="spinner" id="spincustomu"></span>
                                                                    </p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </form>

                                        @if($categories)
                                            <div id="side-sortables" class="accordion-container mt-4">
                                                <ul class="outer-border">
                                                    <li class="control-section accordion-section">
                                                        <h3 class="accordion-section-title hndle"
                                                            data-toggle="collapse"
                                                            data-target="#categories"
                                                            aria-expanded="false" aria-controls="categories"
                                                            id="headingTwo"
                                                            tabindex="0"> {{ __('strings.backend.menu_manager.categories') }}
                                                            <span
                                                                class="screen-reader-text">{{ __('strings.backend.menu_manager.screen_reader_text') }}</span>
                                                        </h3>

                                                        <div id="categories" class="collapse">
                                                            <div class="card-body px-3 pt-3  pb-0">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           placeholder="Search Categories"
                                                                           class="form-control searchInput mb-3">
                                                                    <div class="checkbox-wrapper category">
                                                                        @if($categories->count() > 0)
                                                                            @foreach(($categories) as $item)
                                                                                {{--                                                                            @if($item->children->count() == 0)--}}
                                                                                <div class="checkbox"
                                                                                     data-value="{{$item->name}}">
                                                                                    <label><input type="checkbox" name="category[]" id="category[]" value="{{ $item->id }}"> @if($item->parent_id)- @endif&nbsp;{{ $item->name }}</label>
                                                                                </div>
                                                                                {{--                                                                            @else--}}
                                                                                {{--                                                                                <div class="checkbox"--}}
                                                                                {{--                                                                                     data-value="{{$item->name}}">--}}
                                                                                {{--                                                                                    <label><input type="checkbox" name="category[]" id="category[]" value="{{ $item->id }}"> &nbsp;{{ $item->name }}</label>--}}
                                                                                {{--                                                                                </div>--}}
                                                                                {{--                                                                                @foreach(($item->children) as $sub_item)--}}
                                                                                {{--                                                                                    <div class="checkbox"--}}
                                                                                {{--                                                                                         data-value="{{$sub_item->name}}"--}}
                                                                                {{--                                                                                         style="margin-left: 20px;">--}}
                                                                                {{--                                                                                        <label><input type="checkbox" name="category[]" id="category[]" value="{{ $sub_item->id }}"> &nbsp;{{ $sub_item->name }}</label>--}}
                                                                                {{--                                                                                    </div>--}}
                                                                                {{--                                                                                @endforeach--}}
                                                                                {{--                                                                            @endif--}}
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-inline-block w-100 cat action-wrapper border-top col-12 pt-2 pb-1">
                                                                <div class="checkbox float-left">
                                                                    <label class="my-2"><input type="checkbox" class="select_all" name="category[]" id="category[]"> &nbsp {{  __('strings.backend.menu_manager.select_all') }}</label>

                                                                </div>
                                                                <button class="btn btn-light add-to-menu border float-right">
                                                                    {{ __('strings.backend.menu_manager.add_to_menu') }}
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif

                                        @if($posts)
                                            <div class="accordion-container mt-4">
                                                <ul class="outer-border">
                                                    <li class="control-section accordion-section">
                                                        <h3 class="accordion-section-title hndle"
                                                            data-toggle="collapse"
                                                            data-target="#posts"
                                                            aria-expanded="false" aria-controls="posts"
                                                            id="headingFour"
                                                            tabindex="0"> {{ __('strings.backend.menu_manager.posts') }}
                                                            <span class="screen-reader-text">{{ __('strings.backend.menu_manager.screen_reader_text') }}</span>
                                                        </h3>
                                                        <div id="posts" class="collapse">
                                                            <div class="card-body px-3 pt-3  pb-0">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           placeholder="Search Posts"
                                                                           class="form-control searchInput mb-3">
                                                                    <div class="checkbox-wrapper post">
                                                                        @if($posts->count() > 0)
                                                                            @foreach($posts as $item)
                                                                                <div class="checkbox"
                                                                                     data-value="{{$item->title}}">
                                                                                    <label><input type="checkbox" name="post[]" id="post[]" value="{{ $item->id }}"> &nbsp;{{ $item->title }}</label>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-inline-block action-wrapper post w-100 border-top col-12 pt-2 pb-1">
                                                                <div class="checkbox float-left">
                                                                    <label class="my-2"><input type="checkbox" class="select_all" name="post[]" id="post[]"> &nbsp {{  __('strings.backend.menu_manager.select_all') }}</label>
                                                                </div>
                                                                <button class="btn btn-light add-to-menu border float-right">
                                                                    {{ __('strings.backend.menu_manager.add_to_menu') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                        @endif

                                        @if($pages)
                                            <div class="accordion-container mt-4">
                                                <ul class="outer-border">
                                                    <li class="control-section accordion-section">
                                                        <h3 class="accordion-section-title hndle"
                                                            data-toggle="collapse"
                                                            data-target="#pages"
                                                            aria-expanded="false" aria-controls="pages"
                                                            id="headingFour"
                                                            tabindex="0"> {{ __('strings.backend.menu_manager.pages') }}
                                                            <span class="screen-reader-text">{{ __('strings.backend.menu_manager.screen_reader_text') }}</span>
                                                        </h3>
                                                        <div id="pages" class="collapse">
                                                            <div class="card-body px-3 pt-3  pb-0">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                           placeholder="Search Posts"
                                                                           class="form-control searchInput mb-3">
                                                                    <div class="checkbox-wrapper page">
                                                                        @if($pages->count() > 0)
                                                                            @foreach($pages as $item)
                                                                                <div class="checkbox"
                                                                                     data-value="{{$item->title}}">
                                                                                    <label><input type="checkbox" name="page[]" id="page[]" value="{{ $item->id }}"> &nbsp;{{ $item->title }}</label>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-inline-block action-wrapper post w-100 border-top col-12 pt-2 pb-1">
                                                                <div class="checkbox float-left">
                                                                    <label class="my-2"><input type="checkbox" class="select_all" name="page[]" id="page[]"> &nbsp {{  __('strings.backend.menu_manager.select_all') }}</label>
                                                                </div>
                                                                <button class="btn btn-light add-to-menu border float-right">
                                                                    {{ __('strings.backend.menu_manager.add_to_menu') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>

                                        @endif

                                    </div>
                                @endif
                                <div id="menu-management-liquid" class="col-lg-9 col-12">
                                    <div id="menu-management">
                                        <form id="update-nav-menu" action="" method="post" enctype="multipart/form-data">
                                            <div class="menu-edit ">
                                                <div id="nav-menu-header">
                                                    <div class="major-publishing-actions">
                                                        <label class="menu-name-label howto open-label" for="menu-name"> <span>Name</span>
                                                            <input name="menu-name" id="menu-name" type="text" class="menu-name regular-text menu-item-textbox" title="Enter menu name" value="@if(isset($indmenu)){{$indmenu->name}}@endif">
                                                            <input type="hidden" id="idmenu" value="@if(isset($indmenu)){{$indmenu->id}}@endif" />
                                                        </label>

                                                        @if(request()->has('action'))
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
                                                            </div>
                                                        @elseif(request()->has("menu"))
                                                            <div class="publishing-action">
                                                                <a onclick="getmenus()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Save menu</a>
                                                                <span class="spinner" id="spincustomu2"></span>
                                                            </div>

                                                        @else
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div id="post-body">
                                                    <div id="post-body-content">

                                                        @if(request()->has("menu"))
                                                            <h3>Menu Structure</h3>
                                                            <div class="drag-instructions post-body-plain" style="">
                                                                <p>
                                                                    Place each item in the order you prefer. Click on the arrow to the right of the item to display more configuration options.
                                                                </p>
                                                            </div>

                                                        @else
                                                            <h3>Menu Creation</h3>
                                                            <div class="drag-instructions post-body-plain" style="">
                                                                <p>
                                                                    Please enter the name and select "Create menu" button
                                                                </p>
                                                            </div>
                                                        @endif

                                                        <ul class="menu ui-sortable" id="menu-to-edit">
                                                            @if(isset($menus))
                                                                @foreach($menus as $m)
                                                                    <li id="menu-item-{{$m->id}}" class="menu-item menu-item-depth-{{$m->depth}} menu-item-page menu-item-edit-inactive pending" style="display: list-item;">
                                                                        <dl class="menu-item-bar">
                                                                            <dt class="menu-item-handle">
                                                                                <span class="item-title"> <span class="menu-item-title"> <span id="menutitletemp_{{$m->id}}">{{$m->label}}</span> <span style="color: transparent;">|{{$m->id}}|</span> </span> <span class="is-submenu" style="@if($m->depth==0)display: none;@endif">Subelement</span> </span>
                                                                                <span class="item-controls"> <span class="item-type">{{$m->type}}</span> <span class="item-order hide-if-js"> <a href="{{ $currentUrl }}?action=move-up-menu-item&menu-item={{$m->id}}&_wpnonce=8b3eb7ac44" class="item-move-up"><abbr title="Move Up">↑</abbr></a> | <a href="{{ $currentUrl }}?action=move-down-menu-item&menu-item={{$m->id}}&_wpnonce=8b3eb7ac44" class="item-move-down"><abbr title="Move Down">↓</abbr></a> </span> <a class="item-edit" id="edit-{{$m->id}}" title=" " href="{{ $currentUrl }}?edit-menu-item={{$m->id}}#menu-item-settings-{{$m->id}}"> </a> </span>
                                                                            </dt>
                                                                        </dl>

                                                                        <div class="menu-item-settings" id="menu-item-settings-{{$m->id}}">
                                                                            <input type="hidden" class="edit-menu-item-id" name="menuid_{{$m->id}}" value="{{$m->id}}" />
                                                                            <p class="description description-thin">
                                                                                <label for="edit-menu-item-title-{{$m->id}}"> Label
                                                                                    <br>
                                                                                    <input type="text" id="idlabelmenu_{{$m->id}}" class="widefat edit-menu-item-title" name="idlabelmenu_{{$m->id}}" value="{{$m->label}}">
                                                                                </label>
                                                                            </p>

                                                                            <p class="field-css-classes description description-thin">
                                                                                <label for="edit-menu-item-classes-{{$m->id}}"> Class CSS (optional)
                                                                                    <br>
                                                                                    <input type="text" id="clases_menu_{{$m->id}}" class="widefat code edit-menu-item-classes" name="clases_menu_{{$m->id}}" value="{{$m->class}}">
                                                                                </label>
                                                                            </p>

                                                                            <p class="field-css-url description description-wide">
                                                                                <label for="edit-menu-item-url-{{$m->id}}"> Url
                                                                                    <br>
                                                                                    <input type="text" id="url_menu_{{$m->id}}" class="widefat code edit-menu-item-url" id="url_menu_{{$m->id}}" value="{{$m->link}}" @if(in_array($m->type, ['Category','Post'])) readonly @endif>
                                                                                </label>
                                                                            </p>

                                                                            @if(!empty($roles))
                                                                                <p class="field-css-role description description-wide">
                                                                                    <label for="edit-menu-item-role-{{$m->id}}"> Role
                                                                                        <br>
                                                                                        <select id="role_menu_{{$m->id}}" class="widefat code edit-menu-item-role" name="role_menu_[{{$m->id}}]" >
                                                                                            <option value="0">Select Role</option>
                                                                                            @foreach($roles as $role)
                                                                                                <option @if($role->id == $m->role_id) selected @endif value="{{ $role->$role_pk }}">{{ ucwords($role->$role_title_field) }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </label>
                                                                                </p>
                                                                            @endif

                                                                            <p class="field-move hide-if-no-js description description-wide">
                                                                                <label> <span>Move</span> <a href="{{ $currentUrl }}" class="menus-move-up" style="display: none;">Move up</a> <a href="{{ $currentUrl }}" class="menus-move-down" title="Mover uno abajo" style="display: inline;">Move Down</a> <a href="{{ $currentUrl }}" class="menus-move-left" style="display: none;"></a> <a href="{{ $currentUrl }}" class="menus-move-right" style="display: none;"></a> <a href="{{ $currentUrl }}" class="menus-move-top" style="display: none;">Top</a> </label>
                                                                            </p>

                                                                            <div class="menu-item-actions description-wide submitbox">

                                                                                <a class="item-delete submitdelete deletion" id="delete-{{$m->id}}" href="{{ $currentUrl }}?action=delete-menu-item&menu-item={{$m->id}}&_wpnonce=2844002501">Delete</a>
                                                                                <span class="meta-sep hide-if-no-js"> | </span>
                                                                                <a class="item-cancel submitcancel hide-if-no-js button-secondary" id="cancel-{{$m->id}}" href="{{ $currentUrl }}?edit-menu-item={{$m->id}}&cancel=1424297719#menu-item-settings-{{$m->id}}">Cancel</a>
                                                                                <span class="meta-sep hide-if-no-js"> | </span>
                                                                                <a onclick="getmenus()" class="button button-primary updatemenu" id="update-{{$m->id}}" href="javascript:void(0)">Update item</a>

                                                                            </div>

                                                                        </div>
                                                                        <ul class="menu-item-transport"></ul>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                        <div class="menu-settings">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="nav-menu-footer">
                                                    <div class="major-publishing-actions">

                                                        @if(request()->has('action'))
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
                                                            </div>
                                                        @elseif(request()->has("menu"))
                                                            <span class="delete-action"> <a class="submitdelete deletion menu-delete" onclick="deletemenu()" href="javascript:void(9)">Delete menu</a> </span>
                                                            <div class="publishing-action">

                                                                <a onclick="getmenus()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Save menu</a>
                                                                <span class="spinner" id="spincustomu2"></span>
                                                            </div>

                                                        @else
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
        var i, menu;

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

        //Changing edit link on location change
        $('#menu-locations-wrap').find('select').on('change', function () {
            if ($(this).val() == "") {
                $(this).find('option:first').attr('selected', true);
                $(this).siblings('a').attr('href', "{{route('admin.menu-manager')}}").text('Create New');
            } else {
                $(this).siblings('a').attr('href', "{{route('admin.menu-manager')}}?menu=" + $(this).val()).text('Edit')

            }
        });

        //Custom-link, post, page, category add to menu.
        $(document).on('click', '.add-to-menu', function () {
            var value, link, type, label;
            var data = [];

            var card = $(this).parents('.action-wrapper').siblings('.card-body');

            var checked = $(card).find('.checkbox-wrapper input:checked');
            if (checked.length > 0) {
                $(checked).each(function () {
                    if ($(card).find('.checkbox-wrapper').hasClass('category')) {
                        link = '/category';
                        type = 'category';
                    } else if ($(card).find('.checkbox-wrapper').hasClass('page')) {
                        type = 'page';

                    } else if ($(card).find('.checkbox-wrapper').hasClass('post')) {
                        link = '/post'
                        type = 'post';

                    } else {
                        link = 'Custom Link'
                        type = 'custom-link';

                    }
                    value = $(this).val();
                    label = $(this).parent('label').text().trim();
                    label = $(this).parent('label').text().trim();
                    data.push({
                        labelmenu: label,
                        link: link,
                        item_id: value,
                        type: type,
                        idmenu: $("#idmenu").val()
                    });


                });
                $.ajax({
                    data: {data: data},
                    url: '{{route('admin.hsaveCustomItem')}}',
                    type: 'POST',
                    success: function (response) {
                        window.location = "";
                    },
                    complete: function () {
                        $("#spincustomu").hide();
                    }

                });
            }
        });
    </script>
@endpush

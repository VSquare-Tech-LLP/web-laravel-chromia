@extends('backend.layouts.app')

@section('title', 'Add New Form')


@section('content')
    <div class="card mt-5">
        <x-forms.post :action="route('admin.forms.store')">
            <x-backend.card>
                <x-slot name="header">
                    Add New Form
                </x-slot>

                <x-slot name="headerActions">
                    <x-utils.link class="card-header-action" :href="route('admin.forms.index')" :text="'Cancel'" />
                </x-slot>

                @include('backend.forms.form', ['form' => optional()])

                <x-slot name="footer">
                    <button class="btn btn-sm btn-primary float-right" type="submit">Save</button>
                </x-slot>
            </x-backend.card>
        </x-forms.post>
    </div>

@endsection

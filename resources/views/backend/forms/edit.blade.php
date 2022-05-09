@extends('backend.layouts.app')

@section('title', 'Edit Form')

@section('content')

    <div class="card mt-5">
        <x-forms.post :action="route('admin.forms.update',['form' => $form])">
            @method('PUT')
            <x-backend.card>
                <x-slot name="header">
                    Edit Form
                </x-slot>

                <x-slot name="headerActions">
                    <x-utils.link class="card-header-action" :href="route('admin.forms.index')" :text="'Cancel'" />
                </x-slot>

                @include('backend.forms.form')

                <x-slot name="footer">
                    <button class="btn btn-sm btn-primary float-right" type="submit">Save</button>
                </x-slot>
            </x-backend.card>
        </x-forms.post>
    </div>
@endsection

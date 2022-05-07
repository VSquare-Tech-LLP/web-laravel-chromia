@extends('backend.layouts.app')

@section('title', __('File Manager'))
@php
    $url = url('filemanager?type=images');
@endphp
@push('after-styles')
    <style>
        .main-heading{
            border-radius: 0;
            color: #fff;
            background-color: #2b363c;
            border-color: #2b363c;
        }
        .main-heading h4{
            margin-bottom: 0px;
        }
        .main-heading .panel-heading{
            padding: 10px 20px!important;
        }
    </style>
@endpush
@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('File Manager')
        </x-slot>
        <x-slot name="body">
            <iframe name="filemanager" onload="init_iframe(window.filemanager);" id="filemanager" style="width: 100%;min-height: 70vh;padding-top: 20px;background-color: white" frameborder="0"
                    src="{{$url}}">
            </iframe>
        </x-slot>
    </x-backend.card>


@endsection

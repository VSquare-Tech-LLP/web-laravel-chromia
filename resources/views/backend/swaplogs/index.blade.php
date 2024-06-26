@extends('backend.layouts.app')

@section('title', __('Swap Log'))


@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Swap Log')
        </x-slot>

        <x-slot name="body">
            <livewire:backend.swap-logs-table />
        </x-slot>
    </x-backend.card>
@endsection

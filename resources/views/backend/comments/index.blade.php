@extends('backend.layouts.app')

@section('title', __('Comments Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Comments')
        </x-slot>
        <x-slot name="body">
            <div class="d-block">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a
                            href="{{ route('admin.comments.index',['sorts' => ['id' => 'desc']]) }}"
                            style="{{ !request('status') ? 'font-weight: 700' : '' }}">
                            All
                        </a>
                    </li>
                    |
                    <li class="list-inline-item">
                        <a
                            href="{{ route('admin.comments.index',['status' => 'pending', 'sorts' => ['id' => 'desc']]) }}"
                            style="{{ request('status') == 'pending' ? 'font-weight: 700' : '' }}">
                            Pending
                        </a>
                    </li>
                    |
                    <li class="list-inline-item">
                        <a
                            href="{{ route('admin.comments.index',['status' => 'approved', 'sorts' => ['id' => 'desc']]) }}"
                            style="{{ request('status') == 'approved' ? 'font-weight: 700' : '' }}">
                            Approved
                        </a>
                    </li>
                </ul>
            </div>
            <livewire:backend.comments-table/>
        </x-slot>
    </x-backend.card>
@endsection

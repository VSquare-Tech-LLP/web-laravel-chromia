@extends ('backend.layouts.app')

@section ('title', 'All Forms')

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        All Forms
                    </h4>
                </div><!--col-->

                <div class="col-sm-7">
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                        <a href="{{ route('admin.forms.create') }}" class="btn btn-success mb-2" data-toggle="tooltip" title="Create New"><i class="c-icon cil-plus"></i></a>
                    </div>
                </div><!--col-->
            </div>

            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table" id="myTable">
                            <thead>
                            <tr>
                                <th>Form</th>
                                <th>Shortcode</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($forms as $form)
                                <tr>
                                    <td>
                                        {{ $form->title }}
                                    </td>
                                    <td>
                                        [form id="{{ $form->id}}" title="{{ $form->title }}"]
                                    </td>
                                    <td>
                                        <a href="{{route('admin.forms.edit',['form' => $form->id])}}"
                                           class="btn btn-sm btn-primary" data-toggle="tooltip"
                                           data-placement="top"
                                           title="edit">Edit</a>


                                        <x-utils.delete-button :href="route('admin.forms.destroy',  ['form' => $form->id])" />
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script src="{{asset('plugins/dataTables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                order: [
                    [0, 'desc']
                ],
            });
        });
    </script>
@endpush

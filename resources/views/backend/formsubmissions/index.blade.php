@extends ('backend.layouts.app')

@section ('title', 'Form Submission')

@push('after-styles')
    <style href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"></style>
@endpush

@section('content')

    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        Form Submission
                    </h4>

                </div><!--col-->
                <div class="col-sm-12">
                    <form class="mt-4">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <select name="form" class="form-control" required>
                                        <option value="">Select Form</option>
                                        @forelse($forms as $f)
                                            <option value="{{ $f->id }}">{{ $f->title }}</option>
                                        @empty
                                        @endforelse
                                    </select>

                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <button type="submit" name="submit"
                                            class="btn btn-primary">View</button>
                                    <a href="{{ url()->current() }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table" id="myTable">
                            <thead>
                                <tr>
                                    <th class="d-none">Id</th>
                                    <th>Form</th>
                                    @php
                                        if($form->count()){
                                            $formField = json_decode($form->form_template,true);
                                            $fieldCount = 0;
                                            foreach ($formField as $field){
                                                 if(in_array($field['type'],['text','textarea','number','select','paragraph'])){
                                                    echo '<th>'.ucfirst($field['label']).'</th>';
                                                    $fieldCount++;
                                                 }
                                            }
                                        }
                                    @endphp
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($submissions as $submission)
                                <tr>
                                    <td class="d-none">{{ $submission->id }}</td>
                                    <td>
                                        {{ $submission->form->title }}
                                    </td>
                                    @php
                                        $data = json_decode($submission->form_data,true);
                                        $fillableCount = 0;

                                        if($data){
                                            foreach ($data as $key => $value){
                                                $fillableCount++;
                                                if($fillableCount <= $fieldCount){
                                                    echo '<td>'.$value.'</td>';
                                                }
                                            }
                                            for($f=0; $f < ($fieldCount-$fillableCount);$f++){
                                              echo '<td></td>';
                                            }

                                        }
                                    @endphp
                                    <td>
                                        <x-utils.delete-button :href="route('admin.form-submission.destroy',  ['form_submission' => $submission->id])" />
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
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                order: [
                    [0, 'desc']
                ],
                dom: 'Bfrtip',
                buttons: [
                    'csvHtml5',
                ]
            });
        });
    </script>
@endpush

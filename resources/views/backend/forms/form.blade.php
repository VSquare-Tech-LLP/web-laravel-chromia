<x-slot name="body">
    <div>
        <h5>Form Setting</h5>
        <div class="form-group row">
            <label for="title" class="col-md-2 col-form-label">Title</label>

            <div class="col-md-10">
                <input type="text" name="title" class="form-control"
                       placeholder="Title"
                       value="{{ old('title',$form->title) }}" required/>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-12">
        <div class="build-wrap"></div>
            </div>
        </div>

        <div class="form-group row">
            <label for="status" class="col-md-2 col-form-label">Status</label>
            <div class="checkbox col-md-10">
                <label class="c-switch c-switch-3d c-switch-primary">
                    <input class="c-switch-input" name="status" type="checkbox" id="status"
                           value="1" {{ $form->status? 'checked':'' }}>
                    <span class="c-switch-slider"></span>
                </label>
            </div>
            <input type="hidden" name="form_template" value="{{ $form->form_template }}">
        </div>

        <div class="form-group row">
            <label for="captcha" class="col-md-2 col-form-label">Captcha</label>
            <div class="checkbox col-md-10">
                <label class="c-switch c-switch-3d c-switch-primary">
                    <input class="c-switch-input" name="captcha" type="checkbox" id="captcha"
                           value="1" {{ $form->captcha? 'checked':'' }}>
                    <span class="c-switch-slider"></span>
                </label>
            </div>
        </div>

        <h5>Mail Setting</h5>

        <div class="form-group row">
            <label for="mail_to" class="col-md-2 col-form-label">Mail To</label>

            <div class="col-md-10">
                <input type="text" name="mail_to" class="form-control"
                       placeholder="Mail To"
                       value="{{ old('mail_to',$form->mail_to) }}" required/>
            </div>
        </div>

        <div class="form-group row">
            <label for="mail_from" class="col-md-2 col-form-label">Mail From</label>

            <div class="col-md-10">
                <input type="text" name="mail_from" class="form-control"
                       placeholder="Mail From"
                       value="{{ old('mail_from',$form->mail_from) }}" required/>
            </div>
        </div>

        <div class="form-group row">
            <label for="mail_subject"
                   class="col-md-2 col-form-label">Mail Subject</label>

            <div class="col-md-10">
                <input type="text" name="mail_subject" class="form-control"
                       placeholder="Mail Subject"
                       value="{{ old('mail_subject',$form->mail_subject) }}" required/>
            </div>
        </div>
    </div>
</x-slot>


@push('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    {{-- <script src="https://formbuilder.online/assets/js/form-builder.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-formBuilder/3.8.1/form-builder.min.js"></script>
    <script>
        var options = {
            controlPosition: 'left',
            disableFields: ['autocomplete', 'checkbox-group', 'header', 'file', 'hidden', 'date', 'radio-group'],
            disabledActionButtons: ['data', 'save'],
            subtypes: {
                text: ['url']
            },
            dataType: 'json',
            formData: $('input[name=form_template]').val(),
        };

        var formBuilder = $('.build-wrap').formBuilder(options);

        $(document).on('click', 'button[type="submit"]', function (e) {
            $('input[name=form_template]').val(formBuilder.actions.getData('json', true));
        })
    </script>
@endpush

@php
    $form = \App\Models\Form::where('id',$id)->where('status',1)->first();
@endphp
@if($form)
    <h2>{{ $form->title }}</h2>
    <div class="formbuilder">
        <form method="post" action="">
            <div class="d-none">
                <input type="hidden" name="id" value="{{ $form->id }}">
            </div>
            <div class="form-wrap"></div>
            @csrf
            @if($form->captcha)
            <div class="mb-3 col-md-12 col-xs-12 text-center mx-auto">
                <div class="text-center d-inline-block">
                    {!! NoCaptcha::display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <div class="help-block">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            @endif
            <div class="form-message-notify"></div>
        </form>
    </div>
    @push('after-scripts')
        <script src="https://formbuilder.online/assets/js/form-render.min.js"></script>
        <script>
            $('.form-wrap').formRender({
                dataType: 'json',
                formData: {!! $form->form_template !!},
            });
            $(document).on('submit', '.formbuilder form', function (event) {
                event.preventDefault();
                self = $(this);
                self.find('input[type="submit"]').attr('disabled', true);
                self.find('button[type="submit"]').attr('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.form-submission.store') }}',
                    data: new FormData(this),
                    dataType: 'json',
                    processData: false,
                    contentType: false
                }).done(function (data, status, xhr) {
                        // $('.form-message-notify').text(data.message);
                        self.trigger("reset");
                        location.reload();
                        self.find('input[type="submit"]').attr('disabled', false);
                        self.find('button[type="submit"]').attr('disabled', false);
                        alert(data.message);
                }).fail(function (xhr, status, error) {
                    alert('Select the captcha');
                    self.find('input[type="submit"]').attr('disabled', false);
                    self.find('button[type="submit"]').attr('disabled', false);
                });
            })
        </script>
        @if($form->captcha)
            {!! NoCaptcha::renderJs() !!}
        @endif
    @endpush
@endif



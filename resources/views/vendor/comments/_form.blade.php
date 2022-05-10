<div class="comment-form">
    <div class="subtitle">@lang('comments::comments.any_comment')</div>
    <p>@lang('comments::comments.comment_box_instruction')</p>
    <form method="POST" action="{{ route('comments.store') }}">
        @csrf
        <input type="hidden" name="commentable_type" value="\{{ get_class($model) }}" />
        <input type="hidden" name="commentable_id" value="{{ $model->getKey() }}" />
        @if($errors->has('commentable_type'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('commentable_type') }}
            </div>
        @endif
        @if($errors->has('commentable_id'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('commentable_id') }}
            </div>
        @endif
        <div class="row">
            {{-- Guest commenting --}}
            @if(isset($guest_commenting) and $guest_commenting == true)
            <div class="col-lg-6 col-12">
                <div class="form-group">
                    <label for="name">@lang('comments::comments.enter_your_name_here')</label>
                    <input type="text" id="name" class="form-control @if($errors->has('guest_name')) is-invalid @endif" name="guest_name">
                    @error('guest_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-6 col-12">
                <div class="form-group">
                    <label for="email">@lang('comments::comments.enter_your_email_here')</label>
                    <input type="text" id="email" class="form-control @if($errors->has('guest_email')) is-invalid @endif" name="guest_email">
                    @error('guest_email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            @endif

            <div class="col-12">
                <div class="form-group">
                    <label for="message">@lang('comments::comments.enter_your_message_here')</label>
                    <textarea id="message" class="form-control @if($errors->has('message')) is-invalid @endif" name="message"></textarea>
                    <div class="invalid-feedback">
                        @lang('comments::comments.your_message_is_required')
                    </div>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-comment">@lang('comments::comments.post_comment_button_text')</button>
            </div>
        </div>
    </form>
</div>
<br />

    <x-livewire-tables::bs4.table.cell>
        <strong>
        {{ $row->commenter->name ?? $row->guest_name }}
        </strong>
        <br>
        <a href="mailto:{{$row->commenter->email ??  $row->guest_email }}">{{ $row->commenter->email ??  $row->guest_email }}</a>
    </x-livewire-tables::bs4.table.cell>

    <x-livewire-tables::bs4.table.cell>
        {{ $row->comment }}
    </x-livewire-tables::bs4.table.cell>

    <x-livewire-tables::bs4.table.cell>
        <a href="{{ route('admin.posts.edit', ['post' => $row->commentable]) }} ">{{ $row->commentable->title }}</a>
    </x-livewire-tables::bs4.table.cell>

    <x-livewire-tables::bs4.table.cell>
        {{ $row->created_at->format('d/m/Y') }}
    </x-livewire-tables::bs4.table.cell>

    <x-livewire-tables::bs4.table.cell>
        <a href="{{ route('frontend.single-post', ['slug' => $row->commentable->slug]) }} " target="_blank">View</a> |
        <a href="#" data-toggle="modal" data-target="#comment-modal-{{ $row->getKey() }}">@lang('comments::comments.edit')</a> |
        <a href="#" data-toggle="modal" data-target="#reply-modal-{{ $row->getKey() }}">@lang('comments::comments.reply')</a>
        @if($row->approved == 1)
        |
        <a href="{{ route('admin.comments.status',['status' => 0,'comment' => $row]) }}">@lang('comments::comments.unapprove')</a>
        @else
        |
        <a href="{{ route('admin.comments.status',['status' => 1,'comment' => $row]) }}">@lang('comments::comments.approve')</a>
        @endif
        |
        <a href="{{ route('admin.comments.destroy', ['comment' => $row->getKey()]) }}" onclick="event.preventDefault();document.getElementById('comment-delete-form-{{ $row->getKey() }}').submit();" >@lang('comments::comments.delete')</a>
        <form id="comment-delete-form-{{ $row->getKey() }}" action="{{ route('admin.comments.destroy', ['comment' => $row->getKey()]) }}" method="POST" style="display: none;">
            @method('DELETE')
            @csrf
        </form>
    </x-livewire-tables::bs4.table.cell>

    @can('reply-to-comment', $row)
        <div class="modal fade" id="reply-modal-{{ $row->getKey() }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('comments.reply', $row->getKey()) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('comments::comments.reply_to_comment')</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="message">@lang('comments::comments.enter_your_message_here')</label>
                                <textarea required class="form-control" name="message" rows="3"></textarea>
                                <small class="form-text text-muted">@lang('comments::comments.markdown_cheatsheet', ['url' => 'https://help.github.com/articles/basic-writing-and-formatting-syntax'])</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">@lang('comments::comments.cancel')</button>
                            <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">@lang('comments::comments.reply')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('edit-comment', $row)
        <div class="modal fade" id="comment-modal-{{ $row->getKey() }}" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('comments.update', $row->getKey()) }}">
                        @method('PUT')
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">@lang('comments::comments.edit_comment')</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="message">@lang('comments::comments.update_your_message_here')</label>
                                <textarea required class="form-control" name="message" rows="3">{{ $row->comment }}</textarea>
                                <small class="form-text text-muted">@lang('comments::comments.markdown_cheatsheet', ['url' => 'https://help.github.com/articles/basic-writing-and-formatting-syntax'])</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">@lang('comments::comments.cancel')</button>
                            <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">@lang('comments::comments.update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@endpush

<x-slot name="body">
    <div>
        <input type="hidden" name="old_thumb"  id="old_thumb" value="{{ $image->image }}" />
        <div class="mb-3 row">
            <label for="pack_id" class="col-md-2 col-form-label">@lang('Pack')</label>
            <div class="col-md-4">
                <select name="category_id" class="form-select" aria-label="Pack">
                    <option selected >-- Select Category --</option>
                    @foreach ($categorylist as $singlepack)
                        <option value="{{ $singlepack->id }}" {{ $singlepack->id == $image->category_id ? ' selected ' : '' }} >{{ $singlepack->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="thumbnail" class="col-md-2 col-form-label">Image</label>
            <div class="col-md-6">
                <input id="image" name="image" type="file" placeholder="Upload" />
            </div>
        </div>

        

        @if (isset($image->id) && $image->id > 0)
            
            <div class="mb-3 row">
                <div class="col-2"></div>
                <div class="col-10">
                    <img width="100" src="{{ $image->url }}" alt=" " />
                </div>
            </div>
            
        @endif

        

    </div>
    
</x-slot>

@push('after-scripts')
    <script>
        
    </script>
@endpush

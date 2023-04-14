@props(['dismissable' => true, 'type' => 'success', 'ariaLabel' => __('Close'), 'backend' => false])

<div {{ $attributes->merge(['class' => 'alert alert-dismissible alert-'.$type]) }} role="alert">
    {{ $slot }}

    @if ($dismissable)
    <button type="button" class="btn-close {{ $backend ? 'yes': 'no '}}" data-{{ $backend ? 'coreui' : 'bs' }}-dismiss="alert" aria-label="{{ $ariaLabel }}"></button>
    @endif
</div>
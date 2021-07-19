<div
    x-data="editorInstance('data', '{{ 'id'.md5($editorId) }}', {{ $readOnly ? 'true' : 'false' }}, '{{ $placeholder }}', '{{ $logLevel }}')"
    x-init="init()"
    class="{{ $class }}"
    style="{{ $style }}"
wire:ignore
>
    <div id="{{ 'id'.md5($editorId) }}"></div>
</div>

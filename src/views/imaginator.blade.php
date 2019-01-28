<picture @if(!empty($class)) class='{{$class}}' @endif>
    {!! imaginatorGenUrls($id, $alt = '', $formats = [], $options = [], $sets = []) !!}
</picture>
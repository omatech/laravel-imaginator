<picture @if(!empty($class)) class='{{$class}}' @endif>
    @php
        $alt = (!empty($alt)) ? $alt : '';
        $format = (!empty($formats)) ? $formats : [];
        $options = (!empty($options)) ? $options : [];
        $sets = (!empty($sets)) ? $sets : [];

    @endphp
    {!! imaginatorGenUrls($id, $alt, $formats, $options, $sets) !!}
</picture>
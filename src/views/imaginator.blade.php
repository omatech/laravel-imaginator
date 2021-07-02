<picture @if(!empty($class)) class='{{$class}}' @endif @if(!empty($loading)) loading='{{$loading}}' @endif>
    @php $alt = (!empty($alt)) ? $alt : ''; $formats = (!empty($formats)) ? $formats : []; $options = (!empty($options)) ? $options
    : []; $sets = (!empty($sets)) ? $sets : []; 
@endphp {!! imaginatorGenUrls($id, $alt, $formats, $options, $sets) !!}
</picture>
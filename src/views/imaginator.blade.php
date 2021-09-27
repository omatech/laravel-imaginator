<picture {{ $attributes }} @endif >
    @php 
        $alt = (!empty($alt)) ? $alt : ''; 
        $formats = (!empty($formats)) ? $formats : []; 
        $options = (!empty($options)) ? $options: []; 
        $sets = (!empty($sets)) ? $sets : [];
        $loading = (!empty($loading)) ? $loading : '';
    @endphp 
    {!! imaginatorGenUrls($id, $alt, $formats, $options, $sets, $loading) !!}
</picture>

<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="nav-header {{ $item['class'] ?? '' }}">

    {{ __(is_string($item) ? $item : $item['header']) }}

</li>

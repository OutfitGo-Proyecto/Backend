@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@php
    $logoPath = public_path('img/logo.png');
    $logoSrc = null;
    if (file_exists($logoPath)) {
        try {
            $logoSrc = $message->embed($logoPath);
        } catch (\Exception $e) {
            $logoSrc = config('app.url') . '/img/logo.png';
        }
    }
@endphp

@if ($logoSrc)
    <img src="{{ $logoSrc }}" class="logo" alt="{{ config('app.name') }}" style="max-height: 50px;">
@elseif (trim($slot) === 'OutfitGo' || trim($slot) === 'Laravel')
    <img src="{{ config('app.url') }}/img/logo.png" class="logo" alt="{{ config('app.name') }}" style="max-height: 50px;">
@else
    {!! $slot !!}
@endif
</a>
</td>
</tr>

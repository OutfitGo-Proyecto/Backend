@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'OutfitGo' || trim($slot) === 'Laravel')
<img src="{{ config('app.url') }}/img/logo.png" class="logo" alt="{{ config('app.name') }}" style="max-height: 50px;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>

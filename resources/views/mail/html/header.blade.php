<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (!empty(setting('global.adminlogo')))
<img src="{{ setting('global.adminlogo') }}" alt="">
@else
<img src="{{ kompass_asset('kompass_logo.png')}}" class="kompasslogo" alt="Kompass Logo">
@endif
</a>
</td>
</tr>

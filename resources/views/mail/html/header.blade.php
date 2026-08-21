<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (!empty(setting_image('global.adminlogo')))
<img src="{{ url(setting_image('global.adminlogo')) }}" alt="">
@else
<img src="{{ kompass_asset('kompass_logo.png')}}" class="kompasslogo" alt="Kompass Logo">
@endif
</a>
</td>
</tr>

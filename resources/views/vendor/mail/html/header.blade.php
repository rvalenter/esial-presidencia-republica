@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="https://sso.acesso.gov.br/assets/govbr/img/govbr.png" class="logo" alt="logo gov.br">
@endif
</a>
</td>
</tr>

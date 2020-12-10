@component('mail::message')
# {{ __('Your Marmelade Groups') }}

{{ trans_choice('messages.notification.account.invitations.message', $invitations->count()) }}
<br><br>
{{ __('⚠ You must click on the button from your smartphone ⚠') }}


@foreach($invitations as $invitation)

<p style="text-align: center; margin-bottom: -20px;font-family: serif">
<img src="{{ env('IMAGES_URL') . $invitation->group->logo_url }}" style="-ms-interpolation-mode: bicubic; outline: none; text-decoration: none;border-radius: 8px;" width="54" height="54"><br>
{{ $invitation->group->name }}

@component('mail::button', ['url' => $deeplink_base . $invitation->group->id . '/' . $invitation->token])
{{ __('Join Now') }}
@endcomponent
</p>

@endforeach


{{ __("If you're trying to join a specific group that isn't listed here, you can try contacting your Group Administrator for an invitation.") }}


@lang('Regards'),<br>
@endcomponent

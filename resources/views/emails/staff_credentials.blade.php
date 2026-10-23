<x-mail::message>
# Welcome to Memorial Platform

Hello **{{ $user->name }}**,

A staff account has been created for you with the role **{{ $role }}**.

You can log in to the administrative portal using the following temporary credentials:

<x-mail::panel>
**Email:** {{ $user->email }}  
**Temporary Password:** `{{ $temporaryPassword }}`
</x-mail::panel>

<x-mail::button :url="$loginUrl">
Sign In to Admin Dashboard
</x-mail::button>

> [!IMPORTANT]
> Please change your password immediately upon your first sign in to protect platform security.

Thanks,<br>
{{ config('app.name') }} Administration
</x-mail::message>

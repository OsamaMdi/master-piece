@component('mail::message')
# Category Removed: {{ $category->name }}

We would like to inform you that the category "**{{ $category->name }}**" has been deleted by the admin.

@component('mail::panel')
All tools listed under this category will no longer be visible or available for reservation.
@endcomponent

We sincerely apologize for the inconvenience.
You may reassign your tools to another category if needed.

Thanks,
**{{ config('app.name') }} Team**
@endcomponent

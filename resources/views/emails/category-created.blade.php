@component('mail::message')
# New Tool Category Added 🛠️

We’re excited to announce that a new product category "**{{ $category->name }}**" is now available on **{{ config('app.name') }}**!

@component('mail::panel')
This means you can now list tools under this new type and increase your visibility to customers!
@endcomponent

Start adding your tools under this category today from your dashboard.

@component('mail::button', ['url' => route('merchant.products.create')])
Add New Tool
@endcomponent

Thanks,
**{{ config('app.name') }} Team**
@endcomponent

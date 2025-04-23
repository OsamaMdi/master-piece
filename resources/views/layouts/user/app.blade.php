

 @include('users.navbar')

  @yield('content')

  @stack('scripts')

  @include('users.footer')



  @include('components.website-feedback-modal')

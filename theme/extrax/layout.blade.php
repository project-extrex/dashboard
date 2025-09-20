<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" property="og:description" content="{{ $description }}">
  <meta name="image" property="og:image" content="{{$favicon}}">
  <title>@yield('title', '{{ $siteName }}')</title>

  {{-- Tailwind (choose one: compiled CSS or CDN) --}}
  <link rel="stylesheet" href="/assets/css/output.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  {{-- Icons --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">

  {{-- Favicon --}}
  <link rel="icon" type="image/png" href="{{ $favicon }}"/>

  <style>
    body {
      font-family: "JetBrains Mono", monospace;
    }
  </style>

  @stack('head')
</head>
<body class="min-h-screen flex flex-col">

  @include('components.menu')

  <main role="main" class="flex-1">
    @yield('content')
  </main>

  <footer role="contentinfo" class="text-center py-4">
    © 2025 {{ $siteName }}
  </footer>

  @stack('script')
</body>
</html>
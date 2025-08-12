<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Portal de Notícias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
    <script>
      // Inicializar tema antes do carregamento da página
      (function() {
        const savedTheme = localStorage.getItem('theme')
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
          document.documentElement.classList.add('dark')
          document.documentElement.setAttribute('data-theme', 'dark')
        }
      })()
    </script>
  </head>
  <body>
    @inertia
  </body>
</html>

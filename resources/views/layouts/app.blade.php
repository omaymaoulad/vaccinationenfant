<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Application Vaccination')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Vaccination</a>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin-left: auto;">
        @csrf
        <button type="submit" class="btn btn-danger">Se déconnecter</button>
    </form>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Application Vaccination')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .bg-side{
           background: #da22ff;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #da22ff, #9733ee);
            color: white;
            padding: 20px;
        }
   
        .sidebar h4 {
            font-weight: bold;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: white;
            margin: 10px 0;
            text-decoration: none;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }
        .sidebar .text-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .sidebar img {
            display: block;
            margin: 0 auto;
            border: 4px solid white;
            border-radius: 50%;
            background: white;
        }
        .content {
            flex: 1;
            padding: 20px;
        }

        .logout-btn {
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="text-center mt-3 mb-4">
            <img src="{{ asset('images/sidebar.png') }}" alt="Profil" width="100" height="100" class="rounded-circle">
        </div>
            <h4>Vaccination</h4>
        <a href="{{ route('dashboard.admin') }}">🏠Acceuil</a>
        <a href="#">📊 Statistiques</a>
        <a href="{{ route('enfants.create') }}">👶 Enfants par secteur</a>
        <a href="#">💉 Liste de vaccination</a>
        <a href="{{ route('admin.profile') }}">👤 Profil</a>
        <a href="{{route('admin.users.index')}}">👥 Utilisateurs</a>
        <a href="{{route('admin.secteurs.index')}}">📚 Liste des secteurs</a>
        <form method="POST" action="{{ route('logout') }}" class="logout-btn">
            @csrf
            <button type="submit" class="btn btn-light w-100">Se déconnecter</button>
        </form>
    </div>

    <div class="content">
        @yield('content')
    </div>

</body>
</html>

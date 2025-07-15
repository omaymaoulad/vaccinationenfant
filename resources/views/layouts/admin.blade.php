<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Application Vaccination')</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #4a148c;
            --sidebar-active: #7b1fa2;
            --sidebar-text: #f8f9fa;
            --sidebar-hover: #e1bee7;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            padding: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 30px 20px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .profile-container {
            position: relative;
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
        }
        
        .sidebar-header img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-sizing: border-box;
        }
        
        .sidebar-header h4 {
            font-weight: 600;
            margin: 10px 0 0;
            font-size: 1.2rem;
            text-align: center;
        }
        
        .sidebar-menu {
            padding: 20px 0;
            flex-grow: 1;
            overflow-y: auto;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: var(--sidebar-text);
            padding: 12px 25px;
            margin: 5px 0;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            opacity: 0.9;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--sidebar-hover);
            opacity: 1;
        }
        
        .sidebar-menu a.active {
            background-color: var(--sidebar-active);
            border-left-color: white;
            font-weight: 500;
        }
        
        .sidebar-menu i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logout-btn {
            width: 100%;
            background-color: transparent;
            color: var(--sidebar-text);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--sidebar-hover);
        }
        
        .logout-btn i {
            margin-right: 8px;
        }
        
        .content {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fa;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="profile-container">
                <img src="{{ asset('images/sidebar.png') }}" alt="Photo de profil">
            </div>
            <h4>Vaccination</h4>
        </div>
        
        <div class="sidebar-menu">
            <a href="{{ route('dashboard.admin') }}" class="{{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Accueil</span>
            </a>
            <a href="{{ route('enfants.create') }}" class="{{ request()->routeIs('enfants.create') ? 'active' : '' }}">
                <i class="fas fa-child"></i>
                <span>Enfants par secteur</span>
            </a>
            <a href="{{ route('admin.stats.globales') }}" class="{{ request()->routeIs('admin.stats.globales') ? 'active' : '' }}">
                <i class="fas fa-syringe"></i>
                <span>Liste de vaccination</span>
            </a>
            <a href="{{ route('admin.stats.zones') }}" class="{{ request()->routeIs('admin.stats.zones') ? 'active' : '' }}">
                <i class="fas fa-syringe"></i>
                <span>Liste de vaccination par zone</span>
            </a>
            <a href="{{ route('admin.stats.charts') }}" class="{{ request()->routeIs('admin.stats.charts') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Statistiques</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Utilisateurs</span>
            </a>
            <a href="{{ route('admin.secteurs.index') }}" class="{{ request()->routeIs('admin.secteurs.index') ? 'active' : '' }}">
                <i class="fas fa-map-marker-alt"></i>
                <span>Liste des secteurs</span>
            </a>
            <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i>
                <span>Profil</span>
            </a>
        </div>
        
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Se déconnecter</span>
                </button>
            </form>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @yield('scripts')
</body>
</html>
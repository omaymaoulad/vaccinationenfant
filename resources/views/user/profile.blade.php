@extends('layouts.user')

@section('content')
<div class="container mt-5">
    <div class="row">

        {{-- Carte Profil --}}
        <div class="col-md-4">
            <div class="card ">
                <div class="card-body text-center">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('images/profil.jpg') }}" width="100" height="100" class="rounded-circle mb-3" alt="Photo">
                        </div>
                        <h5 class="card-title">{{ $user->name }}</h5>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Carte Modifier Profil --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-side">Modifier Profil</div>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Nom</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>

            {{-- Carte Mot de passe --}}
            <div class="card">
                <div class="card-header bg-side">Changer le mot de passe</div>
                <div class="card-body">
                    <form action="{{ route('user.profile.updatePassword') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label>Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-warning">Changer le mot de passe</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

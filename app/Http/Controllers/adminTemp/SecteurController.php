<?php

namespace App\Http\Controllers\admin;
use App\Models\Secteur;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecteurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $secteurs= Secteur::all();
        return view('admin.secteurs.index',compact('secteurs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.secteurs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom'=>'required|string|max:255',
            'zone'=>'required|in:urbain,rural,DR',
            'niveau'=> 'required|in:1,2',
        ]);
        Secteur::create($request->all());
        return redirect()->route('admin.secteurs.index')->with('success','secteur supprimé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

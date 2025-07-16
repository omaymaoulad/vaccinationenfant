<?php

namespace App\Http\Controllers\User;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    public function edit(){
        $user=Auth::user();
        return view('user.profile',compact('user'));
    }
    public function update(Request $request){
        /** @var User $user */
        $user=Auth::user();
        $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|email|unique:users,email,' . $user->id,
        ]);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return redirect()->back()->with('success','Profil mis à jour.');
    }
    public function updatePassword(Request $request){
        /** @var User $user */
        $user = Auth::user();
        $request->validate([
            'current_password' =>'required',
            'password'=> 'required|min:6|confirmed',
        ]);
        if(!Hash::check($request->current_password,$user->password)){
            return back()->withErrors(['current_password'=>'mot de passe actuel incorrect.']);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->back()->with('success','Mot de passe mis à jour .');
    }
}

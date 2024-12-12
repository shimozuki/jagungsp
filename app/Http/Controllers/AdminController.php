<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return inertia('DataAdmin');
    }

    public function insert(Request $request)
    {
        $request->validate([
            'nama' => ['required'],
            'username' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
        ]);

        User::create([
            'name' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'gambar'=>$request->gambar,
            'password' => Hash::make($request->password),
            
        ]);
 
        return back()->with([
            'title' => 'Berhasil',
            'type' => 'success',
            'message' => 'Data berhasil tersimpan!'
        ]);
    }
    public function hapus(Request $request)
    {
        User::where('id', $request->id)->delete();

        return back()-> with([
            'title'=> 'Berhasil',
            'type'=>'success',
            'message'=> 'Data Berhasil Dihapus!'
        ]);
    }

    public function edit(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'username' => ['required'],
            'email' => ['required'],
        ]);

        if($request->password){
            if($request->file('gambar')) {
                $file = $request->file('gambar')[0];
                $extension = $file->getClientOriginalExtension();

                User::where('id', $request->id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'gambar' => $file->storeAs('gambar-profil', $request->username . '.' . $extension)
                ]);
            } else {
                User::where('id', $request->id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
            }
            
        } else if (!$request->password) {
            $file = $request->file('gambar')[0];
            $extension = $file->getClientOriginalExtension();
            if($request->file('gambar')) {
                User::where('id', $request->id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'gambar' => $file->storeAs('gambar-profil', $request->username . '.' . $extension)
                ]);
            } else {
                User::where('id', $request->id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                ]);
            }
        }
       

        return back()->with([
            'title' =>'Berhasil',
            'type' => 'success',
            'message' => 'Data Berhasil Diubah!'

        ]);
    }
}

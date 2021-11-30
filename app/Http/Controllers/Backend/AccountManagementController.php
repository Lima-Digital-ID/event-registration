<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AccountManagementController extends Controller
{
    private $params;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $this->params['data'] = User::orderBy('name')->get();
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }

        return view('backend.account.index', $this->params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.account.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, 
        [
                'nama' => 'required',
                'email' => 'required',
                'role' => 'required|not_in:0',
                'password' => 'required|min:4'
            ],
            [
                'required' => ':attribute harus diisi.',
                'not_in' => ':attribute harus dipilih.',
                'password.min' => 'Minimal panjang 4 karakter.'
            ],
            [
                'nama' => 'Nama',
                'email' => 'Email',
                'role' => 'Role',
                'password' => 'Password'
            ],
        );

        try {
            $newUser = new User;
            $newUser->name = $request->nama;
            $newUser->email = $request->email;
            $newUser->level = $request->role;
            $newUser->password = \Hash::make($request->password);

            $newUser->save();

            return redirect('administrator/account')->withStatus('Berhasil menyimpan data.');
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $this->params['data'] = User::findOrFail($id);

            return view('backend.account.edit', $this->params);
        }
        catch(\Exception $e){
            return redirect()->back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e){
            return redirect()->back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, 
        [
                'nama' => 'required',
                'email' => 'required',
                'role' => 'required|not_in:0',
                'password' => 'required|min:4'
            ],
            [
                'required' => ':attribute harus diisi.',
                'not_in' => ':attribute harus dipilih.',
                'password.min' => 'Minimal panjang 4 karakter.'
            ],
            [
                'nama' => 'Nama',
                'email' => 'Email',
                'role' => 'Role',
                'password' => 'Password'
            ],
        );

        try {
            $newUser = User::FindOrFail($id);
            $newUser->name = $request->nama;
            $newUser->email = $request->email;
            $newUser->level = $request->role;
            $newUser->password = \Hash::make($request->password);

            $newUser->save();

            return redirect('administrator/account')->withStatus('Berhasil menyimpan data.');
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            User::findOrFail($id)->delete();

            return redirect()->back()->withStatus('Berhasil menghapus data.');
        }
        catch(\Exception $e){
            return redirect()->back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e){
            return redirect()->back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }
    }
}

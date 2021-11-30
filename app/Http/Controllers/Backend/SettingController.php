<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Website;
use Illuminate\Http\Request;

class SettingController extends Controller
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
            $this->params['data'] = Website::first();
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }

        return view('backend.setting.index', $this->params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        // return $request;
        try {
            $update = Website::first();

            $emailUnique = $request->email != null && $request->email != $update->email ? '|unique:website,email': '';

            $this->validate($request, [
                'judul' => 'required|max:100',
                'email' => 'required'.$emailUnique,
                'alamat' => 'required',
            ], [
                'required' => ':attribute harus diisi.',
                'judul.max' => 'Maksimal panjang 100 karakter.',
                'unique' => ':attribute telah digunakan.',
            ], [
                'judul' => 'Judul',
                'email' => 'Email',
                'alamat' => 'Alamat',
            ]);

            $update->judul = $request->get('judul');
            $update->email = $request->get('email');
            $update->alamat = $request->get('alamat');
            if(isset($request->send_email) && $request->send_email == 1)
                $update->send_email = $request->send_email;
            else
                $update->send_email = 0;

            if(isset($request->send_whatsapp) && $request->send_whatsapp == 1)
                $update->send_whatsapp = $request->send_whatsapp;
            else
                $update->send_whatsapp = 0;

            $update->save();

            return back()->withStatus('Berhasil menyimpan perubahan.');
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
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
        //
    }
}

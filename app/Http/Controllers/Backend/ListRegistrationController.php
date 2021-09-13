<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use App\Models\Visitors;
use Illuminate\Http\Request;

class ListRegistrationController extends Controller
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
            $this->params['data'] = Visitors::orderBy('nomor_pendaftaran', 'DESC')->get();
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }

        return view('backend.visitors.index', $this->params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $nomorPendaftaran = null;
            $peserta = Visitors::orderBy('nomor_pendaftaran', 'DESC')->get();

            $now = date('Ymd');

            if($peserta->count() > 0){
                $nomorPendaftaran = $peserta[0]->nomor_pendaftaran;

                $lastIncrement = substr($nomorPendaftaran, 7);

                $nomorPendaftaran = str_pad($lastIncrement + 1, 3, 0, STR_PAD_LEFT);
                $nomorPendaftaran = $now.$nomorPendaftaran;

            }
            else{
                $nomorPendaftaran = $now."001";
            }
            
            $this->params['nomorPendaftaran'] = $nomorPendaftaran;
            
            $this->params['provinsi'] = Province::orderBy('nama')->get();
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }

        return view('backend.visitors.create', $this->params);
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
                'nomor' => 'required',
                'nama' => 'required',
                'email' => 'required',
                'gender' => 'required|not_in:0',
                'no_hp' => 'required',
                'tgl_lahir' => 'required',
                'provinsi' => 'required|not_in:0',
                'kota' => 'required|not_in:0',
                'alamat' => 'required',
            ],
            [
                'required' => ':attribute harus diisi.',
                'not_in' => ':attribute harus dipilih.',
                'password.min' => 'Minimal panjang 4 karakter.'
            ],
            [
                'nomor' => 'Nomor Pendaftaran',
                'nama' => 'Nama',
                'email' => 'Email',
                'gender' => 'Gender',
                'no_hp' => 'Nomor Handphone',
                'tgl_lahir' => 'Tanggal Lahir',
                'provinsi' => 'Provinsi',
                'kota' => 'Kota',
                'alamat' => 'Alamat',
            ],
        );

        try {
            $newPeserta = new Visitors;
            $newPeserta->nomor_pendaftaran = $request->get('_nomor');
            $newPeserta->name = $request->nama;
            $newPeserta->province_id = $request->provinsi;
            $newPeserta->city_id = $request->kota;
            $newPeserta->address = $request->alamat;
            $newPeserta->phone = $request->get('no_hp');
            $newPeserta->email = $request->email;
            $newPeserta->gender = $request->gender;
            $newPeserta->date_of_birth = $request->get('tgl_lahir');

            $newPeserta->save();

            return redirect('administrator/list-registration')->withStatus('Berhasil menyimpan data.');
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
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
        try {
            $this->params['data'] = Visitors::select(
                                                    'visitors.*',
                                                    'province.nama AS provinsi',
                                                    'city.nama AS kota',
                                                    )
                                                    ->join('province', 'province.id', 'visitors.province_id')
                                                    ->join('city', 'city.id', 'visitors.city_id')
                                                    ->findOrFail($id);

            return view('backend.visitors.show', $this->params);
        }
        catch(\Exception $e){
            return redirect()->back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e){
            return redirect()->back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }
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
        //
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
            Visitors::findOrFail($id)->delete();

            return redirect()->back()->withStatus('Berhasil menghapus data.');
        }
        catch(\Exception $e){
            return redirect()->back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e){
            return redirect()->back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }
    }

    public function getCity(Request $request)
    {
        $city = City::select('id', 'nama')->where('province_id', $request->province_id)->get();

        $city = json_encode($city);

        return $city;
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use App\Models\Visitors;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;
use Illuminate\Support\Facades\Http;

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
            // $nomorPendaftaran = null;
            // $peserta = Visitors::orderBy('nomor_pendaftaran', 'DESC')->get();

            // $now = date('Ymd');

            // if($peserta->count() > 0){
            //     $nomorPendaftaran = $peserta[0]->nomor_pendaftaran;

            //     $lastIncrement = substr($nomorPendaftaran, 8);

            //     $nomorPendaftaran = str_pad($lastIncrement + 1, 3, 0, STR_PAD_LEFT);
            //     $nomorPendaftaran = $now.$nomorPendaftaran;

            // }
            // else{
            //     $nomorPendaftaran = $now."001";
            // }
            
            // $this->params['nomorPendaftaran'] = $nomorPendaftaran;
            
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
                // 'nomor' => 'required',
                'instansi' => 'required',
                'jabatan' => 'required',
                'nama' => 'required',
                'email' => 'required',
                'gender' => 'required|not_in:0',
                'no_hp' => 'required',
                // 'tgl_lahir' => 'required',
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
                // 'nomor' => 'Nomor Pendaftaran',
                'instansi' => 'Instansi',
                'jabatan' => 'Jabatan',
                'nama' => 'Nama',
                'email' => 'Email',
                'gender' => 'Gender',
                'no_hp' => 'Nomor Handphone',
                // 'tgl_lahir' => 'Tanggal Lahir',
                'provinsi' => 'Provinsi',
                'kota' => 'Kota',
                'alamat' => 'Alamat',
            ],
        );

        // $this->validate($request, 
        // [
        //         'nama' => 'required',
        //         'meja' => 'required',
        //         'undangan' => 'required',
        //         'no_urut' => 'required',
        //     ],
        //     [
        //         'required' => ':attribute harus diisi.',
        //     ],
        //     [
        //         'nama' => 'Nama',
        //         'meja' => 'Meja',
        //         'undangan' => 'Undangan',
        //         'no_urut' => 'Nomor urut',
        //     ],
        // );

        try {
            $nomorPendaftaran = null;
            $peserta = Visitors::orderBy('nomor_pendaftaran', 'DESC')->get();

            $now = date('Ymd');

            if($peserta->count() > 0){
                $nomorPendaftaran = $peserta[0]->nomor_pendaftaran;

                $lastIncrement = substr($nomorPendaftaran, 8);

                $nomorPendaftaran = str_pad($lastIncrement + 1, 3, 0, STR_PAD_LEFT);
                $nomorPendaftaran = $now.$nomorPendaftaran;

            }
            else{
                $nomorPendaftaran = $now."001";
            }
            
            $newPeserta = new Visitors;
            $newPeserta->nomor_pendaftaran = $nomorPendaftaran;
            $newPeserta->name = $request->nama;
            $newPeserta->instansi = $request->instansi;
            $newPeserta->jabatan = $request->jabatan;
            $newPeserta->province_id = $request->provinsi;
            $newPeserta->city_id = $request->kota;
            $newPeserta->address = $request->alamat;
            $newPeserta->phone = $request->get('no_hp');
            $newPeserta->email = $request->email;
            $newPeserta->gender = $request->gender;
            $newPeserta->register_by = auth()->user()->id;
            // $newPeserta->date_of_birth = $request->get('tgl_lahir');

            $newPeserta->save();

            $website = Website::select('send_email', 'send_whatsapp')->first();

            if($website->send_email == 1)
                Mail::to($request->get('email'))->send(new \App\Mail\EmailMessage($nomorPendaftaran));
            
            if($website->send_whatsapp == 1)
                $sendWhatsapp = $this->sendMedia($nomorPendaftaran, $request->get('no_hp'));

            // $nomorPendaftaran = $request->undangan.$request->meja.$request->no_urut;

            // $newPeserta = new Visitors;
            // $newPeserta->nomor_pendaftaran = $nomorPendaftaran;
            // $newPeserta->name = $request->nama;
            // $newPeserta->undangan = $request->undangan;
            // $newPeserta->meja = $request->meja;
            // $newPeserta->urutan_no = $request->no_urut;
            
            // $newPeserta->save();

            return redirect('administrator/list-registration')->withStatus('Berhasil menyimpan data.');
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }
    }

    private function sendMedia($nomorPendaftaran, $phone)
    {
        $qrcode = FacadesQrCode::format('png')
                ->size(250)
                ->errorCorrection('H')
                ->generate($nomorPendaftaran);

        $qrcode = str_replace('250', '100%', $qrcode);
        $qrcode = str_replace('viewBox="0 0 100% 100%"', 'viewBox="0 0 250 250"', $qrcode);

        $output_file = 'img-' . $nomorPendaftaran . '.png';

        Storage::disk('public')->put($output_file, $qrcode); //public/qrcode/img-1557309130.png

        // $file = asset('qrcode/img-'.$nomorPendaftaran.'.png');
        $file = 'http://127.0.0.1:8002/qrcode/img-'.$nomorPendaftaran.'.png';
        // $file = 'https://www.nicesnippets.com/upload/blog/1622612582_social-media.png';

        $caption = 'Silahkan menggunakan QRCode diatas untuk tiket masuk.';

        $url = 'http://127.0.0.1:8000/send-media';

        $response = Http::post($url, [
            'number' => $phone,
            'caption' => $caption,
            'file' => $file,
        ]);
        $res = json_decode($response, false);

        return $res->status;
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
            $this->params['data'] = Visitors::select('visitors.*', 'province.nama AS provinsi', 'city.nama AS kota', 'users.name AS operator')
                                            ->join('province', 'province.id', 'visitors.province_id')
                                            ->join('city', 'city.id', 'visitors.city_id')
                                            ->join('users', 'users.id', 'visitors.register_by')
                                            ->where('visitors.id', $id)
                                            ->first();

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
        $this->params['data'] = Visitors::find($id);
        $this->params['provinsi'] = Province::orderBy('nama')->get();

        return view('backend.visitors.edit', $this->params);
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
                'instansi' => 'required',
                'jabatan' => 'required',
                'nama' => 'required',
                'email' => 'required',
                'gender' => 'required|not_in:0',
                'no_hp' => 'required',
                // 'tgl_lahir' => 'required',
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
                'instansi' => 'Instansi',
                'jabatan' => 'Jabatan',
                'nama' => 'Nama',
                'email' => 'Email',
                'gender' => 'Gender',
                'no_hp' => 'Nomor Handphone',
                // 'tgl_lahir' => 'Tanggal Lahir',
                'provinsi' => 'Provinsi',
                'kota' => 'Kota',
                'alamat' => 'Alamat',
            ],
        );
        // $this->validate($request, 
        // [
        //         'nama' => 'required',
        //         'meja' => 'required',
        //         'undangan' => 'required',
        //         'no_urut' => 'required',
        //     ],
        //     [
        //         'required' => ':attribute harus diisi.',
        //     ],
        //     [
        //         'nama' => 'Nama',
        //         'meja' => 'Meja',
        //         'undangan' => 'Undangan',
        //         'no_urut' => 'Nomor urut',
        //     ],
        // );

        try {
            // $nomorPendaftaran = $request->undangan.$request->meja.$request->no_urut;
            // $editPeserta = Visitors::find($id);

            // $editPeserta->nomor_pendaftaran = $nomorPendaftaran;
            // $editPeserta->name = $request->nama;
            // $editPeserta->undangan = $request->undangan;
            // $editPeserta->meja = $request->meja;
            // $editPeserta->urutan_no = $request->no_urut;

            // $editPeserta->save();

            $updatePeserta = Visitors::find($id);
            $updatePeserta->name = $request->nama;
            $updatePeserta->province_id = $request->provinsi;
            $updatePeserta->city_id = $request->kota;
            $updatePeserta->address = $request->alamat;
            $updatePeserta->phone = $request->get('no_hp');
            $updatePeserta->email = $request->email;
            $updatePeserta->gender = $request->gender;
            $updatePeserta->date_of_birth = $request->get('tgl_lahir');

            $updatePeserta->save();

            $website = Website::select('send_email', 'send_whatsapp')->first();

            if($website->send_email == 1)
                Mail::to($request->get('email'))->send(new \App\Mail\EmailMessage($updatePeserta->nomor_pendaftaran));
            
            if($website->send_whatsapp == 1)
                $sendWhatsapp = $this->sendMedia($updatePeserta->nomor_pendaftaran, $request->get('no_hp'));

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

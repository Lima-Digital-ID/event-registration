<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use App\Models\Visitors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;
use Illuminate\Support\Facades\Http;

class RegistrationController extends Controller
{
    private $params;

    public function index()
    {
        try {
            $this->params['provinsi'] = Province::orderBy('nama')->get();

            return view('frontend.registration', $this->params);
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $this->validate($request, 
        [
                'nama' => 'required',
                'email' => 'required|unique:visitors,email',
                'gender' => 'required|not_in:0',
                'no_hp' => 'required|unique:visitors,phone',
                'tgl_lahir' => 'required',
                'provinsi' => 'required|not_in:0',
                'kota' => 'required|not_in:0',
                'alamat' => 'required',
            ],
            [
                'required' => ':attribute harus diisi.',
                'not_in' => ':attribute harus dipilih.',
                'password.min' => 'Minimal panjang 4 karakter.',
                'unique' => ':attribute telah digunakan.'
            ],
            [
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

        // return $request;

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
            $newPeserta->province_id = $request->provinsi;
            $newPeserta->city_id = $request->kota;
            $newPeserta->address = $request->alamat;
            $newPeserta->phone = $request->get('no_hp');
            $newPeserta->email = $request->email;
            $newPeserta->gender = $request->gender;
            $newPeserta->date_of_birth = $request->get('tgl_lahir');

            $newPeserta->save();
            
            $sendWhatsapp = $this->sendMedia($nomorPendaftaran, $request->get('no_hp'));

            Mail::to($request->get('email'))->send(new \App\Mail\EmailMessage($nomorPendaftaran));

            return view('frontend.success')->with('nomorPendaftaran', $nomorPendaftaran);
        }
        catch(\Exception $e) {
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }
    }

    public function getCity(Request $request)
    {
        $city = City::select('id', 'nama')->where('province_id', $request->province_id)->get();

        $city = json_encode($city);

        return $city;
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
}

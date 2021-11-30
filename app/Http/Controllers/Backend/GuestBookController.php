<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use App\Models\Visitors;
use Illuminate\Http\Request;

class GuestBookController extends Controller
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
            // $this->params['data'] = GuestBook::select(
            //                             'guest_book.id',
            //                             'guest_book.updated_at',
            //                             'visitors.*'   
            //                         )
            //                         ->join('visitors', 'visitors.id', 'guest_book.visitor_id')
            //                         ->orderBy('visitors.nomor_pendaftaran', 'DESC')
            //                         ->get();
            $this->params['data'] = Visitors::select(
                                             'guest_book.id',
                                             'guest_book.created_at as checkin_at',
                                             'visitors.*'   
                                            )
                                            ->leftJoin('guest_book', 'visitors.id', 'guest_book.visitor_id')
                                            ->orderBy('checkin_at', 'DESC')
                                            ->orderBy('visitors.nomor_pendaftaran')
                                            ->orderBy('visitors.name')
                                            ->get();
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }

        return view('backend.guest-book.index', $this->params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $this->params['visitor'] = Visitors::select('id', 'nomor_pendaftaran', 'name')
                                                ->whereNotIn('id', function($q) {
                                                    $q->from('guest_book')->select('visitor_id');
                                                })
                                                ->orderBy('nomor_pendaftaran')
                                                ->get();
        }
        catch(\Exception $e) {
            return back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }

        return view('backend.guest-book.create', $this->params);
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
            'visitor' => 'required|not_in:0'
        ], [
            'required' => ':attribute harus diisi.',
            'not_in' => ':attribute harus dipilih.'
        ], [
            'visitor' => 'Peserta'
        ]);

        try {
            $newTamu = new GuestBook;
            $newTamu->visitor_id = $request->visitor;
            
            $newTamu->save();

            return redirect('administrator/guest-book')->withStatus('Peserta berhasil ditambahkan.');
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
            $this->params['data'] = GuestBook::select(
                                                    'guest_book.id',
                                                    'guest_book.updated_at',
                                                    'visitors.*',
                                                    'province.nama AS provinsi',
                                                    'city.nama AS kota',
                                                    )
                                                    ->join('visitors', 'visitors.id', 'guest_book.visitor_id')
                                                    ->join('province', 'province.id', 'visitors.province_id')
                                                    ->join('city', 'city.id', 'visitors.city_id')
                                                    ->where('visitors.id', $id)->first();

            return view('backend.guest-book.show', $this->params);
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // try {
        //     GuestBook::findOrFail($id)->delete();

        //     return redirect()->back()->withStatus('Berhasil menghapus data.');
        // }
        // catch(\Exception $e){
        //     return redirect()->back()->withError('Terjadi kesalahan. '.$e->getMessage());
        // }
        // catch(\Illuminate\Database\QueryException $e){
        //     return redirect()->back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        // }
    }

    public function addGuest(Request $request)
    {
        try {
            $nomorPendaftaran = $request->get('nomor_pendaftaran');
            $visitor = Visitors::where('nomor_pendaftaran', $nomorPendaftaran)->first();
            if($visitor) {
                // jika peserta ditemukan
                $isAlreadyGuest = GuestBook::where('visitor_id', $visitor->id)
                                            ->whereDate('created_at', date('Y-m-d'))
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                if(count($isAlreadyGuest) > 0) {
                    // sudah terdaftar sebagai tamu
                    $response = [
                        'status' => 'success',
                        'message' => 'sudah terdaftar di buku tamu',
                        'data' => $visitor,
                        'checkin' => $isAlreadyGuest[0]->created_at
                    ];
                    return $response;
                }
                else {
                    // belum terdaftar sebagai tamu
                    $newGuest = new GuestBook;
                    $newGuest->visitor_id = $visitor->id;
                    $newGuest->operator = auth()->user()->id;

                    $newGuest->save();

                    $response = [
                        'status' => 'success',
                        'message' => 'berhasil',
                        'data' => $visitor
                    ];
                    return $response;
                }
            }
            else {
                // jika peserta tidak ditemukan
                $response = [
                    'status' => 'success',
                    'message' => 'peserta tidak ada',
                    'nomor' => $nomorPendaftaran,
                    'data' => $visitor
                ];
                return $response;
            }
        }
        catch(\Exception $e){
            $json = [
                'status' => 'failed',
                'message' => 'Terjadi kesalahan. '.$e->getMessage(),
                'data' => null
            ];

            return $json;
        }
        catch(\Illuminate\Database\QueryException $e){
            $json = [
                'status' => 'failed',
                'message' => 'Terjadi kesalahan pada database.'.$e->getMessage(),
                'data' => null
            ];

            return $json;
        }
    }

    public function checkIn($id)
    {
        try {
            $isAlreadyGuest = GuestBook::where('visitor_id', $id)->count();

            if($isAlreadyGuest > 0) {
                // sudah melakukan checkin
                return redirect()->back()->withError('Sudah melakukan checkin.');
            }
            else {
                $checkIn = new GuestBook;
                $checkIn->visitor_id = $id;
                $checkIn->operator = auth()->user()->id;
                
                $checkIn->save();
    
                return back()->withStatus('Berhasil melakukan checkin.');
            }
        }
        catch(\Exception $e){
            return redirect()->back()->withError('Terjadi kesalahan. '.$e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e){
            return redirect()->back()->withError('Terjadi kesalahan pada database. '.$e->getMessage());
        }
    }
}

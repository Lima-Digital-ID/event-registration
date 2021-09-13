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
            $this->params['data'] = GuestBook::select(
                                             'guest_book.id',
                                             'guest_book.updated_at',
                                             'visitors.*'   
                                            )
                                            ->join('visitors', 'visitors.id', 'guest_book.visitor_id')
                                            ->orderBy('visitors.nomor_pendaftaran', 'DESC')
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
}

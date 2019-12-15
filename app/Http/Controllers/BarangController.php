<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BarangFormRequest;
use Yajra\Datatables\Datatables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return redirect(route('barang.create'));
        return view('barang.index');
    }

    public function getData()
    {
        $user = auth()->user();
        return Datatables::of($user->barang)
        ->addColumn('actions', function ($data) {
            return '
                <a href="'. route('barang.edit', $data->id) .'" class="btn btn-sm btn-primary">Edit</a>
                <a href="'. route('barang.destroy', $data->id) .'" class="btn btn-sm btn-danger">Hapus</a>
            ';
        })
        ->rawColumns(['actions'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BarangFormRequest $request)
    {
        $user   = $request->user();
        $warung = $user->warung;
        
        $warung->barang()->create([
            'nama'          => $request->nama,
            'harga_beli'    => $request->harga_beli,
            'harga_jual'    => $request->harga_jual,
            'stok'          => $request->stok,
        ]);

        session()->flash('status', 'Barang berhasil di buat');

        return redirect()->back();
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
        //
    }
}
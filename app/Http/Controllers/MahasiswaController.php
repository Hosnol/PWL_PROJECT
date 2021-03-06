<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cari = $request->get('cari');
        $mhs = Mahasiswa::with('kelas')->paginate(5);

        if($cari){
            $mhs = Mahasiswa::with('kelas')->where('nama','like','%'.$cari.'%')
            ->orWhere('nim','like','%'.$cari.'%')->paginate(5);
        }

        return view('index_mahasiswa', ['mhs'=>$mhs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all();
        $user = User::all();
        return view('create_mahasiswa',['kelas'=>$kelas, 'user'=>$user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'jk' => 'required',
            'kelas' => 'required',
            'nohp' => 'required',
            'alamat' => 'required',
        ]);

        if($request->file('foto')){
            $image_name = $request->file('foto')->store('image','public');
        }else{
            $image_name= null;
        }

        $mahasiswa = new Mahasiswa;
        $mahasiswa->nim = $request->get('nim');
        $mahasiswa->nama = $request->get('nama');
        $mahasiswa->jk = $request->get('jk');
        $mahasiswa->nohp = $request->get('nohp');
        $mahasiswa->alamat = $request->get('alamat');
        $mahasiswa->foto = $image_name;
        $mahasiswa->save();

        $kelas = new Kelas;
        $kelas->id = $request->get('kelas');

        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        $user = new User;
        $user->id = $request->get('user');

        $mahasiswa->user()->associate($user);
        $mahasiswa->save();

        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan');

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
        $Mahasiswa = Mahasiswa::with('kelas')->where('id', $id)->first();
        $kelas = Kelas::all();
        $user = User::all();
        return view('edit_mahasiswa', compact('Mahasiswa','kelas','user'));
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
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'jk' => 'required',
            'kelas' => 'required',
            'nohp' => 'required',
            'alamat' => 'required',
        ]);

        $mahasiswa = Mahasiswa::with('kelas')->where('id',$id)->first();

        $mahasiswa->nim = $request->get('nim');
        $mahasiswa->nama = $request->get('nama');
        $mahasiswa->jk = $request->get('jk');
        $mahasiswa->nohp = $request->get('nohp');
        $mahasiswa->alamat = $request->get('alamat');

        if($mahasiswa->foto && file_exists(storage_path('app/public/' . $mahasiswa->foto))){
            Storage::delete('public/' . $mahasiswa->foto);
        }
        $image_name = $request->file('foto')->store('images', 'public');
        $mahasiswa->foto = $image_name;

        $mahasiswa->save();

        $kelas = new Kelas;
        $kelas->id = $request->get('kelas');

        //fungsi eloquent untuk menambah data dengan relasi belongTo
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        $user = new User;
        $user->id = $request->get('user');

        $mahasiswa->user()->associate($user);
        $mahasiswa->save();

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Mahasiswa::find($id)->delete();
        return redirect()->route('mahasiswa.index')-> with('success', 'Mahasiswa berhasil dihapus');
    }

    public function nilai($id)
    {   
        $Mahasiswa = Mahasiswa::find($id);
        return view('mahasiswa.nilai-mahasiswa',['Mahasiswa'=>$Mahasiswa]);
    }

    public function cetak($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        $pdf = PDF::loadview('mahasiswa.nilai-pdf',['mhs'=>$mahasiswa]);
        return $pdf->stream();
    }
}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="card" style="width: 30rem">
            <div class="card-header">
                Edit Jadwal
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong> Whoops!!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="post" action="{{ route('jadwal.update', $jadwal->id) }}" id="myForm"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <input type="text" name="hari" required="required" class="form-control" id="hari"
                            aria-describedby="hari" value="{{ $jadwal->hari }}">
                    </div>

                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select name="kelas" class="form-control">
                            @foreach ($kelas as $item)
                            <option value="{{ $item->id }}" {{ $jadwal->kelas_id == $item->id ? 'selected': '' }}>
                                {{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="jam_mulai">Jam mulai</label>
                            <input type="jam_mulai" name="jam_mulai" required="required" class="form-control"
                                id="jam_mulai" aria-describedby="jam_mulai" value="{{ $jadwal->jam_mulai }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="jam_selesai">Jam selesai</label>
                            <input type="jam_selesai" name="jam_selesai" required="required" class="form-control"
                                id="jam_selesai" aria-describedby="jam_selesai" value="{{ $jadwal->jam_selesai }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="matakuliah">Matakuliah</label>
                        <select name="matakuliah" class="form-control">
                            @foreach ($matkul as $item)
                            <option value="{{ $item->id }}" {{ $jadwal->matakuliah_id == $item->id ? 'selected': '' }}>
                                {{ $item->nama_matkul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dosen">Dosen</label>
                        <select name="dosen" class="form-control">
                            @foreach ($dosen as $item)
                            <option value="{{ $item->id }}" {{ $jadwal->dosen_id == $item->id ? 'selected': '' }}>
                                {{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-success" href="{{ route('jadwal.index') }}">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

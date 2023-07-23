@extends('layout.main')
@section('container')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="fw-bold py-3 mb-4">
            <span class="text-secondary fw-light">
                <a href="/dashboard" class="text-secondary">Beranda /</a>
                Kegiatan /
                <a href="/permohonan" class="text-secondary">Permohonan Kegiatan /</a>
            </span>
            <span class="text-primary">
                Tambah
            </span>
        </h5>


        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Permohonan Kegiatan</h5>
                        <small class="text-muted float-end">
                            <a href="/permohonan">
                                < Kembali </a>
                        </small>
                    </div>
                    <div class="card-body">
                        <form action="/permohonan" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="kode">Kode Kegiatan</label>
                                <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                    id="kode" placeholder="kode" value="{{ old('kode', bin2hex(random_bytes(4))) }}"
                                    name="kode" required />
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="nama">Nama Kegiatan</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" placeholder="Nama" value="{{ old('nama') }}" name="nama" required />
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="tipe">Tipe permohonan</label>
                                <select id="organization" class="select2 form-select @error('tipe') is-invalid @enderror"
                                    name="tipe">
                                    <option value="perkuliahan" @selected(old('tipe') == 'perkuliahan')>
                                        Perkuliahan</option>
                                    <option value="non perkuliahan" @selected(old('tipe') == 'non perkuliahan')>
                                        Non Perkuliahan</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="laboratorium_id">Laboratorium</label>
                                <select id="organization"
                                    class="select2 form-select @error('laboratorium_id') is-invalid @enderror"
                                    name="laboratorium_id">
                                    <option value="">Pilih Laboratorium</option>
                                    @foreach ($laboratoriums as $laboratorium)
                                        @if ($laboratorium->user_id == auth()->user()->id)
                                            @continue
                                        @endif
                                        <option value="{{ $laboratorium->id }}" @selected(old('laboratorium_id') == $laboratorium->id)>
                                            {{ $laboratorium->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('laboratorium_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            @if (auth()->user()->role != 'staff')
                                <div class="mb-3">
                                    <label class="form-label" for="dospem_id">Dosen Pengampu</label>
                                    <select id="organization"
                                        class="select2 form-select @error('dospem_id') is-invalid @enderror"
                                        name="dospem_id">
                                        @if (auth()->user()->role != 'dosen')
                                            <option value="">Pilih Dosen Pengampu</option>
                                        @endif
                                        @foreach ($dospems as $dospems)
                                            <option value="{{ $dospems->id }}" @selected(old('dospem_id') == $dospems->id)>
                                                {{ $dospems->user->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('dospem_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="kelas_id">Kelas</label>
                                    <select id="organization"
                                        class="select2 form-select @error('kelas_id') is-invalid @enderror" name="kelas_id">
                                        @if (auth()->user()->role != 'mahasiswa')
                                            <option value="">Pilih kelas</option>
                                        @endif
                                        @foreach ($kelas as $kls)
                                            $
                                            <option value="{{ $kls->id }}" @selected(old('kelas_id') == $kls->id)>
                                                {{ $kls->nama . ' (' . $kls->angkatan . ')' }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label" for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" class="form-control @error('deskripsi')
                                is-invalid @enderror"
                                    placeholder="deskripsi" name="deskripsi" required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="mulai">Jadwal Tanggal Mulai</label>
                                <input type="datetime-local" class="form-control @error('mulai') is-invalid @enderror"
                                    id="mulai" placeholder="mulai" name="mulai" value="{{ old('mulai') }}"
                                    min="{{ date('Y-m-d') . 'T' . date('H:i') }}" required onchange="akhir()" />
                                @error('mulai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="selesai">Jadwal Tanggal selesai</label>
                                <input type="datetime-local" class="form-control @error('selesai') is-invalid @enderror"
                                    id="selesai" placeholder="selesai" name="selesai" value="{{ old('selesai') }}"
                                    required />
                                @error('selesai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="card">
        <div class="card-header">
            <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
        <div class="card-body">
            <div class="mb-3 col-12 mb-0">
                <div class="alert alert-primary">
                    <h6 class="alert-heading fw-bold mb-1">Penampermohonan Data permohonan</h6>
                    <p class="mb-0">Ketika Form Tambah Data permohonan ditambahkan,<br />
                        Maka Secara Otomatis Kode QR akan menambahkan data Kode QR baru, <br />
                        Dan Langsung Disambungkan sesuai kode qr yang tertera
                    </p>
                </div>
            </div>
        </div>
    </div> --}}
    </div>
    <script>
        const mulai = document.getElementById('mulai');
        const selesai = document.getElementById('selesai');

        function akhir() {
            selesai.min = mulai.value;
        }
    </script>
@endsection

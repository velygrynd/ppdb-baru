@extends('layouts.backend.app')
@section('title', 'Konfirmasi Ulang Pembayaran')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="col-12">
            <h2>Konfirmasi Ulang Pembayaran</h2>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-lg-7 col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Detail Tagihan</h6>
                            <ul class="mb-0 ps-3">
                                <li>Bulan: <strong>{{ $payment->bulan }}</strong></li>
                                <li>Tahun Ajaran: <strong>{{ $payment->year }}</strong></li>
                                <li class="text-success fw-bold">Nominal: <strong>Rp {{ number_format($sppSetting->amount, 0, ',', '.') }}</strong></li>
                            </ul>
                        </div>
                        @if($payment->status == 'Ditolak')
                        <div class="alert alert-danger">
                            <h5 class="alert-heading">Pembayaran Ditolak</h5>
                        </div>
                        @endif
                        <hr>
                        <form action="{{ route('murid.pembayaran.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="form-group mb-2"><label for="nama_pengirim">Nama Pengirim</label><input type="text" name="nama_pengirim" class="form-control @error('nama_pengirim') is-invalid @enderror" value="{{ old('nama_pengirim', $user->name) }}" required>@error('nama_pengirim')<div class="invalid-feedback"><strong>{{ $message }}</strong></div>@enderror</div>
                           
                            <div class="form-group mb-2">
                                <label for="bukti_pembayaran">Unggah Ulang Bukti Transfer</label>
                                <input type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" name="bukti_pembayaran" required>
                                <small class="form-text text-muted">Format: JPG, PNG, PDF (Max: 2MB)</small>
                                @error('bukti_pembayaran')<div class="invalid-feedback"><strong>{{ $message }}</strong></div>@enderror
                            </div>
                            <div class="form-group mt-3">
                                <button class="btn btn-primary" type="submit">Kirim Ulang Konfirmasi</button>
                                <a href="{{ route('murid.pembayaran.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
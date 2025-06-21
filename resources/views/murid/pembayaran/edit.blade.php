@extends('layouts.backend.app')
@section('title', 'Konfirmasi Ulang Pembayaran')
@section('content')
<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <div class="col-12"><h2>Konfirmasi Ulang Pembayaran</h2></div>
    </div>
    <div class="content-body">
        <div class="row">
            <div class="col-lg-7 col-md-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Detail Tagihan</h6>
                            <ul class="mb-0 ps-3">
                                <li>Bulan: <strong>{{ $payment->bulan_dibayar }}</strong></li>
                                <li>Tahun Ajaran: <strong>{{  $payment->tahun_ajaran }}</strong></li>
                                <li class="text-success fw-bold">Nominal: <strong>Rp {{ number_format($payment->nominal, 0, ',', '.') }}</strong></li>
                            </ul>
                        </div>
                        @if($payment->status == 'Ditolak')
                            <div class="alert alert-danger"><h5 class="alert-heading">Pembayaran Ditolak</h5></div>
                        @endif
                        <hr>
                    <form action="{{ route('murid.pembayaran.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')
                            <div class="form-group mb-2"><label for="nama_pengirim">Nama Pengirim</label><input type="text" name="nama_pengirim" class="form-control @error('nama_pengirim') is-invalid @enderror" value="{{ old('nama_pengirim', $user->name) }}" required>@error('nama_pengirim')<div class="invalid-feedback"><strong>{{ $message }}</strong></div>@enderror</div>
                            {{-- <div class="form-group mb-2"><label for="nama_bank">Bank Pengirim</label><input type="text" name="nama_bank" class="form-control @error('nama_bank') is-invalid @enderror" value="{{ old('nama_bank', $detailPembayaran->nama_bank) }}" required>@error('nama_bank')<div class="invalid-feedback"><strong>{{ $message }}</strong></div>@enderror</div>
                            <div class="form-group mb-2"><label for="no_rekening">Nomor Rekening</label><input type="number" name="no_rekening" class="form-control @error('no_rekening') is-invalid @enderror" value="{{ old('no_rekening', $detailPembayaran->no_rekening) }}" required>@error('no_rekening')<div class="invalid-feedback"><strong>{{ $message }}</strong></div>@enderror</div>
                            <div class="form-group mb-2">
                                <label for="bank_account_id">Bank Tujuan</label>
                                <select name="bank_account_id" class="form-control" required><option value="">-- Pilih Bank --</option>@foreach ($bank as $b)<option value="{{ $b->id }}" {{ old('bank_account_id', $detailPembayaran->bank_account_id) == $b->id ? 'selected' : '' }}>{{ $b->bank_name }} - {{ $b->account_number }} (a/n {{ $b->account_name }})</option>@endforeach</select>@error('bank_account_id')<div class="invalid-feedback"><strong>{{ $message }}</strong></div>@enderror
                            </div> --}}
                            <div class="form-group mb-2">
                                <label for="bukti_pembayaran">Unggah Ulang Bukti Transfer</label>
                                <input type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" name="bukti_pembayaran" required>
                                <small class="form-text text-muted">Format: JPG, PNG, PDF (Max: 2MB)</small>
                                @error('bukti_pembayaran')<div class="invalid-feedback"><strong>{{ $message }}</strong></div>@enderror
                            </div>
                            <input type="text" name="sender" id="sender" value="{{ $user->name }}" hidden>
                            <input type="text" name="bank_sender" id="bank_sender" value="bri" hidden>
                            <input type="text" name="destination_bank" id="destination_bank" value="bni" hidden>
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
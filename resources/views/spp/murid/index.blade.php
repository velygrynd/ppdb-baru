@extends('layouts.backend.app')
@section('title', 'Data Pembayaran Murid')
@section('content')
    {{-- Notifikasi --}}

<div class="content-wrapper container-xxl p-0">
    <div class="content-header row">
        <h2 class="content-header-title float-left mb-0">Data Pembayaran Murid</h2>
    </div>
    <div class="content-body">
        <div class="card">
            <div class="card-datatable p-2">
                <table class="dt-responsive table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Pembayaran Bulan {{ \Carbon\Carbon::parse($bulanIni)->translatedFormat('F') }}</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($murid as $key => $murid)
                            @php
                                $paymentThisMonth = $murid->detailPaymentSpp ? $murid->detailPaymentSpp->first() : null;
                                $parentPayment = $murid->payment ? $murid->payment->first() : null;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $murid->muridDetail->nisn ?? 'N/A' }}</td>
                                <td>{{ $murid->name }}</td>
                                <td>{{ $murid->email }}</td>
                                <td class="text-center">
                                    @if ($paymentThisMonth)
                                        @if ($paymentThisMonth->status == 'paid')
                                            <span class="badge bg-success">LUNAS</span>
                                        @elseif ($paymentThisMonth->status == 'pending')
                                            <span class="badge bg-info">DIPROSES</span>
                                        @else
                                            <span class="badge bg-warning">BELUM LUNAS</span>
                                        @endif
                                    @else
                                        <span class="badge bg-danger">BELUM DIBAYAR</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($parentPayment)
                                        <a href="{{ route('spp.murid.detail', $murid->id) }}" class="btn btn-sm btn-primary">
                                            <i data-feather="eye"></i> Detail
                                        </a>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
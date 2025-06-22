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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Pembayaran Bulan {{ \Carbon\Carbon::parse($bulanIni)->translatedFormat('F') }}</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentDetails as $key => $payment)
                            @php
                                // $paymentThisMonth = $payment->detailPaymentSpp ? $payment->detailPaymentSpp->first() : null;
                                // $parentPayment = $payment->payment ? $payment->payment->first() : null;
                                $statusPayment = $payment->status;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                
                                <td>{{ $payment->payment->user->name }}</td>
                                <td>{{ $payment->payment->user->email }}</td>
                                <td class="text-center">
                                        @if ($statusPayment == 'paid')
                                            <span class="badge bg-success">LUNAS</span>
                                        @elseif ($statusPayment == 'pending')
                                            <span class="badge bg-info">DIPROSES</span>
                                        @else
                                            <span class="badge bg-warning">BELUM LUNAS</span>
                                        @endif
                                </td>
                                <td class="text-center">
                                        <a href="{{ route('spp.murid.detail', $payment->id) }}" class="btn btn-sm btn-primary">
                                            <i data-feather="eye"></i> Detail
                                        </a>
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
@extends('layouts.backend.app')
@section('title', 'Pembayaran SPP')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i class="fas fa-credit-card"></i> Tagihan SPP Anda</h4>
                        @if(isset($kelas))
                            <small class="text-muted">Kelas: {{ $kelas->nama ?? 'Tidak diketahui' }}</small>
                        @endif
                        
                    </div>
                    <div class="card-body">
                        {{-- Alert Messages --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(isset($error))
                            <div class="alert alert-danger">
                                <h5 class="mb-1"><i class="fas fa-exclamation-triangle"></i> Error</h5>
                                <p class="mb-0">{{ $error }}</p>
                                <hr>
                                <small class="text-muted">Silakan refresh halaman atau hubungi Administrator.</small>
                            </div>
                        @endif

                        @if(isset($message))
                            <div class="alert alert-warning">
                                <h5 class="mb-1"><i class="fas fa-info-circle"></i> Informasi</h5>
                                <p class="mb-0">{{ $message }}</p>
                            </div>
                        @endif

                        {{-- Debug Info (hanya tampil jika ada error) --}}
                        @if(isset($error) && app()->environment('local'))
                            <div class="alert alert-info">
                                <h6>Debug Information:</h6>
                                <ul class="mb-0">
                                    <li>User ID: {{ auth()->id() }}</li>
                                    <li>Kelas ID: {{ auth()->user()->kelas_id ?? 'Tidak ada' }}</li>
                                    <li>Tagihan Count: {{ $tagihanBulanan->count() }}</li>
                                    <li>Bank Count: {{ isset($bank) ? $bank->count() : 0 }}</li>
                                </ul>
                            </div>
                        @endif

                        @if(!isset($tagihanBulanan))
                            <div class="alert alert-warning">
                                <h5 class="mb-1"><i class="fas fa-exclamation-triangle"></i> Setting SPP Belum Tersedia</h5>
                                <p class="mb-0">Hubungi Admin untuk mengatur SPP kelas Anda.</p>
                                @if(app()->environment('local'))
                                    <hr>
                                    <small class="text-muted">
                                        Kemungkinan penyebab: Data SPP Setting untuk kelas Anda belum dibuat di database.
                                        <br>Admin perlu menambahkan data di tabel 'spp_settings' untuk kelas_id: {{ auth()->user()->kelas_id }} 
                                        dan tahun_ajaran: 2024/2025
                                    </small>
                                @endif
                            </div>
                        @else
                            <!-- Informasi Ringkas -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h5>{{ $tagihanBulanan->where('status', 'Lunas')->count() }}</h5>
                                            <small>Bulan Lunas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body text-center">
                                            <h5>{{ $tagihanBulanan->where('status', 'Menunggu Konfirmasi')->count() }}</h5>
                                            <small>Menunggu Konfirmasi</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body text-center">
                                            <h5>{{ $tagihanBulanan->where('status', 'Belum Lunas')->count() }}</h5>
                                            <small>Belum Lunas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <h5>Rp {{ number_format($tagihanBulanan->where('status', 'Belum Lunas')->sum('jumlah'), 0, ',', '.') }}</h5>
                                            <small>Total Tunggakan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Tagihan -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Bulan</th>
                                            <th>Tahun Ajaran</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tagihanBulanan as $index => $tagihan)
                                            <tr class="
                                                @if($tagihan['status'] == 'Lunas') table-success
                                                @elseif($tagihan['status'] == 'Menunggu Konfirmasi') table-warning
                                                @elseif($tagihan['status'] == 'Ditolak') table-danger
                                                @endif
                                            ">
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $tagihan['bulan'] }}</strong></td>
                                                <td><span class="badge bg-info">{{ $tagihan['year'] }}</span></td>
                                                <td><strong class="text-success">Rp {{ number_format($tagihan['jumlah'], 0, ',', '.') }}</strong></td>
                                                <td>
                                                    @if ($tagihan['status'] == 'Lunas')
                                                        <span class="badge bg-success"><i class="fas fa-check"></i> Lunas</span>
                                                    @elseif ($tagihan['status'] == 'Menunggu Konfirmasi')
                                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Menunggu Konfirmasi</span>
                                                    @elseif ($tagihan['status'] == 'Ditolak')
                                                        <span class="badge bg-danger"><i class="fas fa-times"></i> Ditolak</span>
                                                        @if(isset($tagihan['detail']) && $tagihan['detail'] && $tagihan['detail']->admin_note)
                                                            <br><small class="text-danger">{{ $tagihan['detail']->admin_note }}</small>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary"><i class="fas fa-exclamation"></i> Belum Lunas</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($tagihan['status'] == 'belum_bayar')
                                                        <a href="{{ route('murid.pembayaran.edit', $tagihan['id']) }}" class="btn btn-primary btn-sm bayar-btn"
                                                            data-bulan="{{ $tagihan['bulan'] }}"
                                                            data-nominal="{{ number_format($tagihan['jumlah'], 0, ',', '.') }}"
                                                            data-nominal-raw="{{ $tagihan['jumlah'] }}"
                                                            data-tahun-ajaran="{{ $tagihan['tahun_ajaran'] }}" 
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#paymentModal">
                                                            
                                                            <i class="fas fa-money-bill-wave"></i> Bayar 
                                                        </a>
                                                    @elseif (in_array($tagihan['status'], ['lunas', 'menunggu_konfirmasi']) && isset($tagihan['detail']) && $tagihan['detail'] && $tagihan['detail']->file)
                                                        <a href="{{ asset('storage/images/bukti_payment/' . $tagihan['detail']->file) }}"
                                                            target="_blank" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i> Lihat Bukti
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    <i class="fas fa-info-circle"></i> Tidak ada data tagihan
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Pembayaran --}}
    @if(isset($tagihanBulanan) && $tagihanBulanan->isNotEmpty() && isset($bank) && $bank->isNotEmpty())
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="paymentForm" action="{{ route('murid.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="paymentModalLabel">
                            <i class="fas fa-credit-card"></i> Konfirmasi Pembayaran
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <h6 class="mb-2">Detail Pembayaran:</h6>
                            <ul class="mb-0 ps-3">
                                <li>Bulan: <strong id="modal-bulan"></strong></li>
                                <li>Tahun Ajaran: <strong id="modal-tahun-ajaran"></strong></li>
                                <li>Nominal: <strong id="modal-nominal" class="text-success"></strong></li>
                            </ul>
                        </div>
                        
                        <input type="hidden" name="bulan_dibayar" id="form-bulan">
                        <input type="hidden" name="tahun_ajaran" id="form-tahun-ajaran">
                        
                        <div class="mb-3">
                            <label class="form-label">Tujuan Pembayaran <span class="text-danger">*</span></label>
                            <select name="bank_account_id" class="form-select" required>
                                <option value="">-- Pilih Bank --</option>
                                @foreach ($bank as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->bank_name ?? $item->name ?? 'Bank' }} - 
                                        {{ $item->account_number ?? $item->number ?? 'No Rekening' }} 
                                        (a/n {{ $item->account_name ?? $item->name ?? 'Nama' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pengirim <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pengirim" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bank Pengirim <span class="text-danger">*</span></label>
                                <input type="text" name="nama_bank" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No Rekening Pengirim <span class="text-danger">*</span></label>
                                <input type="text" name="no_rekening" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                                <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,application/pdf" required>
                                <small class="text-muted">Format: JPG, PNG, PDF (Max: 2MB)</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitPayment">
                            <i class="fas fa-upload"></i> Unggah Bukti
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle payment modal
            document.querySelectorAll('.bayar-btn').forEach(function (button) {
                button.addEventListener('click', function () {
                    const bulan = this.dataset.bulan;
                    const tahunAjaran = this.dataset.tahunAjaran;
                    const nominal = this.dataset.nominal;
                    
                    // Update modal content
                    document.getElementById('modal-bulan').textContent = bulan;
                    document.getElementById('modal-tahun-ajaran').textContent = tahunAjaran;
                    document.getElementById('modal-nominal').textContent = 'Rp ' + nominal;
                    
                    // Update form fields
                    document.getElementById('form-bulan').value = bulan;
                    document.getElementById('form-tahun-ajaran').value = tahunAjaran;
                    
                    // Reset form
                    const form = document.getElementById('paymentForm');
                    if (form) {
                        // Reset only the form fields, not the hidden inputs we just set
                        const inputs = form.querySelectorAll('input:not([type="hidden"]), select, textarea');
                        inputs.forEach(input => {
                            if (input.type === 'file') {
                                input.value = '';
                            } else if (input.tagName === 'SELECT') {
                                input.selectedIndex = 0;
                            } else if (input.type === 'text') {
                                input.value = '';
                            }
                        });
                    }
                });
            });
            
            // Handle form submission
            const paymentForm = document.getElementById('paymentForm');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    const submitBtn = document.getElementById('submitPayment');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                        submitBtn.disabled = true;
                    }
                });
            }
        });
    </script>
@endpush
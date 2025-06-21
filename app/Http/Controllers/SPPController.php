<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bank;
use App\Models\Kelas;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\ConfirmPaymentRequest;
use App\Models\DetailPaymentSpp;
use App\Models\PaymentSpp;
use App\Models\SppSetting;
use App\Models\BankAccount;

class SPPController extends Controller
{
    //======================================================================
    // METHOD UNTUK ADMIN/TU
    //======================================================================

    /**
     * Menampilkan daftar semua murid untuk Admin.
     */
    public function murid(Request $request)
    {
        $bulanIni = Carbon::now()->format('F');
        $tahunAjaranAktif = '2024/2025';

        // Filter berdasarkan kelas jika diperlukan
        $user = auth()->user();
        $selectedKelas = $request->get('kelas_id');

        $studentsQuery = User::where('role', 'murid')
            ->with([
                'muridDetail',
                'kelas',
                'payment' => function ($q) use ($tahunAjaranAktif) {
                    $q->where('year', $tahunAjaranAktif);
                },
                //  relasi detailPaymentSpp 
                'detailPaymentSpp' => function ($query) use ($bulanIni, $tahunAjaranAktif) {
                    $query->where('month', $bulanIni)
                        ->whereHas('payment', function ($q) use ($tahunAjaranAktif) {
                            $q->where('year', $tahunAjaranAktif);
                        });
                }
            ]);

        // Filter berdasarkan kelas jika ada
        if ($selectedKelas) {
            $studentsQuery->where('kelas_id', $selectedKelas);
        }

        $murid = $studentsQuery->get();
        $kelas = Kelas::all(); // Untuk dropdown filter

        return view("spp::murid.index", compact('murid', 'bulanIni', 'kelas', 'selectedKelas'));
    }

    /**
     * PERBAIKAN: Menampilkan detail pembayaran berdasarkan user_id
     */
    public function detail($userId)
    {
        try {
            $user = User::with(['muridDetail', 'kelas'])->find($userId);

            if (!$user) {
                return redirect()->route('spp.murid.index')
                    ->with('error', 'Data murid tidak ditemukan.');
            }

            $tahunAjaranAktif = '2024/2025';

            // Cari atau buat record pembayaran jika belum ada
            $payment = PaymentSpp::firstOrCreate([
                'user_id' => $userId,
                'year' => $tahunAjaranAktif
            ], [
                'total_amount' => 0,
                'status' => 'pending'
            ]);

            // Muat relasi yang diperlukan
            $payment->load([
                "detailPayment.user.muridDetail",
                "detailPayment.user.kelas",
                "detailPayment.aprroveBy",
                "user"
            ]);

            return view("spp::murid.show", compact("payment"));
        } catch (Exception $e) {
            Log::error('Error pada method detail SPP: ' . $e->getMessage());
            return redirect()->route('spp.murid.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail pembayaran.');
        }
    }

    /**
     * PERBAIKAN: Memproses konfirmasi pembayaran manual oleh Admin.
     */
    public function updatePembayaran(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:detail_payment_spps,id',
            'status' => 'required|in:paid,rejected,pending',
            'admin_note' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $detailPayment = DetailPaymentSpp::findOrFail($request->payment_id);
            $detailPayment->status = $request->status;
            $detailPayment->admin_note = $request->admin_note;
            $detailPayment->approved_by = Auth::id();
            $detailPayment->approved_at = now();
            $detailPayment->save();

            DB::commit();

            $statusText = $request->status == 'paid' ? 'disetujui' : 'ditolak';
            return back()->with('success', "Pembayaran berhasil {$statusText}.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error update pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }

    /**
     * TAMBAHAN: Method untuk konfirmasi pembayaran dari modal
     */
    public function confirmPayment($id)
    {
        try {
            DB::beginTransaction();

            $detailPayment = DetailPaymentSpp::findOrFail($id);
            $detailPayment->status = 'paid';
            $detailPayment->approved_by = Auth::id();
            $detailPayment->approved_at = now();
            $detailPayment->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi.'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error confirm payment: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengkonfirmasi pembayaran.'
            ]);
        }
    }

    //======================================================================
    // METHOD UNTUK MURID
    //======================================================================

    /**
     * Menampilkan halaman tagihan SPP untuk murid yang sedang login.
     * DIPERBAIKI: Integrasi yang lebih baik dengan SppSetting dari SettingController
     */
    public function tagihanMurid()
    {
        try {
            $user = Auth::user();
            $tahunAjaranAktif = '2024/2025';

            // Pastikan user memiliki kelas_id
            if (!$user->kelas_id) {
                return view('murid::pembayaran.index', [
                    'tagihanBulanan' => collect(),
                    'bank' => BankAccount::where('is_active', 1)->get(),
                    'message' => 'Data kelas tidak ditemukan. Silakan hubungi Administrator untuk mengatur kelas Anda.'
                ]);
            }

            // Ambil data kelas
            $kelas = $user->kelas;

            if (!$kelas) {
                return view('murid::pembayaran.index', [
                    'tagihanBulanan' => collect(),
                    'bank' => BankAccount::where('is_active', 1)->get(),
                    'message' => 'Data kelas tidak ditemukan. Silakan hubungi Administrator.'
                ]);
            }

            // PERBAIKAN: Ambil setting SPP dari tabel yang ada
            // Jika tabel spp_setting tidak memiliki kelas_id, ambil setting global
            $sppSetting = DB::table('spp_setting')->first();

            if (!$sppSetting) {
                return view('murid::pembayaran.index', [
                    'tagihanBulanan' => collect(),
                    'bank' => BankAccount::where('is_active', 1)->get(),
                    'kelas' => $kelas,
                    'message' => 'Setting SPP belum tersedia. Silakan hubungi Administrator.'
                ]);
            }

            // Ambil data pembayaran yang sudah ada
            $payment = PaymentSpp::where('user_id', $user->id)
                ->where('year', $tahunAjaranAktif)
                ->first();

            // Daftar bulan dalam tahun ajaran
            $bulanList = [
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember',
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni'
            ];

            // Buat array tagihan bulanan berdasarkan struktur payment_spps yang ada
            $tagihanBulanan = collect();

            foreach ($bulanList as $englishMonth => $indonesianMonth) {
                $status = 'Belum Lunas';

                if ($payment) {
                    $paymentStatus = $payment->{$englishMonth};
                    switch ($paymentStatus) {
                        case 'paid':
                            $status = 'Lunas';
                            break;
                        case 'free':
                            $status = 'Gratis';
                            break;
                        case 'unpaid':
                        default:
                            $status = 'Belum Lunas';
                            break;
                    }
                }

                $tagihanBulanan->push([
                    'bulan' => $indonesianMonth,
                    'tahun_ajaran' => $tahunAjaranAktif,
                    'jumlah' => $sppSetting->amount,
                    'formatted_amount' => number_format($sppSetting->amount, 0, ',', '.'),
                    'status' => $status,
                    'detail' => null,
                    'can_pay' => $status === 'Belum Lunas',
                    'spp_setting_id' => $sppSetting->id
                ]);
            }

            // Ambil data bank
            $bank = BankAccount::where('is_active', 1)->get();

            return view('murid::pembayaran.index', compact('tagihanBulanan', 'bank', 'kelas'));
        } catch (Exception $e) {
            Log::error('Error tagihanMurid: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return view('murid::pembayaran.index', [
                'tagihanBulanan' => collect(),
                'bank' => BankAccount::where('is_active', 1)->get(),
                'error' => 'Terjadi kesalahan saat memuat data tagihan. Silakan refresh halaman.'
            ]);
        }
    }
    /**
     * PERBAIKAN: Membuat record pembayaran baru saat murid menekan tombol "Bayar".
     * Diperbaiki untuk konsistensi dengan SppSetting dari SettingController
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'bulan_dibayar' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'bank_account_id' => 'required|exists:bank_accounts,id', // Sesuaikan dengan nama tabel yang tepat
            'nama_pengirim' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:255',
            'bukti_pembayaran' => 'required|file|mimes:jpeg,jpg,png,pdf|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Validasi kelas_id
            if (!$user->kelas_id) {
                return back()->with('error', 'Data kelas Anda tidak ditemukan. Silakan hubungi Administrator.');
            }

            // Cari atau buat PaymentSpp untuk user ini
            $payment = PaymentSpp::firstOrCreate([
                'user_id' => $user->id,
                'year' => $request->tahun_ajaran
            ], [
                'total_amount' => 0,
                'status' => 'pending'
            ]);

            // Cek apakah sudah ada pembayaran untuk bulan ini
            $existingDetail = DetailPaymentSpp::where('payment_id', $payment->id)
                ->where('month', $request->bulan_dibayar)
                ->first();

            if ($existingDetail) {
                return back()->with('error', 'Pembayaran untuk bulan ' . $request->bulan_dibayar . ' sudah pernah dilakukan.');
            }

            // Upload file bukti pembayaran
            $fileName = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $fileName = 'pembayaran-' . time() . '-' . $user->id . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images/bukti_payment', $fileName);
            }

            // PERBAIKAN: Ambil nominal dari SppSetting (konsisten dengan SettingController)
            $sppSetting = SppSetting::where('kelas_id', $user->kelas_id)
                ->where('tahun_ajaran', $request->tahun_ajaran)
                ->where(function ($query) use ($request) {
                    $query->where('bulan', $request->bulan_dibayar)
                        ->orWhereNull('bulan');
                })
                ->orderBy('bulan') // Prioritaskan setting khusus bulan
                ->first();

            if (!$sppSetting) {
                return back()->with('error', 'Setting SPP untuk bulan ' . $request->bulan_dibayar . ' tidak ditemukan. Silakan hubungi Administrator.');
            }

            // Validasi bank account
            $bankAccount = BankAccount::find($request->bank_account_id);
            if (!$bankAccount) {
                return back()->with('error', 'Rekening tujuan tidak valid.');
            }

            // Buat detail pembayaran
            DetailPaymentSpp::create([
                'payment_id' => $payment->id,
                'user_id' => $user->id,
                'month' => $request->bulan_dibayar,
                'amount' => $sppSetting->amount,
                'status' => 'pending',
                'file' => $fileName,
                'date_file' => now()->format('Y-m-d'),
                'sender' => $request->nama_pengirim,
                'bank_sender' => $request->nama_bank,
                'rekening_pengirim' => $request->no_rekening,
                'destination_bank' => $request->bank_account_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update total amount di payment utama
            $payment->total_amount += $sppSetting->amount;
            $payment->save();

            DB::commit();

            return redirect()->route('murid.pembayaran.index')
                ->with('success', 'Bukti pembayaran berhasil dikirim. Mohon tunggu konfirmasi dari Administrator.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error Create Payment: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan halaman edit untuk upload bukti pembayaran.
     */
    public function editPayment($id)
    {
        try {
            $payment = DetailPaymentSpp::where('user_id', Auth::id())->findOrFail($id);

            if ($payment->status == 'paid') {
                return redirect()->route('murid.pembayaran.index')
                    ->with('error', 'Pembayaran untuk bulan ini sudah lunas.');
            }

            // Ambil data rekening tujuan milik Admin/TU
            $accountbanks = User::where('role', 'Admin')->with('banks')->first();

            // Ambil daftar semua bank untuk dropdown
            $bank = Bank::all();

            if (!$accountbanks) {
                return back()->with('error', 'Rekening tujuan pembayaran tidak ditemukan. Silakan hubungi Administrator.');
            }

            return view('murid::pembayaran.edit', compact('payment', 'accountbanks', 'bank'));
        } catch (Exception $e) {
            Log::error('Error Edit Payment: ' . $e->getMessage());
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }
    }

    /**
     * Memproses update bukti pembayaran dari murid.
     */
    public function updatePayment(ConfirmPaymentRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $payment = DetailPaymentSpp::where('user_id', Auth::id())->findOrFail($id);

            // Hapus file lama jika ada file baru
            if ($request->hasFile('file') && $payment->file) {
                Storage::delete('public/images/bukti_payment/' . $payment->file);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $file_payment = 'pembayaran-' . time() . '-' . Auth::id() . "." . $file->getClientOriginalExtension();

                // Simpan file baru
                $file->storeAs('public/images/bukti_payment', $file_payment);

                $payment->file = $file_payment;
            }

            $payment->status              = 'pending';
            $payment->date_file           = $request->date_file;
            $payment->sender              = $request->sender;
            $payment->bank_sender         = $request->bank_sender;
            $payment->destination_bank    = $request->destination_bank;
            $payment->save();

            DB::commit();

            return redirect()->route('murid.pembayaran.index')
                ->with('success', 'Bukti pembayaran berhasil diperbarui. Mohon tunggu konfirmasi dari Administrator.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error Update Bukti Pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui bukti pembayaran.');
        }
    }

    /**
     * Method untuk menampilkan pembayaran untuk murid dengan validasi keamanan
     */
    public function showForMurid()
    {
        $user = auth()->user();
        $kelas_id = request('kelas_id', $user->kelas_id);

        // Validasi keamanan - murid hanya bisa melihat pembayaran kelas mereka sendiri
        if ($user->kelas_id != $kelas_id) {
            abort(403, 'Anda tidak memiliki akses ke data pembayaran kelas ini');
        }

        return $this->tagihanMurid();
    }
}

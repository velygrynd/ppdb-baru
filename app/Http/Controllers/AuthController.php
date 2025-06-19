<?php

namespace App\Http\Controllers;

use App\Models\dataMurid;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\RegisterRequest;
use App\Models\PpdbSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use ErrorException;

class AuthController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function registerView()
    {
        if (!PpdbSetting::isOpen()) {
            return view('ppdb::auth.closed', [
                'message' => PpdbSetting::getClosedMessage(),
            ]);
        }

        return view('ppdb::auth.register');
    }

    public function registerStore(RegisterRequest $request)
    {
        if (!PpdbSetting::isOpen()) {
            Session::flash('error', PpdbSetting::getClosedMessage());
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            // Ambil nama depan sebagai username
            $username = explode(' ', trim($request->name))[0];

            $register = new User();
            $register->name     = $request->name;
            $register->username = $username;
            $register->email    = $request->email;
            $register->role     = 'Guest';
            $register->password = bcrypt($request->password);
            $register->save();

            if ($register) {
                $murid = new dataMurid();
                $murid->user_id       = $register->id;
                $murid->whatsapp      = $request->whatsapp;
                $murid->jenis_kelamin = $request->jenis_kelamin;
                $murid->save();
            }

            $register->assignRole($register->role);

            DB::commit();
            Session::flash('success', 'Success, Data Berhasil dikirim!');
            return redirect()->route('login');
        } catch (ErrorException $e) {
            DB::rollBack();
            throw new ErrorException($e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginApiController extends Controller
{
    public function store(Request $request){

        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $status = 401;
        $response = [
            'error' => 'Proses masuk gagal!. Silahkan coba kembali.',
        ];

        if(Auth::attempt($credentials)){
            $token = $request->user()->session()->createToken('access_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => $request->input('username').' login successfully !',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Login failed !"
            ], 401);
        }

        return response()->json($response, $status);
   }

   public function logout(Request $request){

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

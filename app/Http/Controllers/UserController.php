<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|max:255',
             'username' => 'required|string|max:255',
           'password' => 'required|string|min:2|confirmed',
        //    'jml_saldo' => 'required|string|max:999999999999',
         ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            // 'email' => $request->get('email'),
            'username' => $request->get('username'),
            // 'jml_saldo' => $request->get('jml_saldo'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
             return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
             return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
             return response()->json(['token_absent'], $e->getStatusCode());

        }
        
        return response()->json(compact('user'));
    }
    public function saldo(Request $request, $id){
        try{
            if(!$user =JWTAuth::parsetoken()->authenticate()){
                return response()->json(['user_not_found'], 404);
            }
        }catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            return response()->json(['token_expired'], $e->getStatusCode());
        }catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json(['token_invalid'], $e->getStatusCode());
        }catch (Tymon\JWTAuth\Exceptions\JWTException $e){
             return response()->json(['token_absent'], $e->getStatusCode());
        }
       
        $edit = User::where('id', $id)->first();
        $edit->saldo =$edit->saldo + $request->input('saldo');
        $edit->save();
        $message ="berhasil input saldo";
        return response()->json(compact('edit', 'pesan'));
    }

   
   
}

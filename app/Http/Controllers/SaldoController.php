<?php

namespace App\Http\Controllers;
use App\saldo;
use App\user;
use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class SaldoController extends Controller
{
   public function index()
   {
   	$data = saldo::all();
   	return $data;
   }
   public function store(Request $request)
   {
   	try{
   		if(! $akun = JWTAuth::parseToken()->authenticated()){
   			return response()->json([user_not found], 404);
   		}
   		$user = user::where('id', $akun['id'])->first();
   		if($request->jenis=='kredit'){
   			if($user->saldo < $request->input('jumlah')){
   				return response()->json(['saldo tidak cukup'], 404);
   			}
   		}else if($request->jenis!='debit'){
   			return response()->json(['error'='jenis_salah'], 400);
   		}
   		$data = new saldo();
   		$data->$username =$akun['username'];
   		$data->jenis=$request->input('jenis');
   		$data->nama_transaksi =$request->input('nama_transaksi');
   		$data->jml_saldo=$request->input('jml_saldo');
   		$data->save();
   		if($request->jenis=='debit'){
   			$user->saldo=$user->saldo + $request->input('jml_saldo');
   		}else{
   			$user->saldo=$user->saldo - $request->input('jml_saldo');
   		}

   		$user->save();
   		return response()->json(compact('data', 'user'));
   	}catch(\Excpeption $e){
   		return response()->json([
   			'status'=>'0', 
   			'message' =>'gagal menambah'
   		]);
   	}
}
   	public function update(Request $request)
   	{
   		try{
   			if(! $akun = JWTAuth::parseToken()->authenticated()){
   			return response()->json([user_not found], 404);
   		}
   		$user::where('id', $akun['id'])->first();
   		$data = new saldo();
   		$data->$username =$akun['username'];
   		$data->jenis=$request->input('jenis');
   		$data->nama_transaksi =$request->input('nama_transaksi');
   		$data->jml_saldo=$request->input('jml_saldo');
   		$data->save();
   		return response()->json(compact('data', 'user'));
   		}catch(\Exception $e){
   			return response()->json([
   				'status'=> '0',
   				'message'=>'gagal menambah'
   			]);
   		}
   	}
   
}


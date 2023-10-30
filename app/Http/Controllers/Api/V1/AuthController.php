<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails())
            return $this->ResponseRequest('error',$validator->errors()->all(),[],422);

        if (auth()->attempt(['email'=>$request->email,'password'=>$request->password])){
            auth()->user()->tokens()->delete();
            return $this->ResponseRequest('success',[],['user'=>auth()->user(),'token'=>auth()->user()->createToken('eshop')->plainTextToken],200);
        }

        return $this->ResponseRequest('error',["login error"],[],404);
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'jender' => 'required',
            'imageUrl' => 'required',
            'phone' => 'required|unique:users|min:11|max:11',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails())
            return $this->ResponseRequest('error',$validator->errors()->all(),[],422);

        $data = $request->all();
        $user = User::query()->create($data);

        return $this->ResponseRequest('success',[],['user'=>$user,'token'=>$user->createToken('eshop')->plainTextToken],200);
    }
}

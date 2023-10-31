<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $carts = Cart::query()->where('user_id',Auth::id())->with('product')->get();

        return $this->ResponseRequest('success','',$carts);
    }

    public function add(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->ResponseRequest('error',$validator->errors()->all(),[],422);
        }

        $c = Cart::query()->where([['user_id',Auth::id()],['product_id',$request->product_id]])->exists();

        if ($c){
            Cart::query()->where([['user_id',Auth::id()],['product_id',$request->product_id]])->increment('count',1);
        }else{
            $data['user_id'] = Auth::id();
            $data['count'] = 1;
            Cart::query()->create($data);
        }

        return $this->ResponseRequest('success','',['message' => 'product added to cart']);
    }

    public function remove(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->ResponseRequest('error',$validator->errors()->all(),[],422);
        }

        $c = Cart::query()->where([['user_id',Auth::id()],['product_id',$request->product_id]])->count();

        if ($c > 1){
            Cart::query()->where([['user_id',Auth::id()],['product_id',$request->product_id]])->decrement('count',1);
        }else{
            Cart::query()->where([['user_id',Auth::id()],['product_id',$request->product_id]])->delete();
        }

        return $this->ResponseRequest('success','',['message' => 'product removed from cart']);
    }

    public function delete(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->ResponseRequest('error',$validator->errors()->all(),[],422);
        }

        Cart::query()->findOrFail($request->cart_id)->delete();

        return $this->ResponseRequest('success','',['message' => 'product deleted from cart']);
    }
}

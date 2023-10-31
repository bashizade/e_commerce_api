<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $orders = Order::query()->where('user_id',Auth::id())->with(['address','payment','products'])->get();

        return $this->ResponseRequest('success','',$orders);
    }

    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->ResponseRequest('error',$validator->errors()->all(),[],422);
        }

        $price = 0;
        $cart = Cart::query()->where('user_id',Auth::id())->get();
        foreach ($cart as $item){
            $price += ($item->product->price_off == 0 ? $item->product->price * $item->count : $item->product->price_off * $item->count);
        }

        $order = Order::query()->create([
            'user_id' => Auth::id(),
            'address_id' => $request->address_id,
            'price' => $price,
            'code' => 0,
            'status' => 1
        ]);

        $order->update([
            'code' => 'sh-'.$order->id,
        ]);

        foreach ($cart as $item){
            OrderProduct::query()->create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'count' => $item->count,
                'price' => ($item->product->price_off == 0 ? $item->product->price * $item->count : $item->product->price_off * $item->count)
            ]);
            $item->product()->update([
                'count' =>  ($item->product->count - $item->count),
            ]);
        }

        Cart::query()->where('user_id',Auth::id())->delete();

        return $this->ResponseRequest('success','',['message' => 'Order created']);
    }

    public function change_status(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->ResponseRequest('error',$validator->errors()->all(),[],422);
        }

        $order = Order::query()->findOrFail($request->order_id)->update([
            'status' => $request->status
        ]);

        return $this->ResponseRequest('success','',['message' => 'Order status changed']);
    }

    public function all(): \Illuminate\Http\JsonResponse
    {
        $orders = Order::query()->with(['address','payment','products'])->get();

        return $this->ResponseRequest('success','',$orders);
    }
}

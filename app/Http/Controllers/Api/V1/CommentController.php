<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function comments(): \Illuminate\Http\JsonResponse
    {
        $comments = ProductComment::all();
        return $this->ResponseRequest('success','',[$comments]);
    }

    public function productComments($product_id): \Illuminate\Http\JsonResponse
    {
        $comments = ProductComment::query()->where('product_id',$product_id)->get();
        return $this->ResponseRequest('success','',[$comments]);
    }

    public function change_status(Request $request,ProductComment $productComment): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(),[
           'status' => 'required'
        ]);
        if ($validator->fails())
            return $this->ResponseRequest('error',$validator->errors()->all(),[],422);

        $data = $request->all();

        $productComment->update($data);

        return $this->ResponseRequest('success','',['message' => 'status changed']);
    }
}

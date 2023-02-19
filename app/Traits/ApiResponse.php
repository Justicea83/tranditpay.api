<?php


namespace App\Traits;



use Illuminate\Http\JsonResponse;

trait ApiResponse {
    public function successResponse($data,$code = JsonResponse::HTTP_OK){
        return response()->json(['data'=>$data],$code);
    }
    public function errorResponse($message,$code = JsonResponse::HTTP_BAD_REQUEST){
        return response()->json(['error'=>$message,'code'=>$code],$code);
    }
    public function noContent(){
        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\News;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function getNews(Request $request)
    {
        try {
            $data = News::with(['author'])->paginate($request->take);

            return ResponseFormatter::success([
                'data' => $data->items(),
                'page' => $data->currentPage(),
                'take' => $data->perPage(),
                'total' => $data->total(),
                'total_page' => ceil($data->total() / $data->perPage()),
            ], 'Get News Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => $err,
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function getNewsById(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'news_id' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => $validator->errors()->all(),
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $data = News::with(['author'])->where('id', $request->news_id)->first();

            return ResponseFormatter::success([
                'data' => $data,
            ], 'Get News Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => $err->getMessage(),
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }
}

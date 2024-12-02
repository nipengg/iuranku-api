<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupNews;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupNewsController extends Controller
{
    public function getGroupNews(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'group_id' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $groupNews = GroupNews::with(['group', 'author'])->where('id', $request->group_id)->get();

            return ResponseFormatter::success([
                'group_news' => $groupNews
            ], 'Get Group News Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function insertGroupNews(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'news_title' => ['required', 'string'],
                'content' => ['required', 'string'],
                'author_id' => ['required', 'integer'],
                'group_id' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $groupNews = GroupNews::create([
                'news_title' => $request->news_title,
                'content' => $request->content,
                'author_id' => $request->author_id,
                'group_id' => $request->group_id,
            ]);

            return ResponseFormatter::success([
                'groupNews' => $groupNews
            ], 'Create Group New Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function updateGroupNews(Request $request)
    {
        try {
    
            $validator = Validator::make($request->all(), [
                'group_news_id' => ['required', 'integer'],
                'content' => ['required', 'string'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $data = $request->all();
            
            $groupNews = GroupNews::find($data['group_news_id']);

            if (!$groupNews) {
                return ResponseFormatter::error([
                    'message' => 'Data Group News not Found',
                    'error' => 'Data Group News not Found',
                ], 'Data not Found', 404);
            }

            $groupNews->update($data);

            return ResponseFormatter::success([
                'groupNews' => $groupNews
            ], 'Update Group New Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function deleteGroupNews(Request $request)
    {
        try {
    
            $validator = Validator::make($request->all(), [
                'group_news_id' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => 'Something went wrong..',
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $data = $request->all();
            $groupNews = GroupNews::find($data['group_news_id']);

            if (!$groupNews) {
                return ResponseFormatter::error([
                    'message' => 'Data Group News not Found',
                    'error' => 'Data Group News not Found',
                ], 'Data not Found', 404);
            }

            $groupNews->delete();

            return ResponseFormatter::success([
                'messages' => 'Data Deleted'
            ], 'Delete Group New Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\GroupNews;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

            $data = GroupNews::with(['group', 'author'])
                ->where('group_id', $request->group_id)
                ->paginate($request->take);

            return ResponseFormatter::success([
                'data' => $data->items(),
                'page' => $data->currentPage(),
                'take' => $data->perPage(),
                'total' => $data->total(),
                'total_page' => ceil($data->total() / $data->perPage()),
            ], 'Get Group News Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }

    public function getGroupNewsById(Request $request)
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

            $data = GroupNews::with(['group', 'author'])->where('id', $request->group_news_id)->first();

            return ResponseFormatter::success([
                'data' => $data,
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
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            if ($validator->fails()) {
                return ResponseFormatter::error([
                    'message' => $validator->errors()->all(),
                    'error' => $validator->errors()->all(),
                ], 'Validation Error', 400);
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('group_news_images', 'public');
            }

            // Create the group news
            $groupNews = GroupNews::create([
                'news_title' => $request->news_title,
                'content' => $request->content,
                'author_id' => $request->author_id,
                'group_id' => $request->group_id,
                'image' => $imagePath,
            ]);

            return ResponseFormatter::success([
                'data' => $groupNews
            ], 'Create Group News Success!');
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => $err->getMessage(),
                'error' => $err->getMessage(),
            ], 'Something went wrong..', 500);
        }
    }


    public function updateGroupNews(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'news_title' => ['required', 'string'],
                'group_news_id' => ['required', 'integer'],
                'content' => ['required', 'string'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
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
    
            if ($request->hasFile('image')) {
                if ($groupNews->image && Storage::disk('public')->exists($groupNews->image)) {
                    Storage::disk('public')->delete($groupNews->image);
                }
    
                $image = $request->file('image');
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
    
                $image->storeAs('group_news_images', $imageName, 'public');

                $data['image'] = 'group_news_images/' . $imageName;
            }
    
            $groupNews->update($data);
    
            return ResponseFormatter::success([
                'data' => $groupNews
            ], 'Update Group News Success!');
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
    
            if ($groupNews->image) {
                if ($groupNews->image && Storage::disk('public')->exists($groupNews->image)) {
                    Storage::disk('public')->delete($groupNews->image);
                }
            }
    
            // Now delete the group news entry
            $groupNews->delete();
    
            return ResponseFormatter::success([
                'messages' => 'Data Deleted Successfully'
            ], 'Delete Group News Success!');
            
        } catch (Exception $err) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong..',
                'error' => $err,
            ], 'Something went wrong..', 500);
        }
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\News;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class NewsController extends Controller
{
    public function index()
    {
        $data = News::all();

        return view('admin.news.index', [
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'news_title' => 'required|max:50',
            'content' => 'required',
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()->all();

            $errorListItems = '';

            foreach ($errors as $error) {
                $errorListItems .= "<li>$error</li>";
            }

            $errorMessage = "<ul>$errorListItems</ul>";

            Alert::html('Invalid Input', $errorMessage, 'error');

            return redirect()->back()->withInput();
        }

        News::create([
            'news_title' => $data['news_title'],
            'content' => $data['content'],
            'author_id' => Auth::user()->id,
        ]);

        Alert::success('Success!', 'News Stored');
        return redirect()->route('admin.news.index');
    }

    public function detail($ids)
    {
        $id = Crypt::decryptString($ids);

        $data = News::findOrFail($id);

        return view('admin.news.show', [
            'data' => $data
        ]);
    }

    public function edit($ids)
    {
        $id = Crypt::decryptString($ids);

        $data = News::findOrFail($id);

        return view('admin.news.edit', [
            'data' => $data
        ]);
    }

    public function update(Request $request, $ids)
    {
        $data = $request->all();
        $id = Crypt::decryptString($ids);

        $validator = Validator::make($request->all(), [
            'news_title' => 'required|max:250|string',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            $errorListItems = '';

            foreach ($errors as $error) {
                $errorListItems .= "<li>$error</li>";
            }

            $errorMessage = "<ul>$errorListItems</ul>";

            Alert::html('Invalid Input', $errorMessage, 'error');

            return redirect()->back()->withInput();
        }

        try {
            News::where('id', $id)->update([
                'news_title' => $data['news_title'],
                'content' => $data['content'],
            ]);
        } catch (\Throwable $th) {
            Alert::html('Invalid Input', 'Something went wrong...', 'error');

            return redirect()->back()->withInput();
        }

        Alert::success('Success!', 'News Updated');
        return redirect()->route('admin.news.index', Crypt::encryptString($id));
    }

    public function delete($ids)
    {
        $id = Crypt::decryptString($ids);
        try {
            $news = News::findOrFail($id);
            $news->delete();
        } catch (Exception $err) {
            Alert::html('Invalid Input', $err->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
        Alert::success('Success!', 'News Deleted');
        return redirect()->route('admin.news.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function store(ReplyRequest $request, Reply $reply)
	{
		$reply->content = $request->content;
		$reply->topic_id = $request->topic_id;
		$reply->user_id = Auth::id();
		$reply->save();

		session()->flash('success', '回复成功');

		return redirect()->to($reply->topic->link()); //这里依然调用 topic->link() 方法返回正确的url
	}

	public function destroy(Reply $reply)
	{	
		$this->authorize('destroy', $reply);
		
		$reply->delete();

		session()->flash('success', '删除回复成功');

		return redirect()->to($reply->topic->link());
	}
}
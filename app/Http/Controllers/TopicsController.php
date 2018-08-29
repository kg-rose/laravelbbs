<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Category;
use App\Models\User;
use App\Models\Link;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;
use App\Handlers\ImageUploadHandler;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, Topic $topic, User $user, Link $link)
	{
		$topics = $topic
			->withOrder($request->order)
			->paginate(20);
		
		$active_users = $user->getActiveUsers();
		$links = $link->getAllCached();
			
        return view('topics.index', compact('topics', 'active_users', 'links'));
	}

    public function show(Request $request, Topic $topic)
    {
		// 当有 slug 并且当数据库中存的这篇帖子的 slug 不等于 请求的slug
		if(isset($topic->slug) && ($topic->slug != $request->slug)) {
			return redirect($topic->link(), 301); //301 强制跳转，为了防止用户改浏览器地址最后面翻译出来的 slug
		}

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
		$categories = Category::all();
		
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function store(TopicRequest $request, Topic $topic)
	{
		$topic->fill($request->all());
        $topic->user_id = Auth::id();
		$topic->save();
		
		session()->flash('success', '发帖成功');

        return redirect()->to($topic->link());
	}

	public function edit(Topic $topic)
	{
		$this->authorize('update', $topic);

		$categories = Category::all();

		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		session()->flash('success', '编辑成功');

		return redirect()->to($topic->link());
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		session()->flash('success', '删除成功');

		return redirect()->route('topics.index');
	}

	/**
	 * simditor 图片上传
	 */
	public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 120);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }
}
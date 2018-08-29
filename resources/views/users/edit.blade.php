@extends('layouts.app')

@section('content')

<div class="container">
    <div class="panel panel-default col-md-10 col-md-offset-1">
        <div class="panel-heading">
            <h4>
                <i class="glyphicon glyphicon-edit"></i> 编辑个人资料
            </h4>
        </div>

        @include('components.error')

        <div class="panel-body">

            <form action="{{ route('users.update', $user->id) }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                {{-- 头像上传 --}}
                {{--  <div class="form-group">
                    <label for="" class="avatar-label">用户头像</label>
                    <input type="file" name="avatar">

                    @if($user->avatar)
                        <br>
                        <img class="thumbnail img-responsive" src="{{ $user->avatar }}" width="200" />
                    @endif
                </div>  --}}

                {{--  ajax 头像上传  --}}
                <div class="form-group">
                    <label for="uploader_button">用户头像</label>
                    <br>
            
                    <button type="button" class="btn btn-info btn-sm" id="uploader_button">
                        <i class="glyphicon glyphicon-cloud" id="uploader_button_icon"></i> 上传头像
                    </button>
            
                    <input type="file" id="uploader_file" name="file" style="display:none;">
                    <input id="uploader_image_url" type="hidden" name="avatar" value="{{old('avatar', $user->avatar)}}">

                    <div style="margin-top:10px">
                        <img src="{{ old('avatar', $user->avatar) }}" id="uploader_image_show" style="max-width: 200px; max-height: 200px;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email-field">邮 箱</label>
                    <input class="form-control" type="text" name="email" id="email-field" value="{{ old('email', $user->email) }}" disabled />
                </div>
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name-field">用户名</label>
                    <input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $user->name) }}" />
                </div>
                <div class="form-group{{ $errors->has('introduction') ? ' has-error' : '' }}">
                    <label for="introduction-field">个人简介</label>
                    <textarea name="introduction" id="introduction-field" class="form-control" rows="3">{{ old('introduction', $user->introduction) }}</textarea>
                </div>
                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/image_uploader.js') }}"></script>
@stop
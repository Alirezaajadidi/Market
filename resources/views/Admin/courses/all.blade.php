@extends('Admin.master')

@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <h1 class="page-header">Dashboard</h1>
        <div class="page-header head-section">
            <h2>دوره ها</h2>
            <div class="btn-group">
            <a href="{{route('courses.create')}}" class="btn btn-sm btn-primary">ارسال دوره</a>
            <a href="{{route('episodes.index')}}" class="btn btn-sm btn-danger">بخش ویدیو ها</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>

                <tr>
                    <th>عنوان مقاله</th>
                    <th>تعداد نظرات</th>
                    <th>مقدار بازدید</th>
                    <th>تعداد شرکت کننده ها</th>
                    <th>وضعیت دوره</th>
                    <th>تنظیمات</th>
                </tr>

                </thead>
                <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td><a href="{{$course->path()}}"> {{$course->title}}  </td>
                        <td>{{$course->commentCount}}</td>
                        <td>{{$course->viewCount}}</td>
                        <td>1</td>
                        <td>
                            @if($course->type == 'vip')
                                اعضای ویژه
                            @elseif($course->type =='cash')
                                نقدی
                            @else
                                رایگان
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('courses.destroy'  ,  $course->id) }}" method="post">
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                                <div class="btn-group btn-group-xs">
                                    <a href="{{ route('courses.edit' , $course->id) }}"
                                       class="btn btn-primary">ویرایش</a>
                                    <button type="submit" class="btn btn-danger">حذف</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach

                </tbody>

            </table>

        </div>
        <div style="text-align: center">
            {!! $courses->render() !!}
        </div>
    </div>
@endsection

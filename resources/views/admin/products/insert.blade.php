@extends('layouts.app')

@section('title', 'داشبورد مدیریت - لیست محصولات')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark mb-4">لیست محصولات</a>
                <div class="card">
                    <div class="card-header text-right">اضافه کردن محصول</div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger rtl text-right">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="custom-file">
                                <label for="image" class="custom-file-label">انتخاب تصویر</label>
                                <input type="file" id="image" name="image" accept="image/*"
                                       class="custom-control-input @error('image') is-invalid @enderror">
                            </div>

                            <div class="form-group">
                                <label for="name" class="text-right w-100 rtl">نام محصول:</label>
                                <input type="text" class="form-control rtl @error('name') is-invalid @enderror"
                                       name="name" id="name" value="{{ old('name') }}">
                            </div>

                            <div class="form-group">
                                <label for="description" class="text-right w-100 rtl">توضیحات:</label>
                                <textarea name="description" id="description"
                                          class="form-control rtl @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="price" class="text-right w-100 rtl">قیمت محصول:</label>
                                <input type="number" min="1" class="form-control @error('price') is-invalid @enderror"
                                       name="price" id="price" value="{{ old('price') }}">
                            </div>

                            <button type="submit" class="btn btn-success">ثبت</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-control-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endsection

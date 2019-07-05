@extends('layouts.app')

@section('title', 'داشبورد مدیریت')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-right">داشبورد مدیریت</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col text-center">
                                <h5>شماره حساب</h5>
                                <br>
                                <form
                                    action="{{ route('admin.setting.update', $setting) }}"
                                    method="post">
                                    @method('PUT')
                                    @csrf

                                    <div class="row">
                                        <div class="col d-flex justify-content-between">
                                            <input type="number" class="form-control" id="bankaccount_number"
                                                   name="setting_value"
                                                   value="{{ $setting->setting_value }}">
                                            <button type="submit" class="btn btn-sm btn-outline-success ml-2">ویرایش
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col text-center">
                                <h5>سفارشات</h5>
                                <br>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info">لیست
                                    سفارشات</a>
                            </div>

                            <div class="col text-center">
                                <h5>محصولات</h5>
                                <br>
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info">لیست
                                        محصولات</a>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-success text-white">محصول جدید</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'سبد خرید')

@section('styles')
    <link rel="stylesheet" href="{{ url('/datatable/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-right">سبد خرید</div>
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

                        @if(!session()->get('pay_code'))
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-center">تعداد</th>
                                        <th class="text-center">قیمت</th>
                                        <th class="text-center">توضیحات</th>
                                        <th class="text-center">عنوان</th>
                                        <th width="50" class="text-center">تصویر</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($items)
                                        @foreach ($items->products as $item)
                                            <tr>
                                                <td class="text-right">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-danger" data-toggle="modal"
                                                                data-target="#modal-cart-{{ $item->id }}">حذف
                                                        </button>
                                                    </div>
                                                    <!-- Modal HTML -->
                                                    <div id="modal-cart-{{ $item->id }}" class="modal fade">
                                                        <div class="modal-dialog modal-login">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ route('cart.destroy', ['order' => $items, 'product' => $item]) }}"
                                                                    method="post">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">حذف محصول از سبد
                                                                            خرید</h4>
                                                                        <button type="button" class="close"
                                                                                data-dismiss="modal"
                                                                                aria-hidden="true">&times;
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body rtl text-right">
                                                                        حذف محصول "{{ $item->name }}"
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <div
                                                                            class="d-flex justify-content-between w-100">
                                                                            <button class="btn btn-info text-white"
                                                                                    data-dismiss="modal"
                                                                                    aria-hidden="true">
                                                                                بستن
                                                                            </button>
                                                                            <button
                                                                                class="btn btn-sm btn-outline-danger">
                                                                                حذف
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <form
                                                        action="{{ route('cart.update', ['order' => $items, 'product' => $item]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="input-group mb-3">
                                                            <input type="hidden" name="product_id"
                                                                   value="{{ $item->id }}">
                                                            <input type="number" class="form-control text-center"
                                                                   name="quantity" min="1"
                                                                   value="{{ $item->pivot->quantity }}"
                                                                   style="width: 20px;">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-sm btn-warning text-white"
                                                                        type="submit">ویرایش
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="text-right">{{ number_format($item->price) }}</td>
                                                <td class="text-right">{{ $item->description }}</td>
                                                <td class="text-right">{{ $item->name }}</td>
                                                <td class="text-right">
                                                    <img src="{{ asset($item->image) }}"
                                                         alt="{{ $item->name }}"
                                                         width="50" height="50">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <h3 class="h3 text-center rtl">سفارش شما با موفقیت ثبت شد</h3>
                            <p class="text-center rtl">کد خرید: {{ session()->get('pay_code') }}</p>
                            <p class="text-center rtl">شماره حساب جهت پرداخت: {{ session()->get('bankaccount_number') }}</p>
                            <p class="text-center rtl">برای نهایی کردن سفارش خود،
                                <mark>شماره تراکنش</mark>
                                خود را در <a
                                    href="{{ route('user.orders.index') }}">قسمت سفارشات</a> ثبت نمایید.
                            </p>
                        @endif
                    </div>
                    @if(!session()->get('pay_code'))
                        @if($items)
                            <div class="card-footer d-flex justify-content-around align-items-center">
                                <p class="m-0">قیمت کل: <span
                                        class="font-weight-bold">{{ number_format(\App\Order::cardTotalPrice()) }}</span>
                                </p>
                                <a href="{{ route('cart.checkout') }}" class="btn btn-success">پرداخت نهایی</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('/datatable/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#table').DataTable();
        });
    </script>
@endsection

@extends('layouts.app')

@section('title', 'سفارش ' . $order->pay_code)

@section('styles')
    <link rel="stylesheet" href="{{ url('/datatable/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark mb-4">لیست سفارشات کاربران</a>
                <div class="card">
                    <div class="card-header text-right">سفارش {{ $order->pay_code }}</div>
                    <div class="card-body">

                        @if($errors->any())
                            <div class="alert alert-danger rtl text-right">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session()->get('success'))
                            <div class="alert alert-success rtl text-right">
                                <ul>
                                    <li>{{ session()->get('success') }}</li>
                                </ul>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">ایمیل سفارش دهنده</th>
                                    <th class="text-center">نام سفارش دهنده</th>
                                    <th class="text-center">تایید شده؟</th>
                                    <th class="text-center">پرداخت شده؟</th>
                                    <th class="text-center">تعداد محصولات</th>
                                    <th class="text-center">قیمت کل</th>
                                    <th class="text-center">کد خرید</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-right">
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-danger" data-toggle="modal"
                                                    data-target="#modal-order-{{ $order->id }}">حذف
                                            </button>
                                            @if($order->transaction_code)
                                                <form action="{{ route('admin.orders.update.verified', $order) }}"
                                                      method="post" class="d-inline-block mx-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="verified"
                                                           value="{{ ($order->verified == 1)?0:1 }}">
                                                    <button
                                                        class="btn btn-success">{{ ($order->verified == 1)?'عدم تایید':'تایید' }}</button>
                                                </form>
                                            @endif
                                        </div>
                                        <!-- Modal HTML -->
                                        <div id="modal-order-{{ $order->id }}" class="modal fade">
                                            <div class="modal-dialog modal-login">
                                                <div class="modal-content">
                                                    <form action="{{ route('orders.destroy', $order) }}"
                                                          method="post">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">حذف سفارش</h4>
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal"
                                                                    aria-hidden="true">&times;
                                                            </button>
                                                        </div>
                                                        <div class="modal-body rtl text-right">
                                                            حذف سفارش "{{ $order->pay_code }}"
                                                            @csrf
                                                            @method('DELETE')
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="d-flex justify-content-between w-100">
                                                                <button class="btn btn-info text-white"
                                                                        data-dismiss="modal" aria-hidden="true">
                                                                    بستن
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger">
                                                                    حذف
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">{{ $order->user->email }}</td>
                                    <td class="text-right">{{ $order->user->name }}</td>
                                    <td class="text-right">{{ ($order->verified)?'بله':'خیر' }}</td>
                                    <td class="text-right">{{ ($order->transaction_code)?'بله':'خیر' }}</td>
                                    <td class="text-right">{{ $order->orderTotalProductsCount() }}</td>
                                    <td class="text-right">{{ number_format($order->orderTotalPrice()) }}</td>
                                    <td class="text-right">{{ $order->pay_code }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h5 class="h5 text-right rtl py-4">محصولات موجود در این سفارش:</h5>

                        <div class="table-responsive">

                            <table id="table" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th class="text-center">قیمت</th>
                                    <th class="text-center">تعداد</th>
                                    <th class="text-center">توضیحات</th>
                                    <th class="text-center">عنوان</th>
                                    <th width="50" class="text-center">تصویر</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($order->products as $product)
                                    <tr>
                                        <td class="text-right">{{ number_format($product->pivot->unit_price) }}</td>
                                        <td class="text-right">{{ $product->pivot->quantity }}</td>
                                        <td class="text-right">{{ $product->description }}</td>
                                        <td class="text-right">{{ $product->name }}</td>
                                        <td class="text-right">
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                                 width="50" height="50">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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

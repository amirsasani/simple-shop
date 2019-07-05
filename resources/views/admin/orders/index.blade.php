@extends('layouts.app')

@section('title', 'سفارشات')

@section('styles')
    <link rel="stylesheet" href="{{ url('/datatable/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark mb-4">داشبورد مدیریت</a>
                <div class="card">
                    <div class="card-header text-right">لیست سفارشات کاربران</div>
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

                            <table id="table" class="table table-striped table-bordered" style="width:100%">
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
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="text-right">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-danger" data-toggle="modal"
                                                        data-target="#modal-order-{{ $order->id }}">حذف
                                                </button>
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
                                        <td class="text-right">{{ ($order->verfied)?'بله':'خیر' }}</td>
                                        <td class="text-right">{{ ($order->transaction_code)?'بله':'خیر' }}</td>
                                        <td class="text-right">{{ $order->orderTotalProductsCount() }}</td>
                                        <td class="text-right">{{ number_format($order->orderTotalPrice()) }}</td>
                                        <td class="text-right"><a href="{{ route('admin.orders.new_show', $order) }}">{{ $order->pay_code }}</a></td>
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

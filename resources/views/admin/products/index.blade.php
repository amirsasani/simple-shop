@extends('layouts.app')

@section('title', 'داشبورد مدیریت - لیست محصولات')

@section('styles')
    <link rel="stylesheet" href="{{ url('/datatable/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <a href="{{ route('admin.products.create') }}" class="btn btn-outline-dark mb-4">محصول جدید</a>
                <div class="card">
                    <div class="card-header text-right">لیست محصولات</div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table id="table" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">قیمت</th>
                                    <th class="text-center">توضیحات</th>
                                    <th class="text-center">عنوان</th>
                                    <th width="50" class="text-center">تصویر</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td class="text-right">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                   class="btn btn-warning">ویرایش</a>
                                                <button class="btn btn-danger" data-toggle="modal"
                                                        data-target="#modal-product-{{ $product->id }}">حذف
                                                </button>
                                            </div>
                                            <!-- Modal HTML -->
                                            <div id="modal-product-{{ $product->id }}" class="modal fade">
                                                <div class="modal-dialog modal-login">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.products.destroy', $product) }}" method="post">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">حذف محصول</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            </div>
                                                            <div class="modal-body rtl text-right">
                                                                حذف محصول "{{ $product->name }}"
                                                                @csrf
                                                                @method('DELETE')
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="d-flex justify-content-between w-100">
                                                                    <button class="btn btn-info text-white" data-dismiss="modal" aria-hidden="true">بستن</button>
                                                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">{{ number_format($product->price) }}</td>
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

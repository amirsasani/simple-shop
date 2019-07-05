@extends('layouts.app')

@section('title', 'داشبورد مدیریت - لیست محصولات')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if(count($products))
                    @foreach($products as $product)
                        <div class="card d-inline-block" style="width:250px">
                            <img class="card-img-top" src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                 style="max-height: 200px;">
                            <div class="card-body">
                                <h4 class="card-title text-right rtl">{{ $product->name }}</h4>
                                <p class="card-text text-right rtl">{{ $product->description }}</p>
                                <div class="d-flex justify-content-between">
                                    <span>{{ number_format($product->price) }}</span>
                                    <a href="{{ route('add-to-cart', $product) }}"
                                       class="btn btn-sm btn-outline-success">اضافه به سبد خرید</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3 class="h3 text-center">محصولی برای نمایش وجود ندارد</h3>
                @endif
            </div>
        </div>
    </div>
@endsection

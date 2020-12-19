@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Product Details') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="container">
                            <div class="w-100 text-center mb-2">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->title }}"
                                    style="max-height: 280px" class="img-fluid" />
                            </div>
                            <h3>{{ $product->title }} - {{ $product->price }} BDT</h3>
                            <div class="mt-3">
                                {{ $product->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

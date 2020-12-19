@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>{{ __('Products') }}</h3>
                            </div>
                            <div class="col-md-6">
                                <div class="text-right">
                                    <a href="{{ route('product.create') }}" class="btn btn-info text-white">
                                        {{ __('+ Add Product') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>{{ __($product->id) }}</td>
                                            <td>{{ __($product->title) }}</td>
                                            <td style="max-width: 350px">{{ __($product->description) }}</td>
                                            <td style="max-width: 150px">
                                                <img class="img-fluid" src="{{ asset($product->image) }}"
                                                    alt="{{ $product->title }}">
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('product.show', $product->id) }}">
                                                    <button class="btn btn-info text-white">View</button>
                                                </a>
                                                @if (Auth::check())
                                                    <br>
                                                    <a href="{{ route('product.edit', $product->id) }}">
                                                        <button class="btn btn-warning mt-3">Edit</button>
                                                    </a>
                                                    <br>
                                                    <form action="{{ route('product.destroy', $product->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger mt-3">Delete</button>
                                                    </form>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

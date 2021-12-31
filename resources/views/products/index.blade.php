@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.index') }}" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control"
                        value="{{ $title ?? '' }}">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">

                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From"
                            class="form-control" value="{{ $price_from ?? '' }}">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control"
                            value="{{ $price_to ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control" value="{{ $date ?? '' }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Variant</th>
                            <th width="150px">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->title }} <br> Created at :
                                    {{ $product->created_at->setTimezone('Asia/Dhaka')->format('d-M-y') }}</td>
                                <td class="col-5">{{ $product->description }}</td>
                                <td class="col-4">
                                    @foreach ($product->productVariantPrices->where('price', '>=', $price_from)->where('price', '<=', $price_to) as $vp)
                                       
                                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                                <dt class="col-sm-3 pb-0">
                                                    {{-- @foreach ($vp->variant_names as $variant)
                                                        {{ $variant }} . '/ ' 
                                                    @endforeach --}}

                                                    {{ $vp->name1 ? $vp->name1->variant : '' }}
                                                    {{ $vp->name2 ? '/ ' . $vp->name2->variant : '' }}
                                                    {{ $vp->name3 ? '/ ' . $vp->name3->variant : '' }}
                                                </dt>
                                                <dd class="col-sm-9">
                                                    <dl class="row mb-0">
                                                        <dt class="col-sm-4 pb-0">Price : {{ $vp->price }}</dt>
                                                        <dd class="col-sm-8 pb-0">InStock : {{ $vp->stock }}</dd>
                                                    </dl>
                                                </dd>
                                            </dl>
                                    @endforeach
                                    <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show
                                        more</button>
                                </td>
                                <td class="col-1">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach


                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} out of
                        {{ $products->total() }}
                    </p>
                </div>
                <div class="col-md-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

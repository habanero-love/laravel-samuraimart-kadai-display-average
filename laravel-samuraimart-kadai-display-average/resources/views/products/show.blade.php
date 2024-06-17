@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-center">
    <div class="row w-75">
        <div class="col-5 offset-1">
            @if ($product->image)
                <img src="{{ asset($product->image) }}" class="w-100 img-fluid">
                @else
                <img src="{{ asset('img/dummy.png')}}" class="w-100 img-fluid">
                @endif
        </div>
        <div class="col">
            <div class="d-flex flex-column">
                <h1 class="">
                    {{$product->name}}
                </h1>
                @if($reviews_average > 0)
                <p class="d-flex gap-1 align-items-center">
                    <span class="d-flex align-items-center">
                        @php
                            $reviews_average_round = round($reviews_average * 2) / 2;
                        @endphp
                        @for ($i=0; $i< floor($reviews_average_round); $i++)
                            <span class="ster-rating"></span>
                        @endfor
                        @if($reviews_average_round - floor($reviews_average_round) == 0.5)
                        <span class="ster-rating-half"></span>
                        @endif
                        @for ($i=ceil($reviews_average_round); $i<5; $i++)
                        <span class="ster-rating-none"></span>
                        @endfor
                    </span>
                    {{ number_format($reviews_average,2)}}
                </p>
                @endif
                <p class="">
                    {{$product->description}}
                </p>
                <hr>
                <p class="d-flex align-items-end">
                    ￥{{$product->price}}(税込)
                </p>
                <hr>
            </div>
            @auth
            <form method="POST" action="{{ route('carts.store') }}" class="m-3 align-items-end">
                @csrf
                <input type="hidden" name="id" value="{{$product->id}}">
                <input type="hidden" name="name" value="{{$product->name}}">
                <input type="hidden" name="price" value="{{$product->price}}">
                <input type="hidden" name="image" value="{{$product->image}}">
                <input type="hidden" name="carriage" value="{{$product->carriage_flag}}">
                <div class="form-group row">
                    <label for="quantity" class="col-sm-2 col-form-label">数量</label>
                    <div class="col-sm-10">
                        <input type="number" id="quantity" name="qty" min="1" value="1" class="form-control w-25">
                    </div>
                </div>
                <input type="hidden" name="weight" value="0">
                <div class="row">
                    <div class="col-7">
                        <button type="submit" class="btn samuraimart-submit-button w-100">
                            <i class="fas fa-shopping-cart"></i>
                            カートに追加
                        </button>
                    </div>
                    <div class="col-5">
                        @if(Auth::user()->favorite_products()->where('product_id', $product->id)->exists())
                                <a href="{{ route('favorites.destroy', $product->id) }}" class="btn samuraimart-favorite-button text-favorite w-100" onclick="event.preventDefault(); document.getElementById('favorites-destroy-form').submit();">
                                    <i class="fa fa-heart"></i>
                                    お気に入り解除
                                </a>
                            @else
                                <a href="{{ route('favorites.store', $product->id) }}" class="btn samuraimart-favorite-button text-favorite w-100" onclick="event.preventDefault(); document.getElementById('favorites-store-form').submit();">
                                    <i class="fa fa-heart"></i>
                                    お気に入り
                                </a>
                            @endif
                    </div>
                </div>
            </form>
            <form id="favorites-destroy-form" action="{{ route('favorites.destroy', $product->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
            <form id="favorites-store-form" action="{{ route('favorites.store', $product->id) }}" method="POST" class="d-none">
                @csrf
            </form>
            @endauth
        </div>

        <div class="offset-1 col-11">
            <hr class="w-100">
            <h3 class="float-left">カスタマーレビュー</h3>
        </div>

        <div class="offset-1 col-10">
            <div class="row">
                @foreach($reviews as $review)
                <div class="offset-md-5 col-md-5">
                    <h3 class="review-score-color">{{ str_repeat('★',$review->score)}}</h3>
                    <p class="h3">{{$review->title}}</p>
                    <p class="h3">{{$review->content}}</p>
                    <label>{{$review->created_at}} {{$review->user->name}}</label>
                </div>
                @endforeach
            </div><br />

            @auth
            <div class="row">
                <div class="offset-md-5 col-md-5">
                {{ Form::open([ 'route'=> 'reviews.store']) }}
                        <h4>評価</h4>
                        <select name="score" class="form-control m-2 review-score-color">
                            <option value="5" class="review-score-color">★★★★★</option>
                            <option value="4" class="review-score-color">★★★★</option>
                            <option value="3" class="review-score-color">★★★</option>
                            <option value="2" class="review-score-color">★★</option>
                            <option value="1" class="review-score-color">★</option>
                        </select>
                        <h4>タイトル</h4>
                        @error('title')
                            <strong>タイトルを入力してください</strong>
                        @enderror
                        {{ Form::text('title',null,['class'=>'form-control m-2']) }}
                        <h4>レビュー内容</h4>
                        @error('content')
                            {{ $message }}
                        @enderror
                        {{ Form::textarea('content',null,['class'=>'form-control m-2']) }}
                        {{ Form::hidden('product_id',$product->id) }}
                        {{ Form::button('レビューを追加',['type'=>'submit','class'=>'btn samuraimart-submit-button ml-2']) }}
                {{ Form::close() }}
                </div>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection
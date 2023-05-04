<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <!-- アイテム編集 -->
    <form action="/items/{{$product->id}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div>削除する画像を選択</div>
        @foreach($product->productImages as $product_image)
        <label>
            <img width="100" height="100" src=" {{asset('images/'.$product_image->image_url)}}">
            <input value="{{$product_image->id}}" type="checkbox" name="delete_images[]">
            {{ $product_image->image_url }}
        </label>
        @endforeach
        <div>
            追加する画像を選択
            <input type="file" name="product_images[]" multiple>
        </div>
        <div>
            <label>
                名前
                <input name="title" value="{{$product->title}}">
            </label>
        </div>
        <div>
            <label>
                備考
                <textarea cols="50" rows="4" name="description">{{ $product->description }}</textarea>
            </label>
        </div>
        <div>
            タグ
            @foreach($tags as $tag)
            <label>
                @if($tag->is_chosen===true)
                <input type="checkbox" name="product_tags[]" value="{{ $tag->id }}" checked>
                @else
                <input type="checkbox" name="product_tags[]" value="{{ $tag->id }}">
                @endif
                {{ $tag->name }}
            </label>
            @endforeach
        </div>
        <input type="submit">
    </form>
    <!-- アイテムステータス変更 -->
    <div>{{$product->japanese_product_status}}</div>
    <form action="{{route('items.borrow',['id'=>$product->id])}}" method="POST">
        @csrf
        <input type="submit" value="借りる">
    </form>
    <form action="{{route('items.cancel',['id'=>$product->id])}}" method="POST">
        @csrf
        <input type="submit" value="キャンセル">
    </form>
    <form action="{{route('items.return',['id'=>$product->id])}}" method="POST">
        @csrf
        <input type="submit" value="返す">
    </form>
</body>

</html>
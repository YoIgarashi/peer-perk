<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductTag;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use Illuminate\Support\Facades\File;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $japanese_product_statuses = Product::JAPANESE_STATUS;
        unset($japanese_product_statuses[1]);
        $product_tags = Tag::productTags()->get();
        $products = Product::availableProducts()->withRelations()->paginate(8)->map(function ($product) use ($japanese_product_statuses) {
            $product->japanese_status = $japanese_product_statuses[$product->status];
            return $product;
        });
        return view('backend_test.items', compact('products', 'japanese_product_statuses', 'product_tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend_test.items');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product_instance = new Product();
        $product_instance->title = $request->title;
        $product_instance->user_id = Auth::id();
        $product_instance->description = $request->description;
        $product_instance->request_id = $request->request_id;
        $product_instance->save();
        return view('backend_test.items');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::withRelations()->findOrFail($id);
        $product->description = $product->changeDescriptionReturnToBreakTag($product->description);
        return view('backend_test.item', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tags = Tag::productTags()->get()->map(function ($tag) {
            $tag->is_chosen = false;
            return $tag;
        });
        $chosen_product_tags = ProductTag::where('product_id', $id)->get();
        $product = Product::withRelations()->findOrFail($id);
        foreach ($chosen_product_tags as $chosen_product_tag) {
            $tags->find($chosen_product_tag->tag_id)->is_chosen = true;
        }
        return view('backend_test.edit_item', compact('product', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $images = $request->file('product_images');
        $this->addProductImages($images, $id);
        $this->deleteProductImages($request->delete_images);
        $this->updateProductTags($request->product_tags, $id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function addProductImages($images, $product_id)
    {
        if (!empty($images)) {
            foreach ($images as $image) {
                $product_image_instance = new ProductImage();
                $next_public_images_file_name = 'sample_product_' . (count(File::files(public_path('images'))) + 1) . '.jpeg';
                $image->move(public_path('images'), $next_public_images_file_name);
                $product_image_instance->product_id = $product_id;
                $product_image_instance->image_url = $next_public_images_file_name;
                $product_image_instance->save();
            }
        }
        return;
    }
    public function deleteProductImages($product_image_id_array)
    {
        if (!empty($product_image_id_array)) {
            foreach ($product_image_id_array as $product_image_id) {
                $product_image_instance = ProductImage::findOrFail($product_image_id);
                $product_image_instance->delete();
            }
        }
        return;
    }
    public function updateProductTags($product_tag_id_array, $product_id)
    {
        //ロジックめんどいから全部削除して追加する
        ProductTag::belongsToProduct($product_id)->delete();
        if (!empty($product_tag_id_array)) {
            foreach ($product_tag_id_array as $product_tag_id) {
                $product_tags_instance = new ProductTag();
                $product_tags_instance->product_id = $product_id;
                $product_tags_instance->tag_id = $product_tag_id;
                $product_tags_instance->save();
            }
        }
        //idはテーブルのid,tag_idはタグのid
        return;
    }
}
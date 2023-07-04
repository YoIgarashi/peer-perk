<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SlackController;
use App\Models\Product;
use App\Models\ProductLike;
use App\Models\ProductTag;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\User;

class ItemController extends Controller
{
    /**
     * @var SlackController
     */
    public function __construct(SlackController $slackController)
    {
        $this->slackController = $slackController;
        $this->slackAdminChannelId = $slackController->searchChannelId('peerperk管理者', true);
    }
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
        $products = Product::approvedProducts()->withRelations()->get()->map(function ($product) use ($japanese_product_statuses) {
            $product->data_tag = '[' . implode(',', $product->productTags->pluck('tag_id')->toArray()) . ']';
            //配送中は貸出中として表示
            if ($product->status === Product::STATUS['delivering']) {
                $product->japanese_status = $japanese_product_statuses[Product::STATUS['occupied']];
                $product->status = Product::STATUS['occupied'];
            } else {
                $product->japanese_status = $japanese_product_statuses[$product->status];
            }
            if ($product->productLikes->contains('user_id', Auth::id())) {
                $product->isLiked = 1;
            } else {
                $product->isLiked = 0;
            }
            $product->description = $product->changeDescriptionReturnToBreakTag($product->description);
            return $product;
        })->sortByDesc('created_at');
        //statuses for filter
        unset($japanese_product_statuses[4]);
        $filter_statuses = $japanese_product_statuses;
        return view('user.items.index', compact('products', 'japanese_product_statuses', 'product_tags', 'filter_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $conditions = Product::CONDITION;
        $product_tags = Tag::productTags()->get();
        $requests = ModelsRequest::unresolvedRequests()->productRequests()->get();
        return view('user.items.create', compact('product_tags', 'requests', 'conditions'));
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
        $images = $request->file('product_images');
        $product_instance->title = $request->title;
        $product_instance->user_id = Auth::id();
        $product_instance->description = $request->description;
        $product_instance->request_id = $request->request_id;
        $product_instance->condition = $request->condition;
        $product_instance->save();
        $product_instance->addProductImages($images, $product_instance->id);
        $product_instance->updateProductTags($request->product_tags, $product_instance->id);
        //slack登録申請者
        $this->slackController->sendNotification(Auth::user()->slackID, "アイテムの登録申請を行いました！");
        //slack管理者
        $this->slackController->sendNotification($this->slackAdminChannelId, "アイテムの登録申請が行われました。管理者画面より確認しましょう。\n```" . env('APP_URL') . "admin/items```");
        return redirect()->route('items.index')->with(['flush.message' => 'アイテム登録申請完了しました。', 'flush.alert_type' => 'success']);
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
        $product->japanese_status = Product::JAPANESE_STATUS[$product->status];
        $product->japanese_condition = Product::CONDITION[$product->condition];
        $product->description = $product->changeDescriptionReturnToBreakTag($product->description);
        if ($product->productLikes->contains('user_id', Auth::id())) {
            $product->isLiked = 1;
        } else {
            $product->isLiked = 0;
        }
        // このproduct_idをもつproduct_deal_logの最後のレコードのuser_idがログインユーザーの場合表示
        $last_product_deal_log = $product->productDealLogs->last();
        $login_user_can_borrow_this_product = $product->status === Product::STATUS['available'] && !$product->productBelongsToLoginUser();
        $login_borrower_can_cancel_or_receive_this_product = $product->status === Product::STATUS['delivering'] && $last_product_deal_log->user_id === Auth::id();
        $login_lender_can_return_this_product = $product->status === Product::STATUS['occupied'] && $product->productBelongsToLoginUser();
        return view('user.items.detail', compact('product', 'login_borrower_can_cancel_or_receive_this_product', 'login_lender_can_return_this_product', 'login_user_can_borrow_this_product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_tags = Tag::productTags()->get()->map(function ($product_tag) {
            $product_tag->is_chosen = false;
            return $product_tag;
        });
        $chosen_product_tags = ProductTag::where('product_id', $id)->get();
        $product = Product::withRelations()->findOrFail($id);
        $product->japanese_product_status = Product::JAPANESE_STATUS[$product->status];
        foreach ($chosen_product_tags as $chosen_product_tag) {
            $product_tags->find($chosen_product_tag->tag_id)->is_chosen = true;
        }
        $requests = ModelsRequest::unresolvedRequests()->productRequests()->get();
        $conditions = Product::CONDITION;
        return view('user.items.edit', compact('product', 'product_tags', 'requests', 'conditions'));
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
        $product_instance = Product::withRelations()->findOrFail($id);
        $new_image_length = is_array($images) ? count($images) : 0;
        $delete_image_length = is_array($request->delete_images) ? count($request->delete_images) : 0;
        $images_count = $product_instance->productImages->count() - $delete_image_length + $new_image_length;

        //既存画像の枚数-削除する画像の枚数+追加する画像の枚数 が１以上３以下
        if ($images_count < 1 || $images_count > 3) {
            // return redirect()->back()->withErrors(['image_count' => '画像は1枚以上3枚以下にしてください。']);
            return redirect()->back()->with(['flush.message' => '画像は1枚以上3枚以下にしてください。', 'flush.alert_type' => 'error']);
        }

        $product_instance->addProductImages($images, $id);
        $product_instance->deleteProductImages($request->delete_images);
        $product_instance->updateProductTags($request->product_tags, $id);
        $product_instance->condition = $request->condition;         //追加
        $product_instance->title = $request->title;
        $product_instance->description = $request->description;
        $product_instance->save();
        return redirect()->route('mypage.items.listed')->with(['flush.message' => 'アイテム情報を更新しました。', 'flush.alert_type' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        //ここはたぶんマイページに遷移
        return redirect('/items');
    }
    public function borrow($item)
    {
        $borrower_user_instance = Auth::user();
        $product_instance = Product::findOrFail($item);
        $lender_user_instance = $product_instance->user;
        // ポイント足りるか確認
        if ($borrower_user_instance->distribution_point < $product_instance->point) {
            return redirect()->back()->withErrors(['not_enough_points' => 'Peer Pointが足りません']);
        }
        // 借りた人のポイント減る
        $borrower_user_instance->changeDistributionPoint(-$product_instance->point);
        // 貸した人のポイント増える
        $lender_user_instance->changeEarnedPoint($product_instance->point);
        // product_deal_log増える
        $product_instance->addProductDealLog($item, $borrower_user_instance->id, $product_instance->point, 0);
        // productのステータス変更
        $product_instance->changeStatusToDelivering();
        //slack借りた人
        $this->slackController->sendNotification($borrower_user_instance->slackID, "<@" . $lender_user_instance->slackID . "> のアイテムを借りました！DMで連絡を取ってアイテムを発送してもらいましょう。商品を受け取ったら、マイページより、商品の受取完了ボタンを押してください。\n```" . env('APP_URL') . "mypage/items/borrowed```");
        //slack貸した人
        $this->slackController->sendNotification($lender_user_instance->slackID, "<@" . $borrower_user_instance->slackID . "> があなたのアイテムを借りました！DMで連絡を取ってアイテムを発送しましょう。");
        //slack管理者
        $this->slackController->sendNotification($this->slackAdminChannelId, "<@" . $borrower_user_instance->slackID . "> が <@" . $lender_user_instance->slackID . "> のアイテムを借りました。");
        // 処理が終わった後redirect back
        return redirect()->route('items.index')->with(['flush.message' => 'レンタルが完了しました。以後、アイテムのオーナーとslackで連絡をお取りください。', 'flush.alert_type' => 'success']);
    }
    public function return($item)
    {
        $product_instance = Product::findOrFail($item);
        $product_deal_log_instance = $product_instance->productDealLogs->last();
        // product_deal_logのreturned_at変更
        $product_deal_log_instance->changeReturnedAtToNow();
        // productのステータス変更
        $product_instance->changeStatusToAvailable();
        //slack借りた人
        $this->slackController->sendNotification($product_deal_log_instance->user->slackID, "<@" . $product_instance->user->slackID . "> が商品の返却を完了しました。");
        //slack貸した人
        $this->slackController->sendNotification($product_instance->user->slackID, "商品の返却を完了しました。");
        // 処理が終わった後redirect back
        return redirect()->back();
    }
    public function cancel($item)
    {
        $product_instance = Product::findOrFail($item);
        //最後のユーザーのproduct_deal_logレコードが今借りてるやつ
        $product_deal_log_instance = $product_instance->productDealLogs->last();
        $lender_user_instance = $product_instance->user;
        // 貸した人のポイント減る
        $lender_user_instance->changeEarnedPoint(-$product_deal_log_instance->point);
        // 借りた人のポイント変動なし
        // productのステータス変更
        $product_instance->changeStatusToAvailable();
        // product_deal_logのcancelled_at変更
        $product_deal_log_instance->changeCancelledAtToNow();
        if (Auth::id() === $lender_user_instance->id) {
            //貸してる人がキャンセルした場合
            //slack借りた人
            $this->slackController->sendNotification($product_deal_log_instance->user->slackID, "<@" . $lender_user_instance->slackID . "> によって、アイテムの貸し出しがキャンセルされました。");
            //slack貸した人
            $this->slackController->sendNotification($lender_user_instance->slackID, "アイテムの貸出をキャンセルしました。");
        } else {
            //借りてる人がキャンセルした場合
            //slack借りた人
            $this->slackController->sendNotification($product_deal_log_instance->user->slackID, "アイテムの貸出をキャンセルしました。");
            //slack貸した人
            $this->slackController->sendNotification($lender_user_instance->slackID, "<@" . $product_deal_log_instance->user->slackID . ">によって、アイテムの貸出がキャンセルされました。");
        }
        // 処理が終わった後redirect back
        return redirect()->back()->with(['flush.message' => 'キャンセルが完了しました。', 'flush.alert_type' => 'success']);
    }
    public function receive($item)
    {
        //productのステータス変更
        $product_instance = Product::findOrFail($item);
        $product_instance->changeStatusToOccupied();
        //slack借りた人
        $this->slackController->sendNotification(Auth::user()->slackID, "商品の受け取りを完了しました。");
        //slack貸した人
        $this->slackController->sendNotification($product_instance->user->slackID, "<@" . Auth::user()->slackID . ">が商品の受取を完了しました。アイテムが返却されたら、以下のリンクより、該当のアイテムの受け取り完了ボタンを押してください。\n```" . env('APP_URL') . "mypage/items/listed```");
        //処理が終わった後redirect back
        return redirect()->back()->with(['flush.message' => '受け取りが完了しました。', 'flush.alert_type' => 'success']);
    }
    public function createWithRequest($chosen_request_id)
    {
        $conditions = Product::CONDITION;
        //アイテムタグ一覧を取得
        $tags = Tag::productTags()->get();
        //未完了のリクエストを取得
        $requests = ModelsRequest::unresolvedRequests()->productRequests()->get();
        //リクエストのタグを取得して、チェック済みにする
        $request_tags = ModelsRequest::findOrFail($chosen_request_id)->requestTags->map(function ($request_tag) use ($tags) {
            $tags->find($request_tag->tag_id)->setAttribute('checked', true);
        });
        return view('user.items.create', compact('chosen_request_id', 'tags', 'requests', 'conditions'));
    }
    public function like($id)
    {
        ProductLike::where('product_id', $id)->where('user_id', Auth::id())->delete();
        $product_like_instance = new ProductLike();
        $product_like_instance->product_id = $id;
        $product_like_instance->user_id = Auth::id();
        $product_like_instance->save();
        return response()->json(['message' => 'liked', 'product' => ProductLike::where('product_id', $id)->where('user_id', Auth::id())->get()]);
    }
    public function unlike($id)
    {
        ProductLike::where('product_id', $id)->where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'unliked', 'product' => ProductLike::where('product_id', $id)->where('user_id', Auth::id())->get()]);
    }
}

<x-user-app>
    <x-slot name="header_slot">
        <x-user-header textColor="text-peer-request" bgColor="bg-peer-request">
            <x-slot:app_name>Peer Request</x-slot:app_name>
            <x-slot:button_text>リクエスト登録</x-slot:button_text>
            <x-slot:button_link>{{ route('requests.create') }}</x-slot:button_link>
            <x-slot:earned_point>{{Auth::user()->earned_point}}</x-slot:earned_point>
            <x-slot:distribution_point>{{Auth::user()->distribution_point}}</x-slot:distribution_point>
            <x-slot:top_title_link>{{ route('requests.index') }}</x-slot:top_title_link>
        </x-user-header>
    </x-slot>
    <x-slot name="body_slot">
        <x-user-side-navi>
            <div class="mx-auto max-w-5xl">
                <x-user-search-box bgColor="bg-peer-request">
                    <x-user-search-request bgColor="bg-peer-request">
                        <x-slot name="default_request_type_id">{{ $product_request_type_id }}</x-slot>
                        <x-slot name="filter_by_radio">
                            <x-user-search-radio>
                                <x-slot name="radio_name">リクエストタイプ</x-slot>
                                <x-slot name="radios">
                                    @foreach($app as $request_type_id=>$request_type)
                                    <!-- peer product shareとpeer eventを表示することもできる -->
                                    <div class="flex items-center mb-4" @click="activeTab = {{ $request_type_id }}">
                                        <input id="box-{{ $request_type_id }}" type="radio" value="{{ $request_type_id }}" name="request_type" class="w-4 h-4 bg-gray-100 border-gray-300 filter-input" @if($request_type_id==$product_request_type_id) checked @endif>
                                        <label for="box-{{ $request_type_id }}" class="ml-2 text-sm font-medium text-gray-900">{{ $request_type['japanese_name']  }}</label>
                                    </div>
                                    @endforeach
                                </x-slot>
                            </x-user-search-radio>
                        </x-slot>
                        <x-slot name="filter_by_tags">
                            <div class="sm:w-3/4 sm:pl-8 sm:py-4 sm:mt-0 text-center sm:text-left">
                                <div class="flex flex-col items-center text-center justify-center">
                                    <h2 class="font-medium title-font text-gray-900 text-lg">カテゴリ</h2>
                                    <div class="w-full h-1 bg-gray-500 rounded mt-2 mb-4"></div>
                                    <div class="flex flex-wrap w-full text-sm font-medium text-gray-900 bg-white sm:flex">
                                        <div class="w-full flex flex-wrap max-w-lg text-sm font-medium text-gray-900 bg-white" x-show="activeTab === {{ $product_request_type_id }}">
                                            @foreach ($product_tags as $index => $tag)
                                            <div class="min-w-max m-1 border rounded border-gray-200">
                                                <div class="flex items-center px-3">
                                                    <input value="{{ $tag->id }}" type="checkbox" id="product_tag_{{ $index }}" name="tag_type_{{ $product_request_type_id  }}" class="w-4 h-4 bg-gray-100 border-gray-300 rounded filter-input">
                                                    <label for="product_tag_{{ $index }}" class="w-auto py-3 pl-1 text-sm font-medium text-gray-900">{{ $tag->name }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div x-cloak class="w-full flex flex-wrap max-w-lg text-sm font-medium text-gray-900 bg-white" x-show="activeTab === {{ $event_request_type_id }}">
                                            @foreach ($event_tags as $index => $tag)
                                            <div class="min-w-max m-1 border rounded border-gray-200">
                                                <div class="flex items-center px-3">
                                                    <input value="{{ $tag->id }}" type="checkbox" id="event_tag_{{ $index  }}" name="tag_type_{{ $event_request_type_id }}" class="w-4 h-4 bg-gray-100 border-gray-300 rounded filter-input">
                                                    <label for="event_tag_{{ $index }}" class="w-auto py-3 pl-1 text-sm font-medium text-gray-900">{{ $tag->name }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </x-slot>
                    </x-user-search-request>
                </x-user-search-box>

                <div class="mx-auto max-w-5xl my-4">
                    <div class="mx-auto grid grid-cols-2 items-stretch gap-4">
                        @foreach ($requests as $request)
                        <div class="col-span-1 filter-target" data-tag="{{ $request->data_tag }}">
                            <div class="h-full rounded-lg border border-gray-200 bg-white shadow-sm">
                                <div class="flex flex-col justify-between rounded-lg h-full text-xs shadow-md p-4 pb-1 text-gray-500 bg-white">
                                    <div>
                                        <!-- イベント名 -->
                                        <div class="w-full text-xl text-gray-800 mb-4">
                                            <p class="font-bold pl-2 border-l-4 border-peer-request">{{$request->title}}</p>
                                        </div>
                                        <!-- 概要 -->
                                        <div class="w-full mb-2 text-sm text-gray-800">
                                            <p class="text-gray-500 break-words">
                                                {!! $request->description !!}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="bottom-0">
                                        <!--ユーザー ・ タグ -->
                                        <div class="flex justify-between">
                                            <div class="flex flex-wrap gap-1 items-center">
                                                @foreach($request->requestTags as $tag)
                                                <span class="items-center gap-1 rounded-full border border-gray-300 bg-gray-50 px-2 py-1 text-xs font-semibold text-gray-400">
                                                    {{ $tag->tag->name }}
                                                </span>
                                                @endforeach
                                            </div>
                                            <a href="#" class="divide-y divide-gray-200 flex justify-end">
                                                <div class="flex justify-end space-x-4 p-1 rounded-md hover:bg-gray-50">
                                                    <div class="pl-1">
                                                        <img class="w-8 h-8 rounded-full" src="{{ $request->user->icon }}" alt="icon">
                                                    </div>
                                                    <div class="min-w-0 flex items-center">
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $request->user->name }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <!-- ボタン・モーダル -->
                                        <div x-data="{ modelOpen: false }">
                                            <div @click="modelOpen =!modelOpen" class="flex items-center justify-center px-3">
                                                <x-user-register-button textColor="text-peer-request" bgColor="bg-white" borderColor="border-peer-request">
                                                    <x-slot name="button">リクエストに応える</x-slot>
                                                </x-user-register-button>
                                            </div>

                                            <div x-cloak x-show="modelOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                                                    <div x-cloak @click="modelOpen = false" x-show="modelOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"></div>

                                                    <div x-cloak x-show="modelOpen" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-xl p-8 my-40 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl">
                                                        <div class="flex items-center justify-between space-x-4">
                                                            <h1 class="text-xl font-semibold text-gray-800 pl-2 border-l-4 border-peer-request">
                                                                リクエストに応える</h1>
                                                            <button @click="modelOpen = false" class="text-gray-600 focus:outline-none hover:text-gray-700">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="mt-5">
                                                            <p class="block text-sm mb-4 text-gray-700">次のアプリに移動します</p>
                                                            <div class="flex justify-center items-center">
                                                                <div class="flex items-center {{ $app[$request->type_id]['color'] }}">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="stroke-current">
                                                                        <path d="M3 12C3 13.1819 3.23279 14.3522 3.68508 15.4442C4.13738 16.5361 4.80031 17.5282 5.63604 18.364C6.47177 19.1997 7.46392 19.8626 8.55585 20.3149C9.64778 20.7672 10.8181 21 12 21C13.1819 21 14.3522 20.7672 15.4442 20.3149C16.5361 19.8626 17.5282 19.1997 18.364 18.364C19.1997 17.5282 19.8626 16.5361 20.3149 15.4442C20.7672 14.3522 21 13.1819 21 12C21 10.8181 20.7672 9.64778 20.3149 8.55585C19.8626 7.46392 19.1997 6.47177 18.364 5.63604C17.5282 4.80031 16.5361 4.13738 15.4442 3.68508C14.3522 3.23279 13.1819 3 12 3C10.8181 3 9.64778 3.23279 8.55585 3.68508C7.46392 4.13738 6.47177 4.80031 5.63604 5.63604C4.80031 6.47177 4.13738 7.46392 3.68508 8.55585C3.23279 9.64778 3 10.8181 3 12Z" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                        <path d="M10 16V8H12.5C13.163 8 13.7989 8.26339 14.2678 8.73223C14.7366 9.20107 15 9.83696 15 10.5C15 11.163 14.7366 11.7989 14.2678 12.2678C13.7989 12.7366 13.163 13 12.5 13H10" stroke="" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                    </svg>
                                                                    <span class="ml-3 font-medium text-xl font-patua">
                                                                        {{ $app[$request->type_id]['name'] }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="mt-6">
                                                                <a @if($request->type_id===$product_request_type_id)
                                                                    href="{{ route('items.create-with-request',$request->id) }}"
                                                                    @else
                                                                    href="{{ route('events.create-with-request',$request->id) }}"
                                                                    @endif>
                                                                    <x-user-register-button textColor="text-white" bgColor="bg-peer-request" borderColor="border-peer-request">
                                                                        <x-slot name="button">
                                                                            次へ
                                                                        </x-slot>
                                                                    </x-user-register-button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 日付・いいね -->
                                    <div class="flex items-end justify-between mb-1">
                                        <p>{{$request->created_at->format('Y.m.d')}}</p>
                                        <div class="likes" data-request_id="{{ $request->id }}" data-is_liked="{{ $request->isLiked }}">
                                            <div class="flex relative">
                                                <button class="text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="@if($request->isLiked) red @else none @endif" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                    </svg>
                                                </button>
                                                <div class="mt-3">
                                                    <p class="text-xs like-count">{{ $request->request_likes_count }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-user-side-navi>
    </x-slot>
</x-user-app>
<script>
    // Get all filter inputs
    let filterInputs = document.querySelectorAll('.filter-input');

    // Add event listener to each filter button
    filterInputs.forEach(input => {
        input.addEventListener('click', () => {
            //変数が空なら全て表示、変数が空じゃないなら変数とリクエストのタグの共通項があるものだけ表示
            //name:request_typeのcheckedの値を取得
            let checkedRequestTypeId = document.querySelector('input[name=request_type]:checked').value;
            // 選択したタグを配列に入れる
            let checkedTags = Array.from(document.querySelectorAll('input[name=tag_type_' + checkedRequestTypeId + ']:checked'));
            let checkedTagsValues = checkedTags.map(e => parseInt(e.value));
            // 絞り込み対象全て取得
            let filterTargets = document.querySelectorAll('.filter-target');

            // Show/hide targets based on the integer variable checkedStatus and array variable checkedTags
            filterTargets.forEach(filterTarget => {
                //ターゲットタグを配列に変換
                let targetTags = JSON.parse(filterTarget.dataset.tag);
                //ターゲットタグが空か判定
                let targetTagsEmpty = targetTags.length === 0;
                //インプットタグとターゲットタグの共通項を取得
                let commonTags = checkedTagsValues.filter(value => targetTags.includes(value));
                let filterByTags;
                //インプットタグが空か判定
                let tagsNotChosen = checkedTagsValues.length === 0;
                //インプットタグとターゲットタグの共通項が空か判定
                let commonTagsEmpty = commonTags.length === 0;

                //共通タグ、選択したタグ、ターゲットタグをコンソールに表示＝＞デバッグ用
                console.log(commonTags, checkedTagsValues, targetTags);

                //ターゲットタグが空かつインプットタグが空ではない場合タグによる絞り込みは偽判定
                if (targetTagsEmpty && !tagsNotChosen) {
                    filterByTags = false;
                }
                //インプットタグが空の場合タグによる絞り込みは真判定
                else if (tagsNotChosen) {
                    filterByTags = true;
                }
                //インプットタグとターゲットタグの共通項がある場合タグによる絞り込みは偽判定
                else if (commonTagsEmpty) {
                    filterByTags = false;
                }
                //インプットタグとターゲットタグの共通項がない場合タグによる絞り込みは真判定
                else if (!commonTagsEmpty) {
                    filterByTags = true;
                }
                if (filterByTags) {
                    filterTarget.style.display = 'block';
                } else {
                    filterTarget.style.display = 'none';
                }
            });
        });
    });
    //get all elements with class likes
    let likes = document.querySelectorAll('.likes');
    //foreach likes, if clicked, change color and send ajax request to route('requests.like') or route('requests.unlike')
    likes.forEach(like => {
        like.addEventListener('click', () => {
            //get isLiked data from element
            let isLiked = like.dataset.is_liked;
            //get request id from element
            let requestId = like.dataset.request_id;
            //get like count element
            let likeCount = like.querySelector('.like-count');
            //if isLiked is true, send unlike request
            if (isLiked === '1') {
                axios.post('/requests/' + requestId + '/unlike')
                    .then(function(response) {
                        //change isLiked data to false
                        like.setAttribute('data-is_liked', 0);
                        //change svg color to gray
                        like.querySelector('svg').style.fill = 'none';
                        //decrease like count
                        likeCount.innerHTML = parseInt(likeCount.innerHTML) - 1;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
            //if isLiked is false, send like request
            else {
                axios.post('/requests/' + requestId + '/like')
                    .then(function(response) {
                        //change isLiked data to true
                        like.setAttribute('data-is_liked', 1);
                        //change svg color to red
                        like.querySelector('svg').style.fill = 'red';
                        //increase like count
                        likeCount.innerHTML = parseInt(likeCount.innerHTML) + 1;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        });
    });
</script>

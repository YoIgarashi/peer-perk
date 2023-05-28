<x-user-app>
    <x-slot name="header_slot">
        <x-user-header textColor="text-peer-request" bgColor="bg-peer-request">
            <x-slot:app_name>Peer Request</x-slot:app_name>
            <x-slot:button_text>リクエスト登録</x-slot:button_text>
            <x-slot:button_link>{{ route('requests.create') }}</x-slot:button_link>
            <x-slot:earned_point>{{ Auth::user()->earned_point }}</x-slot:earned_point>
            <x-slot:distribution_point>{{ Auth::user()->distribution_point }}</x-slot:distribution_point>
            <x-slot:top_title_link>{{ route('requests.index') }}</x-slot:top_title_link>
        </x-user-header>
    </x-slot>
    <x-slot name="body_slot">
        <x-user-side-navi>
            <div class="w-full mx-auto">
                <x-user-form method="POST" action="{{ route('requests.store') }}">
                    <x-slot name="title">リクエストの登録</x-slot>
                    <section class="text-left w-full flex gap-8">
                        <section class="my-6 w-1/2">
                            <h3 class="mb-2 text-xl text-gray-600 font-extrabold border-b border-gray-500">タイトルと概要</h3>
                            <h4 class="mb-1 mt-4 block text-sm font-medium text-gray-700">タイトル<span class="text-red-600">*</span></h4>
                            <div class="mx-auto">
                                <input name="title" type="text" class="p-1 block w-full rounded-md border border-gray-300" required />
                            </div>
                            <h4 class="mb-1 mt-4 block text-sm font-medium text-gray-700">リクエストの概要<span class="text-red-600">*</span></h4>
                            <div class="mx-auto">
                                <textarea name="description" class="p-1 block w-full rounded-md border border-gray-300" rows="3" required></textarea>
                            </div>
                        </section>
                        <section class="my-6 w-1/2">
                            <h3 class="mb-2 text-xl text-gray-600 font-extrabold border-b border-gray-500">リクエストの詳細</h3>
                            <h4 class="mb-1 mt-4 block text-sm font-medium text-gray-700">区分<span class="text-red-600">*</span></h4>
                            <div class="tab-wrapper" x-data="{ activeTab:  0 }">
                                <div class="text-base">
                                    <div class="mx-1 grid sm:grid-cols-2 gap-2">
                                        <label for="product" class="flex p-3 w-full bg-white border border-gray-300 rounded-md text-sm" @click="activeTab = 0">
                                            <input type="radio" name="type_id" value="{{ $product_request_type_id }}" class="shrink-0 mt-0.5 border-gray-200 rounded-full" id="product" checked>
                                            <span class="text-sm ml-3">アイテム</span>
                                        </label>
                                        <label for="event" class="flex p-3 w-full bg-white border border-gray-300 rounded-md text-sm" @click="activeTab = 1">
                                            <input type="radio" name="type_id" value="{{ $event_request_type_id }}" class="shrink-0 mt-0.5 border-gray-200 rounded-full" id="event">
                                            <span class="text-sm ml-3">イベント</span>
                                        </label>
                                    </div>
                                </div>

                                <h4 class=" mb-1 mt-4 block text-sm font-medium text-gray-700">カテゴリ</h4>
                                <div class="w-full flex flex-wrap gap-2 max-w-lg text-sm font-medium text-gray-900 bg-white" x-show="activeTab === 0">
                                    @foreach ($product_tags as $index => $tag)
                                    <div class="w-auto">
                                        <div class="flex items-center">
                                            <input hidden name="product_tags[]" id="product_tag_{{ $tag->id }}" type="checkbox" value="{{ $tag->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                            <label for="product_tag_{{ $tag->id }}" class="rounded-full border border-gray-300 bg-gray-50 px-2 py-1 text-xs font-semibold text-gray-400 cursor-pointer">{{ $tag->name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div x-cloak class="w-full flex flex-wrap gap-2 max-w-lg text-sm font-medium text-gray-900 bg-white" x-show="activeTab === 1">
                                    @foreach ($event_tags as $index => $tag)
                                    <div class="w-auto">
                                        <div class="flex items-center">
                                            <input hidden name="event_tags[]" id="event_tag_{{ $tag->id }}" type="checkbox" value="{{ $tag->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                            <label for="event_tag_{{ $tag->id }}" class="rounded-full border border-gray-300 bg-gray-50 px-2 py-1 text-xs font-semibold text-gray-400 cursor-pointer">{{ $tag->name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    </section>
                    <x-user-register-button textColor="text-white" bgColor="bg-peer-request" borderColor="border-peer-request">
                        <x-slot name="button">
                            登録する
                        </x-slot>
                    </x-user-register-button>
                </x-user-form>
            </div>
        </x-user-side-navi>
    </x-slot>
</x-user-app>
<script>
    //絞り込みタグの色
    const productCheckboxes = document.querySelectorAll('input[name="product_tags[]"]');
    const eventCheckboxes = document.querySelectorAll('input[name="event_tags[]"]');

    productCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            const checkboxId = checkbox.id;
            const targetLabel = document.querySelector(`label[for="${checkboxId}"]`);

            if (checkbox.checked) {
                targetLabel.classList.add('bg-gray-500');
            } else {
                targetLabel.classList.remove('bg-gray-500');
            }
        });
    });
    eventCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            const checkboxId = checkbox.id;
            const targetLabel = document.querySelector(`label[for="${checkboxId}"]`);

            if (checkbox.checked) {
                targetLabel.classList.add('bg-gray-500');
            } else {
                targetLabel.classList.remove('bg-gray-500');
            }
        });
    });
</script>

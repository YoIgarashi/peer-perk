<x-user-app>
    <x-slot name="header_slot">
        <x-user-header textColor="text-pink-600" bgColor="bg-pink-600">
            <x-slot:app_name>Peer Event</x-slot:app_name>
            <x-slot:button_text>イベント登録</x-slot:button_text>
            <x-slot:button_link>{{ route('events.create') }}</x-slot:button_link>
            <x-slot:earned_point>{{ Auth::user()->earned_point }}</x-slot:earned_point>
            <x-slot:distribution_point>{{ Auth::user()->distribution_point }}</x-slot:distribution_point>
            <x-slot:top_title_link>{{ route('events.index') }}</x-slot:top_title_link>
        </x-user-header>
    </x-slot>
    <x-slot name="body_slot">
        <x-user-side-navi>
            <div class="w-full mx-auto">
                <x-user-form method="POST" action="{{ route('events.store') }}">
                    <x-slot name="title">イベントの開催</x-slot>
                    <section class="text-left w-full flex gap-8">
                        <section class="my-6 w-1/2">
                            <h3 class="mb-2 text-xl text-gray-600 font-extrabold border-b border-gray-500">イベント名と概要</h3>
                            <h4 class="mb-1 mt-4 block text-sm font-medium text-gray-700">イベント名<span class="text-red-600">*</span></h4>
                            <div class="mx-auto">
                                <input name="title" type="text" class="p-1 block w-full rounded-md border border-gray-300" required />
                            </div>
                            <h4 class="mb-1 mt-4 block text-sm font-medium text-gray-700">イベントの概要<span class="text-red-600">*</span></h4>
                            <div class="mx-auto">
                                <textarea name="description" class="p-1 block w-full rounded-md border border-gray-300" rows="3" required></textarea>
                            </div>
                            <h4 class="mb-1 mt-4 block text-sm font-medium text-gray-700">関連するリクエスト</h4>
                            <div class="mb-4 border border-gray-300 rounded-md">
                                <select name="request_id" id="example1" class="p-1 block w-full rounded-md border-gray-300 shadow-sm text-base text-gray-500">
                                    <option value="">なし</option>
                                    @foreach($requests as $request)
                                    @if(isset($chosen_request_id)&&$chosen_request_id==$request->id)
                                    <option value="{{ $request->id }}" selected>{{ $request->title }}</option>
                                    @else
                                    <option value="{{ $request->id }}">{{ $request->title }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </section>
                        <section class="my-6 w-1/2">
                            <h3 class="mb-2 text-xl text-gray-600 font-extrabold border-b border-gray-500">イベントの詳細</h3>
                            <h4 class="mb-1 mt-4 block text-sm font-medium text-gray-700">カテゴリ</h4>
                            <div class="w-full flex flex-wrap gap-2 max-w-lg text-sm font-medium text-gray-900 bg-white">
                                @foreach ($tags as $index => $tag)
                                <div class="w-auto">
                                    <div class="flex items-center">
                                        <input hidden name="tags[]" id="tag_{{ $index }}" type="checkbox" value="{{ $tag->id }}" class="filter-input w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                                        <label for="tag_{{ $index }}" class="rounded-full border border-gray-300 bg-gray-50 px-2 py-1 text-xs font-semibold text-gray-400 cursor-pointer">{{ $tag->name }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <h4 class="mb-1 mt-4 block text-sm font-medium text-gray-700">開催形態<span class="text-red-600">*</span></h4>
                            <div class="mb-4 border border-gray-300 rounded-md">
                                <select name="location" id="example1" class="p-1 block w-full rounded-md border-gray-300 shadow-sm text-base text-gray-500" required>
                                    @foreach($locations as $location)
                                    <option value="{{ $location }}">{{ $location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <h4 class="mb-1 mt-4 block text-sm font-medium text-gray-700">開催予定日</h4>
                            <div date-rangepicker class="flex items-center justify-between">
                                <div class="relative">
                                    <input id="start" name="start_date" type="datetime-local" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2.5" placeholder="開始日">
                                </div>
                                <span class="mx-4 text-gray-500">〜</span>
                                <div class="relative">
                                    <input id="end" name="end_date" type="datetime-local" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2.5" placeholder="終了日">
                                </div>
                            </div>
                        </section>
                    </section>
                    <x-user-register-button textColor="text-white" bgColor="bg-pink-600" borderColor="border-pink-600">
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
    //タグの色
    const checkboxes = document.querySelectorAll('input[name="tags[]"]');

    checkboxes.forEach((checkbox) => {
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

    var startInput = document.getElementById("start");
    var endInput = document.getElementById("end");

    startInput.addEventListener("input", validateDateTime);
    endInput.addEventListener("input", validateDateTime);

    function validateDateTime() {
        var startValue = new Date(startInput.value);
        var endValue = new Date(endInput.value);

        var today = new Date();
        if (startValue < today || endValue < today) {
            alert("開始日時と終了日時は現在時刻より後の日時を設定してください。");
            // 値をクリアする
            if(startValue < today){
                startInput.value = "";
            }else if(endValue < today){
                endInput.value = "";
            }
        }
        if (startValue > endValue) {
            alert("終了日時は開始日時より後の日時を設定してください。");
            // 値をクリアする
            endInput.value = "";
        }
    }
</script>

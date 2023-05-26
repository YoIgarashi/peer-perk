<x-mypage-app>
    <x-slot:border_color>border-pink-600</x-slot:border_color>
    <x-slot:title>いいねしたイベント一覧</x-slot:title>
    <x-slot:earned_point>580</x-slot:earned_point>
    <x-slot:distribution_point>5000</x-slot:distribution_point>

    <div class="bg-white md:p-6 w-full">
        <div>
            <ul class="border-b border-gray-300">
                @foreach ($liked_events as $event)
                <?php
                $array_participants = [];
                foreach ($event->eventParticipants as $participant) {
                    array_push($array_participants, $participant->user_id);
                }
                ?>
                <li>
                    <x-mypage-event-list>
                        <x-slot:title>{{ $event -> title }}</x-slot:title>
                        <x-slot:description>{{ $event -> description }}</x-slot:description>
                        <x-slot:tag>
                            @foreach ($event->eventTags as $tag)
                            <x-user-tag>{{ $tag->tag->name }}</x-user-tag>
                            @endforeach
                        </x-slot:tag>
                        <x-slot:date>{{ $event  -> date ? date( 'Y.m.d', strtotime( $event  -> date ) ) : '未定' }}</x-slot:date>
                        <x-slot:style>{{ $event -> style ?? '未定' }}</x-slot:style>
                        <x-slot:participants_count>{{ count($event -> eventParticipants) }}</x-slot:participants_count>
                        <x-slot:create_date>{{ date( 'Y.m.d', strtotime( $event  -> created_at ) ) }}</x-slot:create_date>
                        <x-slot:likes>{{ count($event -> eventLikes) }}</x-slot:likes>
                        <x-slot:user_icon>{{ $event  -> user -> icon }}</x-slot:user_icon>
                        <x-slot:user_name>{{ $event  -> user -> name }}</x-slot:user_name>
                        @if ($event -> completed_at !== null)
                        <x-slot:status>開催済み</x-slot:status>
                        <x-slot:button></x-slot:button>
                        @elseif ($event -> cancelled_at !== null)
                        <x-slot:status>中止</x-slot:status>
                        <x-slot:button></x-slot:button>
                        <!-- 自身が参加予定のイベントの場合 -->
                        @elseif (in_array ($user -> id, $array_participants))
                        <x-slot:status></x-slot:status>
                        <x-slot:timestamp></x-slot:timestamp>
                        <x-slot:button>
                            <x-mypage-button-event-cancel action="{{ route('events.cancel', ['event' =>  $event -> id]) }}">
                                <x-slot:content>予約をキャンセルする</x-slot:content>
                                <x-slot:logo_path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </x-slot:logo_path>
                                <x-slot:modal_title>イベントへの参加予定をキャンセル</x-slot:modal_title>
                                <x-slot:modal_description>参加をキャンセルすると、主催者にポイントは入りません。ただし、自分自身にポイントは戻ってこないため、ご注意ください。</x-slot:modal_description>
                                <x-slot:method></x-slot:method>
                                <x-slot:form_slot></x-slot:form_slot>
                            </x-mypage-button-event-cancel>
                        </x-slot:button>
                        <!-- 開催前のイベントであった場合 -->
                        @else
                        <x-slot:status></x-slot:status>
                        <x-slot:timestamp></x-slot:timestamp>
                        <x-slot:button>
                            <x-mypage-button-event-held action="{{ route('events.participate', ['event' =>  $event -> id]) }}">
                                <x-slot:content>予約する</x-slot:content>
                                <x-slot:logo_path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                </x-slot:logo_path>
                                <x-slot:modal_title>参加予約</x-slot:modal_title>
                                <x-slot:modal_description>主催者に支払うポイントを設定してください。一度支払ったポイントは戻ってこないのでご注意ください。</x-slot:modal_description>
                                <x-slot:method></x-slot:method>
                                <x-slot:form_slot>
                                    <div class="mb-4">
                                        <div class="relative flex gap-4">
                                            <label for="point" class="leading-7 text-sm text-gray-600 flex-center">Point:</label>
                                            <input type="number" id="point" name="point" class="bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                        </div>
                                        <p class="ml-2 text-xs text-gray-500 ">
                                            ポイントの上限は 500 pt
                                        </p>
                                    </div>
                                </x-slot:form_slot>
                            </x-mypage-button-event-held>
                        </x-slot:button>
                        @endif
                    </x-mypage-event-list>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-mypage-app>

<x-user-app>
    <x-slot name="header_slot">
        <x-user-header textColor="text-peer-perk" bgColor="bg-peer-perk">
            <x-slot:app_name>Peer Perk</x-slot:app_name>
            <x-slot:earned_point>{{ $earned_point }}</x-slot:earned_point>
            <x-slot:distribution_point>{{ $distribution_point }}</x-slot:distribution_point>
        </x-user-header>
    </x-slot>
    <x-slot name="body_slot">
        <x-user-side-navi>
            <div class="container flex mx-auto gap-4">
                <div class="bg-white shadow rounded-lg md:p-6 w-1/4">
                    <nav>
                        <ul class="mb-3 border-b border-gray-300">
                            <li class="border-slate-100 text-peer-perk rounded-t-1 group relative flex w-full items-center border-b border-solid pb-3 text-left font-semibold text-dark-500 transition-all ease-in">
                                <span>Peer Perk</span>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('プロフィール') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('アカウント') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('ポイント移行履歴') }}
                                </x-nav-link>
                            </li>
                        </ul>
                        <ul class="mb-3 border-b border-gray-300">
                            <li class="border-slate-100 text-blue-400 rounded-t-1 group relative flex w-full items-center border-b border-solid pb-3 text-left font-semibold text-dark-500 transition-all ease-in">
                                <span>Peer Product Share</span>
                            </li>
                            <li>
                                <x-nav-link :href="route('mypage.items.listed')" :active="request()->routeIs('mypage.items.listed')">
                                    {{ __('出品したアイテム') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('貸出中アイテム') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('貸し借り履歴') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('いいねしたアイテム') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('アイテム取引履歴') }}
                                </x-nav-link>
                            </li>
                        </ul>
                        <ul class="mb-3 border-b border-gray-300">
                            <li class="border-slate-100 text-pink-400 rounded-t-1 group relative flex w-full items-center border-b border-solid pb-3 text-left font-semibold text-dark-500 transition-all ease-in">
                                <span>Peer Event</span>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('主催したイベント') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('参加したイベント') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('いいねしたイベント') }}
                                </x-nav-link>
                            </li>
                        </ul>
                        <ul class="mb-3 border-b border-gray-300">
                            <li class="border-slate-100 text-peer-request rounded-t-1 group relative flex w-full items-center border-b border-solid pb-3 text-left font-semibold text-dark-500 transition-all ease-in">
                                <span>Peer Request</span>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('投稿したリクエスト') }}
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                    {{ __('いいねしたリクエスト') }}
                                </x-nav-link>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="bg-white shadow rounded-lg md:p-6 w-3/4">
                    <x-mypage-title>{{ $title }}</x-mypage-title>
                    {{ $slot }}
                </div>
            </div>
        </x-user-side-navi>
    </x-slot>
</x-user-app>

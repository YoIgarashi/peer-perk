<x-user-app>
    <x-slot name="header_slot">
        <x-header-top>
            5000
        </x-header-top>
        <x-header-bottom>
        </x-header-bottom>
    </x-slot>
    <x-slot name="body_slot">
        <div class="bg-white pb-8">
            <nav class="flex mx-auto max-w-screen-xl px-4 md:px-8" aria-label="Breadcrumb">
                <ol class="my-4 inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-lg text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            一覧
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-lg text-gray-500 md:ml-2 dark:text-gray-400">MacBook Air</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mx-auto max-w-screen-xl px-4 md:px-8">
                <div class="grid gap-8 md:grid-cols-2">
                    <!-- images - start -->
                    <div class="grid gap-4 lg:grid-cols-5">
                        <div class="order-last flex gap-4 lg:order-none lg:flex-col">
                            <div class="overflow-hidden rounded-lg bg-gray-100">
                                <img src="https://pixabay.com/get/g99d6b4842ba7137ee41f04054bba656fac302e366af1162bca527744c5492ffb9087ad3414f357dcdf8c1bc8c5f9d28d3629d201f399006e087b705c2d7f62a9_640.jpg" loading="lazy" alt="Photo by Himanshu Dewangan" class="h-full w-full object-cover object-center" />
                            </div>
                            <div class="overflow-hidden rounded-lg bg-gray-100">
                                <img src="https://pixabay.com/get/g638ac3a46d08a1c39fa95ead511083f16a8bee3f32a63cac2eff61f760fb403b11fc091f3cf1bd4c46f5921d00931d5d148f29ecb6033a6669f1ae7e63f1d4e9_640.jpg" loading="lazy" alt="Photo by Himanshu Dewangan" class="h-full w-full object-cover object-center" />
                            </div>
                            <div class="overflow-hidden rounded-lg bg-gray-100">
                                <img src="https://pixabay.com/get/g0c5b58e4266a666a6e131f6bbbe74def1c9113c2256cb866b6393fe6f81edc338e13b7b15dfc1b1e08c5d2068d4f3a4e7f913a6887b0d7de93f9f5d46a6afe30_640.jpg" loading="lazy" alt="Photo by Himanshu Dewangan" class="h-full w-full object-cover object-center" />
                            </div>
                        </div>
                        <div class="relative overflow-hidden rounded-lg bg-gray-100 lg:col-span-4">
                            <img src="https://pixabay.com/get/g0c5b58e4266a666a6e131f6bbbe74def1c9113c2256cb866b6393fe6f81edc338e13b7b15dfc1b1e08c5d2068d4f3a4e7f913a6887b0d7de93f9f5d46a6afe30_640.jpg" loading="lazy" alt="Photo by Himanshu Dewangan" class="h-full w-full object-cover object-center" />
                            <span class="absolute left-0 top-0 rounded-br-lg bg-red-500 px-3 py-1.5 text-sm uppercase tracking-wider text-white">sale</span>
                        </div>
                    </div>
                    <!-- images - end -->

                    <!-- content - start -->
                    <div class="md:py-8">
                        <h1 class="text-black text-3xl title-font font-medium mb-1">MacBook Air</h1>
                        <div class="px-2 flex mt-4 justify-between">
                            <p class="title-font font-medium text-2xl text-gray-500">600 pt</p>
                            <span class="flex items-center">
                                <button type="">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                    </svg>
                                </button>
                                <span class="text-gray-600 ml-3">4 likes</span>
                            </span>
                        </div>
                        <x-user-register-button>
                            <x-slot name="button">レンタルする</x-slot>
                        </x-user-register-button>

                        <h3 class="text-xl text-black mb-1 font-extrabold dark:text-white border-b">アイテムの説明</h3>
                        <p class="mb-4 text-lg text-gray-500">
                            3年前に買ったmacbookairですが、ほとんど使用せず眠っていました。<br>
                            Intel Core i5 1.6GHz<br>
                            8GB<br>
                        </p>
                        <h3 class="text-xl text-black mb-1 font-extrabold dark:text-white border-b">アイテムの状態</h3>
                        <p class="mb-4 text-lg text-gray-500">傷なし</p>
                        <h3 class="text-xl text-black mb-1 font-extrabold dark:text-white border-b">タグ</h3>
                        <p class="mb-4 text-lg text-gray-500">PC デバイス 利用不可</p>
                        <h3 class="text-xl text-black mb-1 font-extrabold dark:text-white border-b">出品者</h3>

                        <a href="#" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="pb-3 sm:pb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="w-8 h-8 rounded-full" src="https://images.unsplash.com/photo-1552058544-f2b08422138a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxNTgwfDB8MXxzZWFyY2h8NXx8cGVyc29ufGVufDB8fHx8MTY4MzAzMzA2OA&ixlib=rb-4.0.3&q=80&w=400" alt="Neil image">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            五十嵐　佳貴
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            email@flowbite.com
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- content - end -->
                </div>
            </div>
        </div>
    </x-slot>
</x-user-app>

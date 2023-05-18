<div x-data="{ showModal: false }" x-on:keydown.window.escape="showModal = false">
    <div class="flex justify-center">
        <a @click="showModal = true">
            <button class="middle none center mr-3 rounded-lg border border-blue-700 py-3 px-6 font-sans text-xs font-bold uppercase text-blue-700 transition-all hover:opacity-75 focus:ring focus:ring-blue-200 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none" data-ripple-dark="true">
                {{ $content }}
            </button>
        </a>
    </div>
    <div x-cloak x-show="showModal" x-transition.opacity class="fixed inset-0 z-10 bg-gray-700/50"></div>
    <div x-cloak x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
        <div class="mx-auto overflow-hidden rounded-lg bg-white shadow-xl sm:w-full sm:max-w-xl">
            <div class="relative p-6">
                <div class="flex gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100 text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-secondary-900">{{ $modal_title }}</h3>
                        <div class="mt-2 text-sm text-secondary-500">{{ $modal_description }}</div>
                    </div>
                </div>
                <div class="mt-6 flex flex-col gap-3">
                    <form {{ $attributes }} method="post">
                        @csrf
                        {{ $method }}
                        {{ $form_slot }}
                        <!-- <slot name="form_slot"></slot> -->
                        <button type="submit" class="w-full rounded-lg border border-blue-500 bg-blue-500 px-4 py-2 text-center text-sm font-medium text-white shadow-sm transition-all hover:border-blue-700 hover:bg-blue-700 focus:ring focus:ring-blue-200 disabled:cursor-not-allowed disabled:border-blue-300 disabled:bg-blue-300">{{ $content }}</button>
                    </form>
                    <button @click="showModal = false" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-100 focus:ring focus:ring-gray-100 disabled:cursor-not-allowed disabled:border-gray-100 disabled:bg-gray-50 disabled:text-gray-400">戻る</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if ($status == "解決済み") {
    $class = "bg-gray-100";
} else {
    $class = "";
}
?>

<div {{ $attributes->merge(['class' => $class . ' relative flex justify-between items-center border-t border-gray-300 py-3 pl-6 pr-12 text-sm']) }}>
    @if ($status == "解決済み")
    <div class="absolute transform top-1/2 -translate-x-1/2 left-1/2 -translate-y-1/2 m-0 p-2 w-44 h-14 border-2 border-double border-red-500 rounded-lg text-red-500 text-center text-lg leading-24 -rotate-12">
        <div class="absolute transform top-1/2 -translate-x-1/2 left-1/2 -translate-y-1/2">
            <span class="text-lg font-extrabold">{{ $status }}</span>
        </div>
    </div>
    @endif
    <div class="flex gap-2 font-mono">
        <div class="flex flex-col gap-2">
            <p class="text-gray-800 text-base">{{ $title ?? '' }}</p>
            <p class="text-gray-500 text-xs">{{ $description ?? '' }}</p>
            <ul class="flex flex-wrap gap-2">{{ $tag ?? '' }}</ul>
            <div class="flex-center justify-start gap-4 text-gray-500 text-xs">
                <span>{{ $date ?? '' }}</span>
                <div class="flex-center likes" data-request_id="{{ $id }}" data-is_liked="{{ $is_liked }}">
                    <svg xmlns=" http://www.w3.org/2000/svg" fill="{{ $fill }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                    <span class="flex-center like-count">{{ $likes ?? '' }}</span>
                </div>
                <div class="flex-center gap-1">
                    <img src="{{ $user_icon ?? '' }}" alt="ユーザアイコン" class="w-6 h-6 rounded-full object-cover object-center">
                    <span class="flex-center">{{ $user_name ?? '' }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="flex-center gap-2">
        {{ $button }}
    </div>
    {{ $slot ?? '' }}
</div>

@if ($paginator->hasPages())
    <nav class="flex items-center justify-center mt-8 mb-4 min-h-[64px]">
        <div class="flex items-center justify-center group h-full">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span
                    class="flex items-center justify-center text-xl font-bold leading-none text-zinc-300 bg-slate-50 relative rounded-full border border-solid border-zinc-200 size-11 z-[4] cursor-not-allowed transition-all duration-300 ease-out shadow-sm group-hover:size-[38px]">
                    &laquo;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="flex items-center justify-center text-xl font-bold leading-none text-zinc-500 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] cursor-pointer transition-all duration-300 ease-out shadow-sm group-hover:size-[38px] hover:!size-16 hover:!text-2xl hover:!z-[50] hover:!text-red-600 hover:!border-red-600 hover:shadow-[0_0_15px_rgba(220,38,38,0.4)]">
                    &laquo;
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span
                        class="flex items-center justify-center text-lg font-extrabold leading-none text-zinc-400 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] -ml-2 cursor-default transition-all duration-300 ease-out shadow-sm group-hover:size-[38px] hover:!size-16 hover:!z-[50]">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="flex items-center justify-center text-2xl font-extrabold leading-none text-white bg-gradient-to-tr from-red-600 to-orange-500 relative rounded-full border-2 border-solid border-red-600 size-14 z-[6] -ml-2 cursor-default shadow-md transition-all duration-300 ease-out group-hover:size-[38px] group-hover:from-red-400 group-hover:to-orange-400 group-hover:border-red-400 group-hover:text-red-50 hover:!size-16 hover:!z-[50] hover:!from-red-700 hover:!to-orange-600 hover:!border-red-700 hover:!text-white hover:shadow-[0_0_20px_rgba(220,38,38,0.6)]">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="flex items-center justify-center text-xl font-extrabold leading-none text-zinc-500 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] -ml-2 cursor-pointer transition-all duration-300 ease-out shadow-sm group-hover:size-[38px] group-hover:text-zinc-400 hover:!size-16 hover:!text-2xl hover:!z-[50] hover:!text-red-600 hover:!border-red-600 hover:shadow-[0_0_15px_rgba(220,38,38,0.4)]">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="flex items-center justify-center text-xl font-bold leading-none text-zinc-500 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] -ml-2 cursor-pointer transition-all duration-300 ease-out shadow-sm group-hover:size-[38px] hover:!size-16 hover:!text-2xl hover:!z-[50] hover:!text-red-600 hover:!border-red-600 hover:shadow-[0_0_15px_rgba(220,38,38,0.4)]">
                    &raquo;
                </a>
            @else
                <span
                    class="flex items-center justify-center text-xl font-bold leading-none text-zinc-300 bg-slate-50 relative rounded-full border border-solid border-zinc-200 size-11 z-[4] -ml-2 cursor-not-allowed transition-all duration-300 ease-out shadow-sm group-hover:size-[38px]">
                    &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif

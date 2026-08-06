@if ($paginator->hasPages())
    <nav class="flex items-center justify-center">
        <div class="flex items-center justify-center [&_a]:[box-shadow:#0000001f_0_1px_3px,#0000003d_0_0_1px] [&_a]:[transition:all_.25s_ease] [&_span]:[box-shadow:#0000001f_0_1px_3px,#0000003d_0_0_1px] [&_span]:[transition:all_.25s_ease]">
            
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center text-xl font-bold leading-none text-zinc-300 p-2 bg-slate-50 relative rounded-full border border-solid border-zinc-200 size-11 z-[4] cursor-not-allowed">
                    &laquo;
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center text-xl font-bold leading-none text-zinc-500 p-2 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] cursor-pointer hover:z-[50] hover:scale-110 hover:text-emerald-500">
                    &laquo;
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="flex items-center justify-center text-lg font-extrabold leading-none text-zinc-400 p-4 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] -ml-2 cursor-default">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex items-center justify-center text-2xl font-extrabold leading-none text-emerald-500 p-4 bg-white relative rounded-full border-2 border-solid border-emerald-400 size-14 z-[6] -ml-2 cursor-default shadow-md">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="flex items-center justify-center text-xl font-extrabold leading-none text-zinc-400 p-4 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] -ml-2 cursor-pointer hover:z-[50] hover:scale-125 hover:text-emerald-500">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center text-xl font-bold leading-none text-zinc-500 p-2 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] -ml-2 cursor-pointer hover:z-[50] hover:scale-110 hover:text-emerald-500">
                    &raquo;
                </a>
            @else
                <span class="flex items-center justify-center text-xl font-bold leading-none text-zinc-300 p-2 bg-slate-50 relative rounded-full border border-solid border-zinc-200 size-11 z-[4] -ml-2 cursor-not-allowed">
                    &raquo;
                </span>
            @endif
        </div>
    </nav>
@endif

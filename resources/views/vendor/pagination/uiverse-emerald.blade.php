@if ($paginator->hasPages())
    <nav class="flex items-center justify-center mt-8 mb-4">
        <div class="flex items-center justify-center group">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center text-xl font-bold leading-none text-zinc-300 bg-slate-50 relative rounded-full border border-solid border-zinc-200 size-11 z-[4] cursor-not-allowed transition-all duration-300 ease-out shadow-sm origin-center group-hover:scale-90">&laquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center text-xl font-bold leading-none text-zinc-500 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] cursor-pointer transition-all duration-300 ease-out shadow-sm origin-center group-hover:scale-90 hover:!scale-150 hover:!z-[50] hover:!text-emerald-500 hover:!border-emerald-500 hover:shadow-[0_0_15px_rgba(16,185,129,0.4)]">&laquo;</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="flex items-center justify-center text-lg font-extrabold leading-none text-zinc-400 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] -ml-2 cursor-default transition-all duration-300 ease-out shadow-sm origin-center group-hover:scale-90 hover:!scale-150 hover:!z-[50]">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex items-center justify-center text-2xl font-extrabold leading-none text-white bg-emerald-500 relative rounded-full border-2 border-solid border-emerald-500 size-14 z-[6] -ml-2 cursor-default shadow-md transition-all duration-300 ease-out origin-center group-hover:scale-75 group-hover:bg-emerald-400 group-hover:border-emerald-400 group-hover:text-emerald-50 hover:!scale-110 hover:!z-[50] hover:!bg-emerald-600 hover:!border-emerald-600 hover:!text-white hover:shadow-[0_0_20px_rgba(16,185,129,0.6)]">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="flex items-center justify-center text-xl font-extrabold leading-none text-zinc-500 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] -ml-2 cursor-pointer transition-all duration-300 ease-out shadow-sm origin-center group-hover:scale-90 group-hover:text-zinc-400 hover:!scale-150 hover:!z-[50] hover:!text-emerald-500 hover:!border-emerald-500 hover:shadow-[0_0_15px_rgba(16,185,129,0.4)]">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center text-xl font-bold leading-none text-zinc-500 bg-white relative rounded-full border border-solid border-zinc-300 size-11 z-[4] -ml-2 cursor-pointer transition-all duration-300 ease-out shadow-sm origin-center group-hover:scale-90 hover:!scale-150 hover:!z-[50] hover:!text-emerald-500 hover:!border-emerald-500 hover:shadow-[0_0_15px_rgba(16,185,129,0.4)]">&raquo;</a>
            @else
                <span class="flex items-center justify-center text-xl font-bold leading-none text-zinc-300 bg-slate-50 relative rounded-full border border-solid border-zinc-200 size-11 z-[4] -ml-2 cursor-not-allowed transition-all duration-300 ease-out shadow-sm origin-center group-hover:scale-90">&raquo;</span>
            @endif
        </div>
    </nav>
@endif

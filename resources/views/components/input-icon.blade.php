@props(['icon', 'type' => 'text', 'anim' => 'ih-bounce'])

<div class="relative group {{ $attributes->get('wrapper-class') }}">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 group-focus-within:text-slate-900 transition-colors {{ $anim }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    </div>
    <input
        type="{{ $type }}"
        {!! $attributes->except(['icon', 'anim', 'wrapper-class', 'type'])->toHtml() !!}
        class="{{ 'w-full pl-10 pr-4 py-2.5 text-sm rounded-xl outline-none transition-all ' . $attributes->get('class', '') }}"
    >
</div>

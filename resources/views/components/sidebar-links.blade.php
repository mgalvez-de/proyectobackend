{{--
    Enlaces de la sidebar. Se usa dos veces: en escritorio (se puede contraer a solo íconos)
    y en el panel del celular (siempre con texto).
--}}
@props(['sections' => [], 'mobile' => false])

<nav class="space-y-4" aria-label="Menú lateral">
    @foreach ($sections as $title => $links)
        <div>
            @if ($mobile)
                <p class="px-3 pb-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $title }}</p>
            @else
                <p x-show="! collapsed" class="px-3 pb-1.5 text-[11px] font-semibold uppercase tracking-wider text-slate-400 whitespace-nowrap">{{ $title }}</p>
                <div x-show="collapsed" x-cloak class="mx-3 mb-2 border-t border-slate-200"></div>
            @endif

            <ul class="space-y-0.5">
                @foreach ($links as $link)
                    <li>
                        <a href="{{ $link['url'] }}" title="{{ $link['label'] }}"
                            @if ($link['active']) aria-current="page" @endif
                            @if (! $mobile) x-bind:class="collapsed ? 'justify-center' : ''" @endif
                            class="flex items-center gap-3 rounded-lg px-3 py-1.5 text-sm font-medium transition-colors
                                {{ $link['active'] ? 'bg-ucsc/10 text-ucsc' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            <x-icon :name="$link['icon']" class="w-5 h-5 shrink-0 {{ $link['active'] ? 'text-ucsc' : 'text-slate-400' }}" />
                            <span @if (! $mobile) x-show="! collapsed" @endif class="truncate">{{ $link['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</nav>

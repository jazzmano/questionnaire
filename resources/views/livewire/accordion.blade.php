<div class="accordion-container">
    
    @if (empty($blocks))
        {{-- Fallback: display as regular markdown if no blocks found --}}
        <div class="prose prose-slate lg:prose-lg max-w-none mx-auto
                   prose-headings:text-slate-800 prose-headings:font-semibold prose-headings:mb-4 prose-headings:mt-6
                   prose-p:text-slate-700 prose-p:leading-relaxed prose-p:mb-4
                   prose-strong:text-slate-900 prose-strong:font-semibold
                   prose-em:text-slate-600 prose-em:italic
                   prose-ul:text-slate-700 prose-ol:text-slate-700 prose-ul:mb-4 prose-ol:mb-4
                   prose-li:text-slate-700 prose-li:leading-relaxed prose-li:mb-1
                   dark:prose-invert dark:prose-headings:text-slate-100 dark:prose-p:text-slate-300
                   dark:prose-strong:text-slate-100 dark:prose-ul:text-slate-300 dark:prose-ol:text-slate-300 dark:prose-li:text-slate-300">
            {!! Str::markdown($content) !!}
        </div>
    @else
        {{-- Render blocks --}}
        <div class="space-y-6">
            @foreach ($blocks as $index => $block)
                @if ($block['type'] === 'content')
                    {{-- Regular content block --}}
                    <div class="prose prose-slate lg:prose-lg max-w-none mx-auto
                               prose-headings:text-slate-800 prose-headings:font-semibold prose-headings:mb-4 prose-headings:mt-6
                               prose-h1:text-xl prose-h1:font-bold
                               prose-h2:text-lg prose-h2:font-semibold  
                               prose-h3:text-base prose-h3:font-semibold
                               prose-h4:text-base prose-h4:font-medium
                               prose-p:text-slate-700 prose-p:leading-relaxed prose-p:mb-4
                               prose-strong:text-slate-900 prose-strong:font-semibold
                               prose-em:text-slate-600 prose-em:italic
                               prose-ul:text-slate-700 prose-ol:text-slate-700 prose-ul:mb-4 prose-ol:mb-4
                               prose-li:text-slate-700 prose-li:leading-relaxed prose-li:mb-1
                               dark:prose-invert dark:prose-headings:text-slate-100 dark:prose-p:text-slate-300
                               dark:prose-strong:text-slate-100 dark:prose-ul:text-slate-300 dark:prose-ol:text-slate-300 dark:prose-li:text-slate-300">
                        {!! Str::markdown($block['markdown']) !!}
                    </div>
                @elseif ($block['type'] === 'accordion')
                    {{-- Accordion block --}}
                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden shadow-sm">
                        {{-- Accordion Header --}}
                        <button wire:click="toggleSection({{ $index }})"
                            class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700
                                   flex items-center justify-between transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset"
                            type="button">
                            <span class="text-left font-medium text-slate-900 dark:text-slate-100 text-base">
                                {{ $block['heading'] }}
                            </span>
                            <svg class="w-5 h-5 text-slate-500 dark:text-slate-400 transition-transform duration-200 flex-shrink-0 ml-4
                                       {{ in_array($index, $openSections) ? 'rotate-180' : '' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Accordion Content --}}
                        <div class="overflow-hidden transition-all duration-300 ease-in-out {{ in_array($index, $openSections) ? 'max-h-screen opacity-100' : 'max-h-0 opacity-0' }}"
                            style="transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;">
                            <div class="px-6 py-5 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 max-h-96 overflow-y-auto">
                                <div class="prose prose-slate lg:prose-lg max-w-none
                                           prose-headings:text-slate-800 prose-headings:font-semibold prose-headings:mb-4 prose-headings:mt-6 prose-headings:first:mt-0
                                           prose-h1:text-xl prose-h1:font-bold
                                           prose-h2:text-lg prose-h2:font-semibold  
                                           prose-h3:text-base prose-h3:font-semibold
                                           prose-h4:text-base prose-h4:font-medium
                                           prose-p:text-slate-700 prose-p:leading-relaxed prose-p:mb-4
                                           prose-strong:text-slate-900 prose-strong:font-semibold
                                           prose-em:text-slate-600 prose-em:italic
                                           prose-ul:text-slate-700 prose-ol:text-slate-700 prose-ul:mb-4 prose-ol:mb-4 prose-ul:pl-6 prose-ol:pl-6
                                           prose-li:text-slate-700 prose-li:leading-relaxed prose-li:mb-2
                                           dark:prose-invert dark:prose-headings:text-slate-100 dark:prose-p:text-slate-300
                                           dark:prose-strong:text-slate-100 dark:prose-ul:text-slate-300 dark:prose-ol:text-slate-300 dark:prose-li:text-slate-300">
                                    {!! Str::markdown($block['body']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

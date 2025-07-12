<div class="accordion-container">
    <style>
        .accordion-container .prose {
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .accordion-container .prose h1,
        .accordion-container .prose h2,
        .accordion-container .prose h3,
        .accordion-container .prose h4 {
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
        }

        .accordion-container .prose p {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .accordion-container .prose ul,
        .accordion-container .prose ol {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
            padding-left: 1.5rem;
        }

        .accordion-container .prose li {
            margin-top: 0.25rem;
            margin-bottom: 0.25rem;
        }
    </style>
    
    @if (empty($blocks))
        {{-- Fallback: display as regular markdown if no blocks found --}}
        <div class="prose prose-slate lg:prose-xl max-w-none">
            {!! Str::markdown($content) !!}
        </div>
    @else
        {{-- Render blocks --}}
        <div class="space-y-4">
            @foreach ($blocks as $index => $block)
                @if ($block['type'] === 'content')
                    {{-- Regular content block --}}
                    <div class="prose prose-slate lg:prose-xl max-w-none
                               prose-headings:text-slate-800 prose-headings:font-semibold
                               prose-p:text-slate-700 prose-p:leading-relaxed
                               prose-strong:text-slate-900 prose-strong:font-semibold
                               prose-em:text-slate-600 prose-em:italic
                               prose-ul:text-slate-700 prose-ol:text-slate-700
                               prose-li:text-slate-700 prose-li:leading-relaxed
                               dark:prose-invert dark:prose-headings:text-slate-100 dark:prose-p:text-slate-300
                               dark:prose-strong:text-slate-100 dark:prose-ul:text-slate-300 dark:prose-ol:text-slate-300 dark:prose-li:text-slate-300">
                        {!! Str::markdown($block['markdown']) !!}
                    </div>
                @elseif ($block['type'] === 'accordion')
                    {{-- Accordion block --}}
                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                        {{-- Accordion Header --}}
                        <button wire:click="toggleSection({{ $index }})"
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700
                                   flex items-center justify-between transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset"
                            type="button">
                            <span class="text-left font-medium text-slate-900 dark:text-slate-100">
                                {{ $block['heading'] }}
                            </span>
                            <svg class="w-5 h-5 text-slate-500 dark:text-slate-400 transition-transform duration-200
                                       {{ in_array($index, $openSections) ? 'rotate-180' : '' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Accordion Content --}}
                        <div class="overflow-hidden transition-all duration-300 ease-in-out {{ in_array($index, $openSections) ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}"
                            style="transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;">
                            <div
                                class="px-4 py-3 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700">
                                <div
                                    class="prose prose-slate max-w-none
                                           prose-headings:text-slate-800 prose-headings:font-semibold
                                           prose-p:text-slate-700 prose-p:leading-relaxed
                                           prose-strong:text-slate-900 prose-strong:font-semibold
                                           prose-em:text-slate-600 prose-em:italic
                                           prose-ul:text-slate-700 prose-ol:text-slate-700
                                           prose-li:text-slate-700 prose-li:leading-relaxed
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

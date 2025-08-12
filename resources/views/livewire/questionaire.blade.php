<div class="p-6 pt-16 sm:pt-20 md:pt-24 lg:pt-28 flex flex-col gap-6 max-w-6xl mx-auto">
    {{-- Progress Timeline --}}
    @if (!$completedFlow && $currentNodeKey !== 'introduction')
        <div class="mx-4 md:mx-8 lg:mx-12">
            <livewire:timeline :visitedNodes="$visitedNodes" :currentNodeKey="$currentNodeKey" :questionsAnswered="$questionsAnswered" :key="'timeline-' . $currentNodeKey . '-' . count($visitedNodes)" />
        </div>
    @endif

    <flux:separator />
    {{-- Top: Question and Explanation --}}
    @if ($currentNode)
        <div
            class="mx-4 md:mx-8 lg:mx-12 px-8 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-700 rounded-xl border-l-4 border-yellow-400 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex-1">
                    <flux:heading size="xl" class="text-slate-900 dark:text-slate-100 font-semibold leading-tight">
                        {{ $currentNode['question'] ?? ($currentNode['message'] ?? 'No content available') }}
                    </flux:heading>
                </div>
            </div>
        </div>
        @if (
            $currentNodeKey !== 'identification' &&
                $currentNodeKey !== 'not_subject_to_the_AI_Act' &&
                $currentNodeKey !== 'your_system_is_an_AI_system')
            <div
                class="prose prose-slate lg:prose-xl max-w-none
                       my-8 mx-4 md:mx-8 lg:mx-12 px-6 py-6
                       bg-white/50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700
                       prose-headings:text-slate-800 prose-headings:font-semibold prose-headings:mb-6 prose-headings:mt-8
                       prose-p:text-slate-700 prose-p:leading-relaxed prose-p:mb-6
                       prose-strong:text-slate-900 prose-strong:font-semibold
                       prose-em:text-slate-600 prose-em:italic
                       prose-a:text-blue-600 prose-a:font-medium prose-a:no-underline hover:prose-a:text-blue-700 hover:prose-a:underline prose-a:break-words
                       prose-ul:text-slate-700 prose-ul:mb-6 prose-ul:space-y-2
                       prose-ol:text-slate-700 prose-ol:mb-6 prose-ol:space-y-2
                       prose-li:text-slate-700 prose-li:leading-relaxed prose-li:mb-2
                       prose-blockquote:border-l-blue-500 prose-blockquote:bg-blue-50 prose-blockquote:p-6 prose-blockquote:rounded-r-lg prose-blockquote:my-8
                       prose-code:text-blue-800 prose-code:bg-blue-100 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:font-mono prose-code:text-sm
                       dark:prose-invert dark:prose-headings:text-slate-100 dark:prose-p:text-slate-300
                       dark:prose-strong:text-slate-100 dark:prose-a:text-blue-400 dark:hover:prose-a:text-blue-300
                       dark:prose-ul:text-slate-300 dark:prose-ol:text-slate-300 dark:prose-li:text-slate-300
                       dark:prose-blockquote:bg-slate-800 dark:prose-blockquote:border-l-blue-400
                       dark:prose-code:text-blue-300 dark:prose-code:bg-slate-800">
                <livewire:accordion :markdownFile="$markdownFile" :key="'accordion-' . $currentNodeKey" />

                {{-- Answer options inside the explanation box --}}
                @if ($showOptions && $currentNodeKey !== 'identification')
                    <div class="not-prose mt-8 pt-8">
                        <div
                            class="bg-gradient-to-br from-slate-50 to-gray-50 dark:from-slate-800 dark:to-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-600 shadow-sm">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Your Response</h3>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">
                                {{-- Left: Options --}}
                                <div class="space-y-4">
                                    @if ($isMultiSelectNode)
                                        <div class="mb-4">
                                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-4">
                                                Choose your answers (you can select multiple):</p>
                                            <div class="space-y-3">
                                                @foreach ($currentNode['options'] ?? [] as $index => $option)
                                                    <label
                                                        class="flex items-start gap-3 p-4 rounded-lg border-2 border-slate-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors cursor-pointer {{ in_array($index, $multiSelectedAnswers) ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'bg-white dark:bg-slate-800' }}">
                                                        <input type="checkbox" wire:model="multiSelectedAnswers"
                                                            value="{{ $index }}"
                                                            class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                        <span
                                                            class="text-sm font-medium text-slate-800 dark:text-slate-200 leading-relaxed">{{ $option['label'] }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <flux:button variant="primary" wire:click="selectOption"
                                            class="w-full py-3 text-base font-semibold">
                                            <span class="hidden sm:inline">Continue with Selected Options</span>
                                            <span class="sm:hidden">Continue</span>
                                        </flux:button>
                                    @else
                                        <div class="mb-4">
                                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-4">
                                                Choose your answer:</p>
                                            <div class="space-y-3">
                                                @foreach ($currentNode['options'] ?? [] as $index => $option)
                                                    <label
                                                        class="flex items-start gap-3 p-4 rounded-lg border-2 border-slate-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors cursor-pointer {{ $selectedAnswer == $index ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'bg-white dark:bg-slate-800' }}">
                                                        <input type="radio" wire:model="selectedAnswer"
                                                            value="{{ $index }}"
                                                            class="mt-1 w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                        <span
                                                            class="text-sm font-medium text-slate-800 dark:text-slate-200 leading-relaxed">{{ $option['label'] }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        @if (!$requiresJustification)
                                            <flux:button variant="primary" wire:click="selectOption"
                                                class="w-full py-3 text-base font-semibold">
                                                Continue
                                            </flux:button>
                                        @endif
                                    @endif
                                </div>

                                {{-- Right: Justification Textarea --}}
                                @if ($requiresJustification)
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div
                                                class="w-5 h-5 bg-orange-500 text-white rounded-full flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                                Justification Required
                                            </label>
                                        </div>
                                        <div
                                            class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-600 p-1">
                                            <flux:textarea wire:model="justification_text"
                                                placeholder="Please provide your justification here. Explain your reasoning and any relevant considerations..."
                                                class="w-full min-h-48 border-0 focus:ring-0 resize-none"
                                                rows="8" />
                                        </div>
                                        @error('justification_text')
                                            <div class="flex items-center gap-2 text-red-600 text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <flux:button variant="primary" wire:click="selectOption"
                                            class="w-full py-3 text-base font-semibold">
                                            Continue
                                        </flux:button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @else
        <h2 class="font-semibold mb-4">{{ $currentNode['message'] }}</h2>
        <p>No node found.</p>
    @endif

    {{-- Identification Questions --}}
    @if ($currentNodeKey === 'identification')
        <div
            class="bg-white/50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700 p-8 mx-auto max-w-3xl">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-semibold text-slate-800 dark:text-slate-100 mb-2">Contact Information</h3>
                <p class="text-slate-600 dark:text-slate-400">Please provide your details to complete the assessment</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                @foreach ($identificationQuestions as $question => $questionContent)
                    <div class="space-y-2 flex flex-col h-full">
                        <flux:field class="flex-1">
                            <flux:label class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ ucfirst($question) }}</flux:label>
                            <flux:description
                                class="text-xs text-slate-500 dark:text-slate-400 min-h-[2.5rem] flex items-center">
                                {{ $questionContent }}</flux:description>
                            <flux:input type="{{ $question === 'email' ? 'email' : 'text' }}"
                                wire:model.defer="userDetails.{{ $question }}" class="w-full" />
                            <flux:error name="userDetails.{{ $question }}" />
                        </flux:field>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Identification button outside the box --}}
    @if ($showOptions && $currentNodeKey === 'identification')
        <div class="flex justify-center mt-6">
            <flux:button variant="primary" wire:click="selectOption">
                {{ $session->final_node_key ? 'Complete Assessment' : 'Start Questionnaire' }}
            </flux:button>
        </div>
    @endif

    {{-- Flow Completed --}}
    @if ($completedFlow)
        <div class="flex flex-col mt-8 gap-4 items-center">
            <a href="/questionnaire/{{ $session->uuid }}/report" target="_blank">
                <flux:button>Download Results</flux:button>
            </a>
        </div>
        <div class="flex flex-col mt-8 gap-4 items-center">
            <a href="/questionnaire/">
                <flux:button variant="primary">Assess new system</flux:button>
            </a>
        </div>
    @endif
    {{-- Navigation --}}
    @if (!empty($nodeHistory))
        <div class="flex justify-between mt-6 gap-4">
            <flux:button variant="ghost" wire:click='goBack' icon='arrow-left'>
                Back
            </flux:button>
        </div>
    @endif

    {{-- Version Number Footer --}}
    <div class="mt-12 pt-6 border-t border-slate-200 dark:border-slate-700">
        <div class="text-center">
            <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                Version 1.0.0
            </p>
        </div>
    </div>
</div>

{{-- Auto-scroll functionality for question navigation --}}
<script src="{{ asset('js/questionnaire-scroll.js') }}"></script>

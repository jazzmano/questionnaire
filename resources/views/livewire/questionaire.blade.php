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
        <div class="mx-4 md:mx-8 lg:mx-12 px-6">
            <flux:heading size="xl">
                {{ $currentNode['question'] ?? ($currentNode['message'] ?? 'No content available') }}
            </flux:heading>
        </div>
        @if (isset($explanationText) &&
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
                {!! $explanationText !!}

                {{-- Answer options inside the explanation box --}}
                @if ($showOptions && $currentNodeKey !== 'identification')
                    <div class="not-prose mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                            {{-- Left: Options --}}
                            <div class="space-y-3">
                                @if ($isMultiSelectNode)
                                    <flux:checkbox.group wire:model="multiSelectedAnswers"
                                        label="Choose your answers (you can select multiple)">
                                        @foreach ($currentNode['options'] ?? [] as $index => $option)
                                            <flux:checkbox value="{{ $index }}" label="{{ $option['label'] }}" />
                                        @endforeach
                                    </flux:checkbox.group>
                                    <flux:button variant="primary" wire:click="selectOption" class="w-full text-center">
                                        <span class="hidden sm:inline">Continue with Selected Options</span>
                                        <span class="sm:hidden">Continue</span>
                                    </flux:button>
                                @else
                                    <flux:radio.group wire:model="selectedAnswer" label="Choose your answer">
                                        @foreach ($currentNode['options'] ?? [] as $index => $option)
                                            <flux:radio value="{{ $index }}" label="{{ $option['label'] }}" />
                                        @endforeach
                                    </flux:radio.group>
                                    <flux:button variant="primary" wire:click="selectOption">Continue
                                    </flux:button>
                                @endif
                            </div>
                            {{-- Right: Justification Textarea --}}
                            @if ($requiresJustification)
                                <div class="w-full">
                                    <flux:textarea wire:model="justification_text"
                                        placeholder="Please provide your justification here..." class="w-full min-h-48"
                                        rows="8" />
                                    <div class="text-red-600 text-sm mt-1">
                                        @error('justification_text')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            @endif
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
</div>

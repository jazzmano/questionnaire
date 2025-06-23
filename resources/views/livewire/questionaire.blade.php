<div class="p-6 flex flex-col gap-6 max-w-6xl mx-auto">

    {{-- Top: Question and Explanation --}}
    @if($currentNode)
        <flux:heading size="xl">
            {{ $currentNode['question'] ?? $currentNode['message'] ?? 'No content available' }}
        </flux:heading>
        @if(isset($explanationText))
            <div class="prose prose-slate lg:prose-xl max-w-none
                       prose-headings:text-slate-800 prose-headings:font-semibold
                       prose-p:text-slate-700 prose-p:leading-relaxed
                       prose-strong:text-slate-900 prose-strong:font-semibold
                       prose-em:text-slate-600 prose-em:italic
                       prose-a:text-blue-600 prose-a:font-medium prose-a:no-underline hover:prose-a:text-blue-700 hover:prose-a:underline
                       prose-ul:text-slate-700 prose-ol:text-red-700
                       prose-li:text-slate-700 prose-li:leading-relaxed
                       prose-blockquote:border-l-blue-500 prose-blockquote:bg-blue-50 prose-blockquote:p-4 prose-blockquote:rounded-r-lg
                       prose-code:text-blue-800 prose-code:bg-blue-100 prose-code:px-2 prose-code:py-1 prose-code:rounded prose-code:font-mono prose-code:text-sm
                       dark:prose-invert dark:prose-headings:text-slate-100 dark:prose-p:text-slate-300
                       dark:prose-strong:text-slate-100 dark:prose-a:text-blue-400 dark:hover:prose-a:text-blue-300
                       dark:prose-ul:text-slate-300 dark:prose-ol:text-slate-300 dark:prose-li:text-slate-300
                       dark:prose-blockquote:bg-slate-800 dark:prose-blockquote:border-l-blue-400
                       dark:prose-code:text-blue-300 dark:prose-code:bg-slate-800">
                {!! $explanationText !!}
            </div>
        @endif
    @else
        <h2 class="font-semibold mb-4">{{ $currentNode['message'] }}</h2>
        <p>No node found.</p>
    @endif

    {{-- Identification Questions --}}
    @if($currentNodeKey === 'identification')
        @foreach($identificationQuestions as $question => $questionContent)
            <div class="w-full max-w-md mx-auto">
                <flux:field>
                    <flux:label>{{ strtoupper($question) }}</flux:label>
                    <flux:description>{{ strtoupper($questionContent) }}</flux:description>
                    <flux:input
                        type="{{ $question === 'email' ? 'email' : 'text' }}"
                        wire:model.defer="userDetails.{{ $question }}"
                    />
                    <flux:error name="userDetails.{{ $question }}" />
                </flux:field>
            </div>
        @endforeach
    @endif

    {{-- Bottom: Grid with answer buttons and justification --}}
    @if($showOptions)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-6 items-start">
        {{-- Left: Options --}}
        <div class="space-y-3">
            @if($currentNodeKey !== 'identification' && $isMultiSelectNode)
                <flux:checkbox.group wire:model="multiSelectedAnswers" label="Choose your answers (you can select multiple)">
                    @foreach($currentNode['options'] ?? [] as $index => $option)
                        <flux:checkbox value="{{ $index }}" label="{{ $option['label'] }}" />
                    @endforeach
                </flux:checkbox.group>
                <flux:button
                    variant="primary"
                    wire:click="selectOption"
                    >Continue with Selected Options
                </flux:button>
            @elseif($currentNodeKey !== 'identification' )
                <flux:radio.group wire:model="selectedAnswer">
                    @foreach($currentNode['options'] ?? [] as $index => $option)
                        <flux:radio value="{{ $index }}" label="{{ $option['label'] }}" />
                    @endforeach
                </flux:radio.group>
                <flux:button
                    variant="primary"
                    wire:click="selectOption"
                    >Continue
                </flux:button>
            @elseif($currentNodeKey === 'identification')
                <flux:button
                    variant="primary"
                    wire:click="selectOption">
                    {{ $session->final_node_key ? 'Complete Assessment' : 'Start Questionnaire' }}
                </flux:button>
            @endif
        </div>
        {{-- Right: Justification Textarea --}}
        @if($requiresJustification)
        <div class="w-full">
            <flux:textarea
                wire:model="justification_text"
                placeholder="Please provide your justification here..."
                resize="both"
                class="w-full"
            />
            <div class="text-red-600 text-sm mt-1">
                @error('justification_text') {{ $message }} @enderror
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Flow Completed --}}
    @if($completedFlow)
        <div class="flex flex-col mt-8 gap-4 items-center">
            <a href="/questionnaire/{{ $session->uuid }}/report" target="_blank">
                <flux:button>Download Results</flux:button>
            </a>
        </div>
    @endif

    {{-- Navigation --}}
    @if(!empty($nodeHistory))
        <div class="flex justify-between mt-6 gap-4">
            <flux:button variant="ghost" wire:click='goBack' icon='arrow-left'>
                Back
            </flux:button>
        </div>
    @endif
</div>

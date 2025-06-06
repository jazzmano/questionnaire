<div class="p-4 flex flex-col items-center gap-2">
    @if($proceedWithFlow)
    @if($currentNode)
        <flux:heading>{{ $currentNode['question'] ?? $currentNode['message'] ?? 'No content available' }}</flux:heading>

        @if(isset($explanationText))
            <flux:text>{{ $explanationText }}</flux:text>
        @endif
    <div class="space-y-2 flex gap-2 flex-col">
        @foreach($currentNode['options'] ?? [] as $option)
            <flux:button
                wire:click='selectOption(
                    {{ json_encode($option["next"] ?? "") }},
                    {{ json_encode($option["actions"] ?? []) }},
                    {{ json_encode($option["label"] ?? []) }},

                )'>
                {{ $option['label'] }}
            </flux:button>
        @endforeach
    </div>
         @else
        <h2 class="text-xl font-semibold mb-4">{{ $currentNode['message']}}</h2>
        <p>No node found.</p>
        @endif
    @endif
    @if($requiresJustification)
    <div class="flex flex-col items-center  gap-4">
    <flux:textarea
        wire:model="justification_text"
        placeholder="Please provide your justification here..."
        resize="both">
    </flux:textarea>

    <flux:button wire:click="submitJustification">Continue</flux:button>

    </div>
    @endif
    @if($completedFlow)
        <div class="flex flex-col mt-4 gap-4 items-center">
            <a href="/questionnaire/{{ $session->id }}/report" target="_blank">
                <flux:button>Download Results</flux:button>
            </a>
        </div>
    @endif
    @if(!empty($nodeHistory))
    <div class="flex justify-between mt-6 gap-4">
        <flux:button wire:click='goBack' icon='arrow-left'>Previous question</flux:button>
    </div>
    @endif
</div>

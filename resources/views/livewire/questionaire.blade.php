<div class="p-4 flex flex-col items-center">
    @if($proceedWithFlow)
    @if($currentNode)
        <h2 class="text-xl font-semibold mb-4">{{ $currentNode['question'] ?? $currentNode['message'] }}</h2>

        @if(isset($currentNode['explanation']))
            <p class="mb-2 text-sm text-gray-600">{{ $currentNode['explanation'] }}</p>
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
</div>

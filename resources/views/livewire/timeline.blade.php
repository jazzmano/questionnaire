{{-- Progress Timeline --}}
<div class="bg-white/50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700 p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Assessment Progress</h3>
        <span class="hidden md:inline text-sm text-slate-600 dark:text-slate-400">
            {{ $questionsAnswered }} questions answered
        </span>
    </div>

    {{-- Mobile View: Simplified progress --}}
    <div class="md:hidden">
        <div class="mb-4">
            <div class="flex justify-between text-sm text-slate-600 dark:text-slate-400 mb-2">
                <span>Step {{ $this->getNumberFromCurrentNodeKey($currentNodeKey) }} of 9</span>
                <span>{{ $this->getProgressPercentage() }}%</span>
            </div>
            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                <div class="bg-yellow-400 h-2 rounded-full transition-all duration-300 ease-out"
                     style="width: {{ $this->getProgressPercentage() }}%"></div>
            </div>
        </div>
        
        {{-- Current step indicator --}}
        <div class="flex items-center justify-center p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-yellow-400 border-2 border-yellow-400 flex items-center justify-center">
                    @if ($currentNodeKey === 'identification')
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <span class="text-white font-medium">{{ is_numeric($currentNodeKey) ? (int)$currentNodeKey + 1 : '?' }}</span>
                    @endif
                </div>
                <div>
                    <div class="font-medium text-slate-800 dark:text-slate-100">
                        {{ $this->getNodeDisplayName($currentNodeKey) }}
                    </div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">
                        Current Step
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Desktop View: Full timeline --}}
    <div class="hidden md:block relative">
        {{-- Progress bar background --}}
        <div class="absolute top-4 left-0 right-0 h-0.5 bg-slate-200 dark:bg-slate-700"></div>

        {{-- Progress bar fill --}}
        <div class="absolute top-4 left-0 h-0.5 bg-yellow-400 transition-all duration-300 ease-out"
            style="width: {{ $this->getProgressPercentage() }}%"></div>

        {{-- Timeline steps for main questions (0-7) plus identification --}}
        <div class="flex justify-between relative">
            @php
                $steps = ['0', '1', '2', '3', '4', '5', '6', '7', 'identification'];
            @endphp
            @foreach ($steps as $stepKey)
                @php
                    $status = $this->getNodeStatus($stepKey);
                @endphp

                <div class="flex flex-col items-center">
                    {{-- Step circle --}}
                    <div
                        class="w-8 h-8 rounded-full border-2 flex items-center justify-center text-sm font-medium transition-all duration-300
                        @if ($status === 'completed') bg-yellow-400 border-yellow-400 text-white
                        @elseif ($status === 'current')
                            bg-white border-yellow-400 text-yellow-400 ring-2 ring-blue-200
                        @else
                            bg-white border-slate-300 text-slate-400 @endif">
                        @if ($status === 'completed')
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            @if ($stepKey === 'identification')
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            @else
                                {{ is_numeric($stepKey) ? (int) $stepKey + 1 : $stepKey }}
                            @endif
                        @endif
                    </div>

                    {{-- Step label --}}
                    <div
                        class="mt-2 text-xs text-center
                        @if ($status === 'completed') text-yellow-400 dark:text-yellow-400
                        @elseif ($status === 'current')
                            text-yellow-400 dark:text-yellow-400 font-medium
                        @else
                            text-slate-400 @endif">
                        {{ $this->getNodeDisplayName($stepKey) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

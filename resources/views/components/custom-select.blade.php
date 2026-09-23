@props([
    'name',
    'id' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'onchange' => null,
    'buttonClass' => 'bg-white border border-gray-400 rounded-md shadow-sm text-gray-700'
])

@php
    $id = $id ?? $name;
    // Ensure selected is a scalar for JS comparison
    $selected = $selected !== null ? (string)$selected : '';
    // Find the initial label
    $initialLabel = 'Select...';
    foreach($options as $val => $label) {
        if((string)$val === $selected) {
            $initialLabel = $label;
            break;
        }
    }
@endphp

<div x-data="{
        open: false,
        value: '{{ $selected }}',
        label: '{{ addslashes($initialLabel) }}',
        select(val, txt) {
            this.value = val;
            this.label = txt;
            this.open = false;
            
            // Dispatch change event for hidden input
            this.$nextTick(() => {
                this.$refs.input.dispatchEvent(new Event('change'));
                @if($onchange)
                    {!! $onchange !!}
                @endif
            });
        },
        toggle() {
            this.open = !this.open;
        }
    }"
    @click.away="open = false"
    class="relative w-full text-left"
>
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" x-ref="input" x-model="value" {{ $required ? 'required' : '' }}>
    
    <button type="button" @click="toggle()"
        class="w-full pl-3 pr-10 py-2 text-left cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#009B77]/50 focus:border-[#009B77] sm:text-sm transition-colors relative {{ $buttonClass }}">
        <span class="block truncate" x-text="label"></span>
        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400">
            <!-- Heroicons chevron-up-down -->
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd" />
            </svg>
        </span>
    </button>

    <div x-show="open" 
        x-transition:enter="transition ease-out duration-100" 
        x-transition:enter-start="opacity-0 scale-95" 
        x-transition:enter-end="opacity-100 scale-100" 
        x-transition:leave="transition ease-in duration-75" 
        x-transition:leave-start="opacity-100 scale-100" 
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-[100] mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm origin-top"
        style="display: none;">
        <ul class="relative" role="listbox">
            @foreach($options as $val => $text)
            <li @click="select('{{ $val }}', '{{ addslashes($text) }}')"
                class="group text-gray-900 relative cursor-pointer select-none py-2.5 pl-3 pr-9 hover:bg-[#009B77] hover:text-white transition-colors"
                role="option">
                <span class="block truncate font-normal" :class="{ 'font-bold': value == '{{ $val }}' }">
                    {{ $text }}
                </span>
                
                <span x-show="value == '{{ $val }}'"
                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#009B77] group-hover:text-white">
                    <!-- Heroicons check -->
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                </span>
            </li>
            @endforeach
        </ul>
    </div>
</div>

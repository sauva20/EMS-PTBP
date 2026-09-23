@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'text-sm pl-3 pr-4 py-2 border-gray-400 focus:border-[#009B77] focus:ring-2 focus:ring-[#009B77]/50 focus:outline-none rounded-md shadow-sm transition-colors']) }}>

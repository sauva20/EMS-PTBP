<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#009B77] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#008264] focus:bg-[#008264] active:bg-[#006e54] focus:outline-none focus:ring-2 focus:ring-[#009B77] focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm']) }}>
    {{ $slot }}
</button>

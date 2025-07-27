<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-1 bg-[#FFB4B4] border border-transparent rounded-lg text-sm text-black tracking-wide hover:bg-[#E14434] active:bg-[#FFB4B4] focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

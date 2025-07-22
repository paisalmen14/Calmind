<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2 bg-[#FFB4B4] border border-transparent rounded-md text-sm text-black tracking-wide hover:oppacity-70 focus:oppacity-70 active:bg-[#FFB4B4] focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

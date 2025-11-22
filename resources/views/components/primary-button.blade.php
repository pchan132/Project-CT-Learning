<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-500  rounded-full font-bold text-base text-white uppercase tracking-wider hover:from-purple-500 hover:to-pink-400 focus:outline-none focus:ring-4 focus:ring-purple-400 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition ease-in-out duration-300', 'style' => 'box-shadow: 0 0 10px rgba(192, 132, 252, 0.4);']) }}>
    {{ $slot }}
</button>

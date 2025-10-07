<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-primary dark:bg-primary-dark border border-transparent rounded-md font-semibold text-sm text-gray-100 dark:text-gray-200 hover:bg-primary-accent dark:hover:bg-primary focus:bg-primary-accent dark:focus:bg-primary active:bg-primary-accent focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-secondary-dark transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

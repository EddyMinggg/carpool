@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 bg-secondary dark:bg-secondary-dark dark:text-gray-300 focus:border-primary dark:focus:border-primary-dark focus:ring-primary dark:focus:ring-primary-dark rounded-md shadow-sm']) }}>

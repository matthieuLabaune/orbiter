@props(['id', 'title' => null, 'maxWidth' => 'max-w-lg'])

<dialog id="{{ $id }}" {{ $attributes->merge(['class' => "rounded-xl bg-white dark:bg-slate-900 text-gray-900 dark:text-white p-6 border border-gray-200 dark:border-slate-700/50 backdrop:bg-black/40 dark:backdrop:bg-black/50 $maxWidth"]) }}>
    @if($title)
        <h2 class="text-lg font-bold mb-4">{{ $title }}</h2>
    @endif
    {{ $slot }}
    <form method="dialog" class="mt-4">
        <button class="text-gray-400 dark:text-slate-400 hover:text-gray-600 dark:hover:text-white text-sm cursor-pointer">Fermer</button>
    </form>
</dialog>

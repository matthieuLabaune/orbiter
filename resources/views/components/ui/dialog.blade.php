@props(['id', 'title' => null, 'maxWidth' => 'max-w-lg'])

<dialog id="{{ $id }}" {{ $attributes->merge(['class' => "rounded-xl bg-slate-900 text-white p-6 backdrop:bg-black/50 $maxWidth"]) }}>
    @if($title)
        <h2 class="text-lg font-bold mb-4">{{ $title }}</h2>
    @endif
    {{ $slot }}
    <form method="dialog" class="mt-4">
        <button class="text-slate-400 hover:text-white text-sm cursor-pointer">Fermer</button>
    </form>
</dialog>

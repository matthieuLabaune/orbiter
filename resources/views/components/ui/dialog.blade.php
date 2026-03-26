@props(['id', 'title' => null, 'maxWidth' => 'max-w-lg'])

<dialog id="{{ $id }}" {{ $attributes->merge(['class' => "surface p-6 $maxWidth"]) }}>
    @if($title)
        <h2 class="text-lg font-bold mb-4" style="color: var(--o-text);">{{ $title }}</h2>
    @endif
    {{ $slot }}
    <form method="dialog" class="mt-4">
        <button class="text-sm cursor-pointer transition-opacity hover:opacity-70" style="color: var(--o-text-4);">Fermer</button>
    </form>
</dialog>

<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-300']) }}>
        {{ $slot }}
    </table>
</div>
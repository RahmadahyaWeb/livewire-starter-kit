@props(['title', 'description' => null, 'buttonLabel' => null, 'buttonHref' => null])

<div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <flux:heading size="xl">{{ $title }}</flux:heading>

        @if ($description)
            <flux:text class="mt-2">{{ $description }}</flux:text>
        @endif
    </div>

    @if ($buttonLabel && $buttonHref)
        <div class="w-full sm:w-auto">
            <flux:button :href="$buttonHref" variant="primary" class="w-full sm:w-auto">
                {{ $buttonLabel }}
            </flux:button>
        </div>
    @endif

</div>

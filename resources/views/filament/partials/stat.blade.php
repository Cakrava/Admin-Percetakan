<div {{ $attributes->class([
    'filament-widgets-stats-overview-widget-stat',
    'w-full' => $stat->isFullWidth(),
]) }}>
    <div class="p-6 bg-white rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">{{ $stat->getLabel() }}</h3>
                <p class="text-sm text-gray-500">{{ $stat->getDescription() }}</p>
            </div>
            @if ($stat->getIcon())
                <div class="text-3xl text-gray-400">
                    <i class="{{ $stat->getIcon() }}"></i>
                </div>
            @endif
        </div>
        <div class="mt-4">
            <p class="text-2xl font-bold text-gray-900">{{ $stat->getValue() }}</p>
        </div>
    </div>
</div>
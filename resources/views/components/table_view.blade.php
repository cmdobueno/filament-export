<input id="{{ $getStatePath() }}" type="hidden" {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}">

<x-filament::modal
    id="preview-modal"
    width="7xl"
    display-classes="block"
    :dark-mode="config('filament.dark_mode')"
    x-init="
        $wire.on('open-preview-modal-{{ $getUniqueActionId() }}',
            function() {
                triggerInputEvent('{{ $getStatePath() }}', '{{ $shouldRefresh() ? 'refresh' : '' }}');
                isOpen = true;
            });
        $wire.on('close-preview-modal-{{ $getUniqueActionId() }}', () => { isOpen = false; });"
    :heading="$getPreviewModalHeading()"
>
    <div class="preview-table-wrapper space-y-4">
        <table
            class="preview-table dark:bg-gray-800 dark:text-white dark:border-gray-700"
            x-init="$wire.on('print-table-{{ $getUniqueActionId() }}', function() {
                triggerInputEvent('{{ $getStatePath() }}', 'print-{{ $getUniqueActionId() }}')
            })"
        >
            <tr class="dark:border-gray-700">
                @foreach ($getAllColumns() as $column)
                    <th class="dark:border-gray-700">
                        {{ $column->getLabel() }}
                    </th>
                @endforeach
            </tr>
            @foreach ($getRows() as $row)
                <tr class="dark:border-gray-700">
                    @foreach ($getAllColumns() as $column)
                        <td class="dark:border-gray-700">
                            {{ $row[$column->getName()] }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
        <div>
            <x-filament::pagination :paginator="$getRows()" :records-per-page-select-options="10"/>
        </div>
    </div>
    <x-slot name="footer">
        <x-filament::button
            type="submit"
            icon="{{config('filament-export.export_icon')}}"
            color="primary"
        >
            Another Button?
        </x-filament::button>
    </x-slot>
</x-filament::modal>

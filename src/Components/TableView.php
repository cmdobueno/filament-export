<?php

namespace Cmdobueno\FilamentExport\Components;

use Cmdobueno\FilamentExport\Components\Concerns\HasUniqueActionId;
use Cmdobueno\FilamentExport\FilamentExport;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Concerns\HasName;
use Filament\Tables\Actions\Modal\Actions\Action;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TableView extends Component
{
    use HasName;
    use HasUniqueActionId;

    protected ?string $statePath = 'table_view';

    protected FilamentExport $export;

    protected $shouldRefresh = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->view('filament-export::components.table_view');
    }

    public static function make(string $name): static
    {
        $static = app(static::class);
        $static->name($name);
        $static->setUp();

        return $static;
    }

    public function export(FilamentExport $export): static
    {
        $this->export = $export;

        return $this;
    }

    public function getExport(): FilamentExport
    {
        return $this->export;
    }

    public function fileName(string $fileName): static
    {
        $this->getExport()->fileName($fileName);

        return $this;
    }

    public function getFileName(): string
    {
        return $this->getExport()->getFileName();
    }

    public function filteredColumns(array $columns): static
    {
        if (count($columns) == 0) {
            return $this;
        }

        $this->getExport()->filteredColumns($columns);

        return $this;
    }

    public function getFilteredColumns(): Collection
    {
        return $this->getExport()->getFilteredColumns();
    }

    public function additionalColumns(array $additionalColumns): static
    {
        if (count($additionalColumns) == 0) {
            return $this;
        }

        $this->getExport()->additionalColumns($additionalColumns);

        return $this;
    }

    public function getAdditionalColumns(): Collection
    {
        return $this->getExport()->getAdditionalColumns();
    }

    public function extraViewData(array $extraViewData): static
    {
        $this->getExport()->extraViewData($extraViewData);

        return $this;
    }

    public function getExtraViewData(): array
    {
        return $this->getExport()->getExtraViewData();
    }

    public function getAllColumns(): Collection
    {
        return $this->getExport()->getAllColumns();
    }

    public function getRows(): LengthAwarePaginator
    {
        $paginator = $this
            ->getExport()
            ->getPaginator();

        $paginator->getCollection()->transform(function ($row, $key) {
            $data = [];

            foreach ($this->getAllColumns() as $column) {
                if (is_array($row)) {
                    $data[$column->getName()] = data_get($row, $column->getName());

                    continue;
                }

                $data[$column->getName()] = FilamentExport::getColumnState($this->getExport()->getTable(), $column, $row, $key);
            }

            return $data;
        });

        return $paginator;
    }

    public function getExportAction(): StaticAction
    {
        $livewireCallActionName = null;
        
        if (method_exists($this, 'getLivewireSubmitActionName')) {
            $livewireCallActionName = $this->getLivewireSubmitActionName();
        } elseif (method_exists($this, 'getLivewireCallActionName')) {
            $livewireCallActionName = $this->getLivewireCallActionName();
        }
        
        return StaticAction::make('export')
            ->button()
            ->label(__('filament-export::table_view.export_action_label'))
            ->submit('export-form')
            ->icon(config('filament-export.export_icon'));
    }
    
    public function getFooterActions(): array
    {
        return [
            $this->getExportAction(),
        ];
    }

    public function getPreviewModalHeading(): string
    {
        return __('filament-export::table_view.preview_modal_heading');
    }

    public function data()
    {
        return array_merge(
            parent::data(),
            $this->getExport()->getExtraViewData()
        );
    }

    public function refresh(bool $condition): static
    {
        $this->shouldRefresh = $condition;

        return $this;
    }

    public function shouldRefresh(): bool
    {
        return $this->shouldRefresh;
    }
}

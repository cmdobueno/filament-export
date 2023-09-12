<?php

namespace Cmdobueno\FilamentExport\Actions;

use Cmdobueno\FilamentExport\Actions\Concerns\CanDisableAdditionalColumns;
use Cmdobueno\FilamentExport\Actions\Concerns\CanDisableFileName;
use Cmdobueno\FilamentExport\Actions\Concerns\CanDisableFileNamePrefix;
use Cmdobueno\FilamentExport\Actions\Concerns\CanDisableFilterColumns;
use Cmdobueno\FilamentExport\Actions\Concerns\CanDisableFormats;
use Cmdobueno\FilamentExport\Actions\Concerns\CanDisablePreview;
use Cmdobueno\FilamentExport\Actions\Concerns\CanDisableTableColumns;
use Cmdobueno\FilamentExport\Actions\Concerns\CanDownloadDirect;
use Cmdobueno\FilamentExport\Actions\Concerns\CanHaveExtraColumns;
use Cmdobueno\FilamentExport\Actions\Concerns\CanHaveExtraViewData;
use Cmdobueno\FilamentExport\Actions\Concerns\CanModifyWriters;
use Cmdobueno\FilamentExport\Actions\Concerns\CanRefreshTable;
use Cmdobueno\FilamentExport\Actions\Concerns\CanShowHiddenColumns;
use Cmdobueno\FilamentExport\Actions\Concerns\CanUseSnappy;
use Cmdobueno\FilamentExport\Actions\Concerns\HasAdditionalColumnsField;
use Cmdobueno\FilamentExport\Actions\Concerns\HasDefaultFormat;
use Cmdobueno\FilamentExport\Actions\Concerns\HasDefaultPageOrientation;
use Cmdobueno\FilamentExport\Actions\Concerns\HasExportModelActions;
use Cmdobueno\FilamentExport\Actions\Concerns\HasFileName;
use Cmdobueno\FilamentExport\Actions\Concerns\HasFileNameField;
use Cmdobueno\FilamentExport\Actions\Concerns\HasFilterColumnsField;
use Cmdobueno\FilamentExport\Actions\Concerns\HasFormatField;
use Cmdobueno\FilamentExport\Actions\Concerns\HasPageOrientationField;
use Cmdobueno\FilamentExport\Actions\Concerns\HasPaginator;
use Cmdobueno\FilamentExport\Actions\Concerns\HasTimeFormat;
use Cmdobueno\FilamentExport\Actions\Concerns\HasUniqueActionId;
use Cmdobueno\FilamentExport\Actions\Concerns\HasCsvDelimiter;
use Cmdobueno\FilamentExport\FilamentExport;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FilamentExportBulkAction extends \Filament\Tables\Actions\BulkAction
{
    use CanDisableAdditionalColumns;
    use CanDisableFileName;
    use CanDisableFileNamePrefix;
    use CanDisableFilterColumns;
    use CanDisableFormats;
    use CanDisablePreview;
    use CanDisableTableColumns;
    use CanDownloadDirect;
    use CanHaveExtraColumns;
    use CanHaveExtraViewData;
    use CanModifyWriters;
    use CanRefreshTable;
    use CanShowHiddenColumns;
    use CanUseSnappy;
    use HasAdditionalColumnsField;
    use HasCsvDelimiter;
    use HasDefaultFormat;
    use HasDefaultPageOrientation;
    use HasExportModelActions;
    use HasFileName;
    use HasFileNameField;
    use HasFilterColumnsField;
    use HasFormatField;
    use HasPageOrientationField;
    use HasPaginator;
    use HasTimeFormat;
    use HasUniqueActionId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uniqueActionId('bulk-action');

        FilamentExport::setUpFilamentExportAction($this);

        $this
            ->form(static function ($action, $records, $livewire): array {
                if ($action->shouldDownloadDirect()) {
                    return [];
                }

                $currentPage = LengthAwarePaginator::resolveCurrentPage('exportPage');

                $paginator = new LengthAwarePaginator($records->forPage($currentPage, $livewire->tableRecordsPerPage), $records->count(), $livewire->tableRecordsPerPage, $currentPage, [
                    'pageName' => 'exportPage',
                ]);

                $action->paginator($paginator);

                return FilamentExport::getFormComponents($action);
            })
            ->action(static function ($action, $records, $data): StreamedResponse {
                $action->fillDefaultData($data);

                return FilamentExport::callDownload($action, $records, $data);
            });
    }
}

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
use Cmdobueno\FilamentExport\Actions\Concerns\HasRecords;
use Cmdobueno\FilamentExport\Actions\Concerns\HasTimeFormat;
use Cmdobueno\FilamentExport\Actions\Concerns\HasUniqueActionId;
use Cmdobueno\FilamentExport\Actions\Concerns\HasCsvDelimiter;
use Cmdobueno\FilamentExport\FilamentExport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FilamentExportHeaderAction extends \Filament\Tables\Actions\Action
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
    use CanShowHiddenColumns;
    use CanUseSnappy;
    use CanRefreshTable;
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
    use HasRecords;
    use HasTimeFormat;
    use HasUniqueActionId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uniqueActionId('header-action');

        FilamentExport::setUpFilamentExportAction($this);

        $this
            ->form(static function ($action, $livewire): array {
                if ($action->shouldDownloadDirect()) {
                    return [];
                }

                $action->paginator($action->getTableQuery()->paginate($livewire->tableRecordsPerPage, ['*'], 'exportPage'));

                return FilamentExport::getFormComponents($action);
            })
            ->action(static function ($action, $data): StreamedResponse {
                $action->fillDefaultData($data);

                $records = $action->getRecords();

                return FilamentExport::callDownload($action, $records, $data);
            });
    }
}

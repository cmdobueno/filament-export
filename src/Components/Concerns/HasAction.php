<?php

namespace Cmdobueno\FilamentExport\Components\Concerns;

use Cmdobueno\FilamentExport\Actions\FilamentExportBulkAction;
use Cmdobueno\FilamentExport\Actions\FilamentExportHeaderAction;

trait HasAction
{
    protected FilamentExportBulkAction | FilamentExportHeaderAction $action;

    public function action(FilamentExportBulkAction | FilamentExportHeaderAction $action): static
    {
        $this->action = $action;

        $this->getExport()->fileName($this->getAction()->getFileName());

        $this->getExport()->table($this->getAction()->getTable());

        return $this;
    }

    public function getAction(): FilamentExportBulkAction | FilamentExportHeaderAction
    {
        return $this->action;
    }
}

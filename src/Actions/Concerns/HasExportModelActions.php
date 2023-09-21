<?php

namespace Cmdobueno\FilamentExport\Actions\Concerns;

use Filament\Actions\StaticAction;
use Filament\Tables\Actions\Modal\Actions\Action;

trait HasExportModelActions
{
    public function getPreviewAction(): array
    {
        return [];
        $uniqueActionId = $this->getUniqueActionId();

        return ! $this->isPreviewDisabled() ? [
            Action::make('preview')
                ->button()
                ->label(__('filament-export::export_action.preview_action_label'))
                ->color('success')
                ->icon(config('filament-export.preview_icon'))
                ->action("\$dispatch('open-preview-modal-{$uniqueActionId}')"),
        ] : [];
    }

    public function getExportModalActions(): array
    {
        $uniqueActionId = $this->getUniqueActionId();

        $livewireCallActionName = null;

        if (method_exists($this, 'getLivewireSubmitActionName')) {
            $livewireCallActionName = $this->getLivewireSubmitActionName();
        } elseif (method_exists($this, 'getLivewireCallActionName')) {
            $livewireCallActionName = $this->getLivewireCallActionName();
        }

        return array_merge(
            $this->getPreviewAction(),
            [
                StaticAction::make('submit')
                    ->button()
                    ->label('Submit Button')
                    ->submit($livewireCallActionName)
                    ->color($this->getColor() !== 'secondary' ? $this->getColor() : null)
                    ->icon(config('filament-export.export_icon')),
            ]
        );
    }
}

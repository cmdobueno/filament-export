<?php

namespace Cmdobueno\FilamentExport\Tests\Filament\Resources\UserResource\Pages;

use Cmdobueno\FilamentExport\Tests\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

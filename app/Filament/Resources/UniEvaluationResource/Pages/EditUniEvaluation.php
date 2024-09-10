<?php

namespace App\Filament\Resources\UniEvaluationResource\Pages;

use App\Filament\Resources\UniEvaluationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniEvaluation extends EditRecord
{
    protected static string $resource = UniEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

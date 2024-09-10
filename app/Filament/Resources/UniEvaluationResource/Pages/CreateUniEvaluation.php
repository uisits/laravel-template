<?php

namespace App\Filament\Resources\UniEvaluationResource\Pages;

use App\Filament\Resources\UniEvaluationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUniEvaluation extends CreateRecord
{
    protected static string $resource = UniEvaluationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}

<?php

namespace App\Filament\Resources\UniEvaluationResource\Pages;

use App\Filament\Resources\UniEvaluationResource;
use App\Models\Registration;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUniEvaluations extends ListRecords
{
    protected static string $resource = UniEvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => Registration::where('student_netid', auth()->user()->netid)
                    ->where('course_no', 'like', 'UNI%')
                    ->whereDoesntHave('uniEvaluation')
                    ->exists()
                )
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}

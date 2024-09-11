<?php

namespace App\Filament\Resources\EvaluationResource\Pages;

use App\Filament\Resources\EvaluationResource;
use App\Models\Registration;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvaluations extends ListRecords
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(fn () => Registration::where('student_netid', auth()->user()->netid)
                    ->where('course_no', 'not like', 'UNI%')
                    ->whereDoesntHave('evaluation')
                    ->exists()
                )
                ->icon('heroicon-s-plus-circle'),
        ];
    }
}

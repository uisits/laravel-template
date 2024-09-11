<?php

namespace App\Filament\Resources\SemesterResource\Pages;

use App\Filament\Resources\SemesterResource;
use App\Models\Registration;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditSemester extends EditRecord
{
    protected static string $resource = SemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * @throws \Exception
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            DB::beginTransaction();
            $record->update($data);
            Registration::where('semester', $record->semester)
                ->update([
                    'evaluation_start' => $record->evaluation_start,
                    'evaluation_end' => $record->evaluation_end,
                ]);
            DB::commit();
        }
        catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception('Could not update semester: ' . $exception->getMessage());
        }
        return $record;
    }
}

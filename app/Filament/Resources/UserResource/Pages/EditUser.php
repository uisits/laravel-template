<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use STS\FilamentImpersonate\Pages\Actions\Impersonate;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Impersonate::make()->record($this->getRecord()),
        ];
    }

    /**
     * Mutates the form data before saving by hashing the password.
     *
     * @param array $data The form data to be mutated.
     * @return array The mutated form data with the hashed password.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['password'] = Hash::make('P@ssw0rd');
        return $data;
    }
}

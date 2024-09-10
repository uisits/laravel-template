<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Ldap\User;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('ad')
                ->label('From AD')
                ->disabled(auth()->user()->hasRole('super-admin'))
                ->form([
                    TextInput::make('uin')
                        ->live()
                        ->numeric()
                        ->minLength(9)
                        ->maxLength(9)
                        ->required(fn (Get $get) => !$get('netid')),
                    TextInput::make('netid')
                        ->live()
                        ->required(fn (Get $get) => !$get('uin')),
                ])->action(function(array $data) {
                    $query = User::query();
                    if($data['netid']) {
                        $query = $query->where('cn', $data['netid']);
                    }

                    if($data['uin']) {
                        $query = $query->where('extensionattribute1', $data['uin']);
                    }
                    $result = $query->firstOrFail();
                    \App\Models\User::updateOrCreate(
                        ['uin' => $result->uin],
                        [
                            'first_name' => $result->first_name,
                            'last_name' => $result->last_name,
                            'name' => $result->full_name,
                            'email' => $result->email,
                            'netid' => $result->netid,
                            'uin' => $result->uin,
                            'password' => Hash::make('P@ssw0rd'),
                        ]
                    );
                })->after(function() {
                    Notification::make()
                        ->success()
                        ->title('Successfully created user!')
                        ->body('The user has been created in the database.');
                })
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make('P@ssw0rd');
        return $data;
    }
}

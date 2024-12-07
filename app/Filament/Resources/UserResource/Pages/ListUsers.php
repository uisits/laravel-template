<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus-circle'),
            Actions\Action::make('create_user_from_ad')
                ->label('Create User from AD')
                ->icon('heroicon-o-plus-circle')
                ->model(User::class)
                ->visible(fn () => auth()->user()->hasRole('super_admin'))
                ->form([
                    TextInput::make('uin')
                        ->requiredWithout('netid'),
                    TextInput::make('netid')
                        ->requiredWithout('uin'),
                ])
                ->action(function ($data) {
                    if (isset($data['netid'])) {
                        $adUser = \App\Ldap\User::where('cn', $data['netid'])->firstOrFail();
                    } else {
                        $adUser = \App\Ldap\User::where('extensionattribute1', $data['uin'])->firstOrFail();
                    }
                    User::updateOrCreate(
                        ['email' => $adUser->email],
                        [
                            'name' => $adUser->full_name,
                            'first_name' => $adUser->first_name,
                            'last_name' => $adUser->last_name,
                            'netid' => $adUser->netid,
                            'uin' => $adUser->uin,
                            'password' => Hash::make('P@ssw0rd'),
                        ]
                    );
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('User added')
                        ->body('The user has been created successfully.'),
                ),
        ];
    }
}

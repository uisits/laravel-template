<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Filament\Resources\RegistrationResource\RelationManagers;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        if(auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()->where('student_netid', auth()->user()->netid);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_firstname')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('student_lastname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('student_netid')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('course_no')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('course_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('instructor_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('instructor_uin')
                    ->required()
                    ->maxLength(9),
                Forms\Components\TextInput::make('instructor_netid')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('section_type')
                    ->required(),
                Forms\Components\Toggle::make('online')
                    ->required(),
                Forms\Components\TextInput::make('semester')
                    ->required()
                    ->maxLength(5),
                Forms\Components\DateTimePicker::make('evaluation_start'),
                Forms\Components\DateTimePicker::make('evaluation_end'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchable()
            ->columns([
                Tables\Columns\TextColumn::make('student_firstname')
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student_lastname')
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('student_netid')
                    ->visible(fn () => auth()->user()->hasRole('super_admin'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('course_no')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('course_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_uni_301')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('instructor_name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('instructor_uin')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('instructor_netid')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('section_type')
                    ->badge(),
                Tables\Columns\IconColumn::make('online')
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('semester')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('evaluation_start')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('evaluation_end')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            RegistrationResource\Widgets\RegistrationOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }
}

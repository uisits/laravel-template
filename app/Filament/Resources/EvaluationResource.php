<?php

namespace App\Filament\Resources;

use App\Enums\Evaluations\Question1Options;
use App\Enums\Evaluations\CourseElectiveOptions;
use App\Enums\Evaluations\Question5Options;
use App\Enums\Evaluations\Question6Options;
use App\Enums\Evaluations\Question3Options;
use App\Enums\Evaluations\Question4Options;
use App\Enums\Evaluations\Question7Options;
use App\Enums\Evaluations\SexOptions;
use App\Enums\Evaluations\Question8Options;
use App\Enums\Evaluations\Question2Options;
use App\Filament\Resources\EvaluationResource\Pages;
use App\Filament\Resources\EvaluationResource\RelationManagers;
use App\Models\Evaluation;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        if(auth()->user()->hasRole('super_admin')) {
            return parent::getEloquentQuery()
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]);
        }

        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('registration_id')
                    ->label('Please select the course you are evaluating')
                    ->options(function() {
                        return Registration::where('student_netid', auth()->user()?->netid)
                            ->doesntHave('evaluation')
                            ->pluck('course_name',  'id');
                    }),
                Forms\Components\Section::make()
                    ->columns([
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        Forms\Components\Select::make('answer_1')
                            ->label('1. Your current class standing')
                            ->options(Question1Options::class),
                        Forms\Components\Select::make('answer_2')
                            ->label('2. I consider myself a fair judge of this course')
                            ->options(Question2Options::class),
                        Forms\Components\Select::make('answer_3')
                            ->label('3. What is your impression about the amount of work required for this course?')
                            ->options(Question3Options::class),
                        Forms\Components\Select::make('answer_4')
                            ->label('4. What grade do you expect to get in this course?')
                            ->options(Question4Options::class),
                        Forms\Components\Select::make('answer_5')
                            ->label('5. This course is (check all that apply):')
                            ->multiple()
                            ->options(Question5Options::class),
                        Forms\Components\Select::make('answer_6')
                            ->label('6. This course has increased my skills in critical thinking')
                            ->options(Question6Options::class),
                        Forms\Components\Select::make('answer_7')
                            ->label('7. The instructor\'s presentation is well planned and organized')
                            ->options(Question7Options::class),
                        Forms\Components\Select::make('answer_8')
                            ->label('8. Do you think this teacher is competent in the content or material offered in this course')
                            ->options(Question8Options::class),
                        Forms\Components\Select::make('answer_9')
                            ->label('9. This course has motivated me to work at my highest level')
                            ->options(Question5Options::class),
                        Forms\Components\Select::make('answer_10')
                            ->label('10. Overall, how do you rate the quality of this person as a teacher')
                            ->options(Question5Options::class),
                    ]),
                Forms\Components\RichEditor::make('comment')
                    ->label('Comments for the course instructor’s use')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration.course_name')
                    ->description(fn ($record) => $record->registration?->course_no)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_1')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_3')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_4')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_5')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_6')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_7')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_8')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_9')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer_10')
                    ->numeric()
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
                Tables\Filters\TrashedFilter::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluations::route('/'),
            'create' => Pages\CreateEvaluation::route('/create'),
            'edit' => Pages\EditEvaluation::route('/{record}/edit'),
        ];
    }
}

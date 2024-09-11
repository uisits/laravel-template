<?php

namespace App\Filament\Resources;

use App\Enums\Evaluations\Question1Options;
use App\Enums\Evaluations\Question2Options;
use App\Enums\Evaluations\Question3Options;
use App\Enums\Evaluations\Question4Options;
use App\Enums\Evaluations\Question5Options;
use App\Enums\Evaluations\Question6Options;
use App\Enums\EvaluationType;
use App\Filament\Resources\EvaluationResource\Pages;
use App\Filament\Resources\EvaluationResource\RelationManagers;
use App\Models\Evaluation;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static array $options = [
        '1' => 'Strongly Disagree.',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => 'Strongly Agree.',
    ];

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
            ->whereHas('registration', function (Builder $query) {
                $query->where('student_netid', auth()->user()->netid)
                    ->where('course_no', 'not like', 'UNI%');
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('registration_id')
                    ->label('Please select the course you are evaluating')
                    ->visibleOn('create')
                    ->relationship(
                        'registration',
                        'course_name',
                        modifyQueryUsing: fn (Builder $query): Builder => $query->where('student_netid', auth()->user()?->netid)
                            ->doesntHave('evaluation')
                    )
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->course_name} - {$record->course_no} {$record->instructor_name}"),
                Forms\Components\Fieldset::make('Course')
                    ->label('Course')
                    ->disabled()
                    ->relationship('registration')
                    ->hiddenOn('create')
                    ->schema([
                        TextInput::make('semester'),
                        TextInput::make('course_name'),
                        TextInput::make('course_no'),
                        TextInput::make('instructor_name'),
                        TextInput::make('instructor_netid'),
                        TextInput::make('instructor_uin'),
                        TextInput::make('section_type'),
                        Forms\Components\Radio::make('online')
                            ->boolean(),
                    ]),
                Forms\Components\Section::make('Evaluation')
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
                        Forms\Components\CheckboxList::make('answer_5')
                            ->label('5. This course is (check all that apply):')
                            ->options(Question5Options::class),
                        Forms\Components\Section::make('Thinking about your class and your instructor, please respond to statements 6-14 on a 1-5 scale with 1 indicating “Strongly Disagree” and 5 indicating “Strongly Agree".')
                            ->schema([
                                Forms\Components\Radio::make('answer_6')
                                    ->label('6. This course has increased my skills in critical thinking')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                                Forms\Components\Radio::make('answer_7')
                                    ->label('7. The instructor\'s presentation is well planned and organized')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                                Forms\Components\Radio::make('answer_8')
                                    ->label('8. Do you think this teacher is competent in the content or material offered in this course')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                                Forms\Components\Radio::make('answer_9')
                                    ->label('9. This course has motivated me to work at my highest level')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                                Forms\Components\Radio::make('answer_10')
                                    ->label('10. Overall, how do you rate the quality of this person as a teacher')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                                Forms\Components\Radio::make('answer_11')
                                    ->label('11. Overall, how do you rate the quality of this person as a teacher')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                                Forms\Components\Radio::make('answer_12')
                                    ->label('12. Overall, how do you rate the quality of this person as a teacher')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                                Forms\Components\Radio::make('answer_13')
                                    ->label('13. Overall, how do you rate the quality of this person as a teacher')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                                Forms\Components\Radio::make('answer_14')
                                    ->label('14. Overall, how do you rate the quality of this person as a teacher')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                                Forms\Components\Radio::make('answer_15')
                                    ->label('15. Overall, this course was of high quality.')
                                    ->inline()
                                    ->options(Question6Options::class)
                                    ->descriptions(self::$options),
                            ]),
                        Forms\Components\Section::make('Use the space below to provide constructive comments:')
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\RichEditor::make('answer_16')
                                    ->columnSpanFull()
                                    ->label('16. hat has been the most beneficial knowledge or skill you have learned in this course?'),
                                Forms\Components\RichEditor::make('answer_17')
                                    ->columnSpanFull()
                                    ->label('17. What are your instructor’s greatest strengths?'),
                                Forms\Components\RichEditor::make('answer_18')
                                    ->columnSpanFull()
                                    ->label('18. What suggestions do you have for your instructor to improve your learning in this course?'),
                                Forms\Components\RichEditor::make('answer_19')
                                    ->columnSpanFull()
                                    ->label('19. Do you have anything else you would like to add regarding this course or instructor?'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchable()
            ->columns([
                Tables\Columns\TextColumn::make('registration.semester')
                    ->label('Semester')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration.course_name')
                    ->label('Course Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration.course_no')
                    ->label('Course No')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration.instructor_name')
                    ->label('Instructor Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration.instructor_netid')
                    ->label('Instructor Netid')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration.section_type')
                    ->label('Section Type'),
                Tables\Columns\IconColumn::make('registration.online')
                    ->boolean()
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        '0' => 'heroicon-o-x-circle',
                    })
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->label('Online?'),
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
                Tables\Filters\TrashedFilter::make()
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

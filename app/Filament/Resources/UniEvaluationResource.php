<?php

namespace App\Filament\Resources;

use App\Enums\Uni\Question1Options;
use App\Enums\Uni\Question2Options;
use App\Enums\Uni\Question3Options;
use App\Enums\Uni\Question4Options;
use App\Enums\Uni\Question5Options;
use App\Enums\Uni\Question6Options;
use App\Enums\Uni\Question7Options;
use App\Enums\Uni\Question8Options;
use App\Enums\Uni\Question9Options;
use App\Enums\Uni\Question10Options;
use App\Enums\Uni\Question12Options;
use App\Filament\Resources\UniEvaluationResource\Pages;
use App\Filament\Resources\UniEvaluationResource\RelationManagers;
use App\Models\UniEvaluation;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UniEvaluationResource extends Resource
{
    protected static ?string $model = UniEvaluation::class;

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
            ->whereHas('registration', function (Builder $query) {
                $query->where('student_netid', auth()->user()->netid)
                    ->where('course_no', 'like', 'UNI%');
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
                            ->where('course_no', 'like', 'UNI%')
                            ->doesntHave('uniEvaluation')
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
                            ->label('2. Your Sex')
                            ->options(Question2Options::class),
                        Forms\Components\Select::make('answer_3')
                            ->label('3. Grade you expect to receive in this class:')
                            ->options(Question3Options::class),
                        Forms\Components\Select::make('answer_4')
                            ->label('4. To what extent was your Course Facilitator timely in carrying out the responsibilities of the course (i.e. responding to emails, giving feedback and/or grading discussion posts, etc.)?')
                            ->options(Question4Options::class),
                        Forms\Components\Select::make('answer_5')
                            ->label('5. To what extent was your Course Facilitator’s feedback on assignments or evaluation of your performance helpful?')
                            ->options(Question5Options::class),
                        Forms\Components\Select::make('answer_6')
                            ->label('6. To what extent was your Course Facilitator helpful in answering questions about the course?')
                            ->options(Question6Options::class),
                        Forms\Components\Select::make('answer_7')
                            ->label('7. To what extent did your Course Facilitator effectively direct student discussions on Canvas and/or in the classroom?')
                            ->options(Question7Options::class),
                        Forms\Components\Select::make('answer_8')
                            ->label('8. Overall, how do you rate the quality of your UNI 301 Course Facilitator?')
                            ->options(Question8Options::class),
                        Forms\Components\Select::make('answer_9')
                            ->label('9. To what extent did you discuss topics in this course with your fellow students (in person and/or on Canvas )')
                            ->options(Question9Options::class),
                        Forms\Components\Select::make('answer_10')
                            ->label('10. To what extent has this course challenged you to consider multiple perspectives?')
                            ->options(Question10Options::class),
                        Forms\Components\Select::make('answer_11')
                            ->label('11. To what extent has this course increased your critical thinking skills?')
                            ->options(Question10Options::class),
                        Forms\Components\Section::make('Please rate the extent to which the events you attended have helped you to:')
                            ->columns([
                                'md' => 2
                            ])
                            ->schema([
                                Forms\Components\Select::make('answer_12_1')
                                    ->label('A. Recognize the social responsibility of the individual within a larger community.')
                                    ->options(Question12Options::class),
                                Forms\Components\Select::make('answer_12_2')
                                    ->label('B. Practice awareness and respect for the diverse cultures and peoples in this country and in the world.')
                                    ->options(Question12Options::class),
                                Forms\Components\Select::make('answer_12_3')
                                    ->label('C. Reflect on the ways involvement, leadership, and respect for community occur at the local, regional, national, or international levels.')
                                    ->options(Question12Options::class),
                                Forms\Components\Select::make('answer_12_4')
                                    ->label('D. Identify how social systems such as economic, political, and legal systems may impact individual development?')
                                    ->options(Question12Options::class),
                                Forms\Components\Select::make('answer_12_5')
                                    ->label('E. Engage in more open-minded and ethical decision-making and action.')
                                    ->options(Question12Options::class),
                                Forms\Components\Select::make('answer_12_6')
                                    ->label('F. Distinguish the possibilities and limitations of social change.')
                                    ->options(Question12Options::class),
                            ]),
                        Forms\Components\RichEditor::make('answer_13')
                            ->columnSpanFull()
                            ->label('13. What did you find most enlightening about this course?'),
                        Forms\Components\RichEditor::make('answer_14')
                            ->columnSpanFull()
                            ->label('14. What did you find most challenging about this course?'),
                        Forms\Components\RichEditor::make('answer_15')
                            ->columnSpanFull()
                            ->label('15. Please share any other comments on the ECCE Speaker Series course.'),
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
            'index' => Pages\ListUniEvaluations::route('/'),
            'create' => Pages\CreateUniEvaluation::route('/create'),
            'edit' => Pages\EditUniEvaluation::route('/{record}/edit'),
        ];
    }
}

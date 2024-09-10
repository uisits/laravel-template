<?php

namespace App\Filament\Resources;

use App\Enums\Uni\Question12Options;
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
use App\Filament\Resources\UniEvaluationResource\Pages;
use App\Filament\Resources\UniEvaluationResource\RelationManagers;
use App\Models\Registration;
use App\Models\UniEvaluation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
                            ->where('course_no', 'like', 'UNI%')
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
                Forms\Components\RichEditor::make('comment')
                    ->label('Comments for the course instructor’s use')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('registration.course_name'),
                Tables\Columns\TextColumn::make('registration.course_no'),
                Tables\Columns\TextColumn::make('registration.semester'),
                Tables\Columns\TextColumn::make('registration.instructor_name'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUniEvaluations::route('/'),
            'create' => Pages\CreateUniEvaluation::route('/create'),
            'edit' => Pages\EditUniEvaluation::route('/{record}/edit'),
        ];
    }
}

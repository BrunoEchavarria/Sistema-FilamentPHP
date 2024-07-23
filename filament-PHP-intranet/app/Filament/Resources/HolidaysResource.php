<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HolidaysResource\Pages;
use App\Filament\Resources\HolidaysResource\RelationManagers;
use App\Models\Holiday;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HolidaysResource extends Resource
{
    protected static ?string $model = Holiday::class;
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('calendar_id')
                ->required()
                ->relationship(name: 'calendar', titleAttribute: 'name'),
                Forms\Components\Select::make('user_id')
                ->required()
                ->relationship(name: 'user', titleAttribute: 'name'),
                Forms\Components\Select::make('type')
                ->required()
                ->options([
                    'decline' => 'Decline',
                    'approved' => 'Approved',
                    'pending' => 'Pending'
                ]),
                Forms\Components\DatePicker::make('day')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('calendar.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('day')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('type')
                ->badge()
                ->color(fn (string $state): string => match ($state){
                    'pending' => 'gray',
                    'approved' => 'success',
                    'decline' => 'danger'
                })
                ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                //
                SelectFilter::make('type')
                ->options([
                    'decline' => 'Decline',
                    'approved' => 'Approved',
                    'pending' => 'Pending'
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHolidays::route('/create'),
            'edit' => Pages\EditHolidays::route('/{record}/edit'),
        ];
    }
}

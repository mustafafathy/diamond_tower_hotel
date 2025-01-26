<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AvailableRoomResource\Pages;
use App\Filament\Resources\AvailableRoomResource\RelationManagers;
use App\Models\AvailableRoom;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvailableRoomResource extends Resource
{
    protected static ?string $navigationGroup = 'Hotel';
    protected static ?int $navigationSort = 15;
    protected static ?string $label = 'Rooms Availability';

    protected static ?string $model = AvailableRoom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('room_id')
                    ->label('Room')
                    ->options(function () {
                        return Room::all()->pluck('name_en', 'id')->toArray();
                    })
                    ->required()
                    ->helperText('Select a room for availability.')
                    ->searchable(),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->helperText('Choose the date the room will be available.')
                    ->label('Availability Date'),
                Forms\Components\TextInput::make('available')
                    ->required()
                    ->numeric()
                    ->helperText('Number of available rooms for this date.')
                    ->label('Available Rooms'),
                Forms\Components\TextInput::make('booked')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->helperText('Number of rooms already booked.')
                    ->label('Rooms Booked'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->helperText('Price per room for the selected date.')
                    ->label('Room Price'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('room_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('available')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booked')
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
            'index' => Pages\ListAvailableRooms::route('/'),
            'create' => Pages\CreateAvailableRoom::route('/create'),
            'edit' => Pages\EditAvailableRoom::route('/{record}/edit'),
        ];
    }
}

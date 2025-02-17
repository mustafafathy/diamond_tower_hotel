<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SecondarySliderResource\Pages;
use App\Filament\Resources\SecondarySliderResource\RelationManagers;
use App\Models\SecondarySlider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SecondarySliderResource extends Resource
{
    protected static ?string $model = SecondarySlider::class;

    // protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Slider';
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->label('Image ')
                    ->image()
                    ->directory('images/secondarySlider')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/*'])
                    ->required(),
                Forms\Components\TextInput::make('order')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('order')
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
            'index' => Pages\ListSecondarySliders::route('/'),
            'create' => Pages\CreateSecondarySlider::route('/create'),
            'edit' => Pages\EditSecondarySlider::route('/{record}/edit'),
        ];
    }
}

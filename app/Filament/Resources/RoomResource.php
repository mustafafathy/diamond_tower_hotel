<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Doctrine\DBAL\Schema\Schema;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Name')->schema([
                    Forms\Components\TextInput::make('name_en')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('name_ar')
                        ->required()
                        ->maxLength(255)
                        ->extraAttributes(['style' => 'direction:rtl']),
                ])->columns(2),

                Section::make('Description')->schema([
                    Forms\Components\Textarea::make('description_en')
                        ->label('Description English')
                        ->required()
                        ->maxLength(65535),
                    Forms\Components\Textarea::make('description_ar')
                        ->label('Description Arabic')
                        ->required()
                        ->maxLength(65535)
                        ->extraAttributes(['style' => 'direction:rtl']),
                ])->columns(2),

                Section::make('Features')->schema([
                    Forms\Components\TextInput::make('space')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('allowed_persons')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('availability')
                        ->required()
                        ->numeric(),
                ])->columns(3),

                Section::make('Features')->schema([
                    Forms\Components\Toggle::make('view')
                        ->label('City View')
                        ->required(),
                    Forms\Components\Toggle::make('bathroom')
                        ->required(),
                    Forms\Components\Toggle::make('kitchen')
                        ->required(),
                    Forms\Components\Toggle::make('tv')
                        ->required(),
                    Forms\Components\Toggle::make('air_condition')
                        ->required(),
                    Forms\Components\Toggle::make('wifi')
                        ->required(),
                    Forms\Components\Toggle::make('smoke')
                        ->required(),
                    Forms\Components\Toggle::make('disabled')
                        ->required(),
                ])->columns(4),

                Section::make('Beds')->schema([
                    Forms\Components\TextInput::make('king_bed')
                        ->required()
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('single_bed')
                        ->required()
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('sofa_bed')
                        ->required()
                        ->numeric()
                        ->default(0),
                ])->columns(3),

                Section::make('Bathroom')->schema([
                    Repeater::make('bathroom_details_en')
                        ->label('Bathroom Details (English)')
                        ->schema([
                            TextInput::make('detail')
                                ->label('Detail')
                                ->required(),
                        ])
                        ->default([]),
                    Repeater::make('bathroom_details_ar')
                        ->label('Bathroom Details (Arabic)')
                        ->schema([
                            TextInput::make('detail')
                                ->label('Detail')
                                ->required()
                                ->extraAttributes(['style' => 'direction:rtl']),
                        ])
                        ->default([]),
                ])->columns(2),

                Section::make('تفاصيل المطبخ')->schema([
                    Repeater::make('kitchen_details_en')
                        ->label('Kitchen Details (English)')
                        ->schema([
                            TextInput::make('detail')
                                ->label('Detail')
                                ->required(),
                        ])
                        ->default([]),
                    Repeater::make('kitchen_details_ar')
                        ->label('Kitchen Details (Arabic)')
                        ->schema([
                            TextInput::make('detail')
                                ->label('Detail')
                                ->required()
                                ->extraAttributes(['style' => 'direction:rtl']),
                        ])
                        ->default([]),
                ])->columns(2),

                Section::make('تجهيزات الغرفة')->schema([
                    Repeater::make('preparations_en')
                        ->label('Preparation Details (English)')
                        ->schema([
                            TextInput::make('detail')
                                ->label('Detail')
                                ->required(),
                        ])
                        ->default([]),
                    Repeater::make('preparations_ar')
                        ->label('Preparation Details (Arabic)')
                        ->schema([
                            TextInput::make('detail')
                                ->label('Detail')
                                ->required()
                                ->extraAttributes(['style' => 'direction:rtl']),
                        ])
                        ->default([]),
                ])->columns(2),


                Section::make('الوسائط والتكنولوجيا')->schema([
                    Repeater::make('media_tech_en')
                        ->label('Media&Tech Details (English)')
                        ->schema([
                            TextInput::make('detail')
                                ->label('Detail')
                                ->required(),
                        ])
                        ->default([]),
                    Repeater::make('media_tech_ar')
                        ->label('Media&Tech Details (Arabic)')
                        ->schema([
                            TextInput::make('detail')
                                ->label('Detail')
                                ->required()
                                ->extraAttributes(['style' => 'direction:rtl']),
                        ])
                        ->default([]),
                ])->columns(2),

                Section::make('Images')->schema([
                    Forms\Components\FileUpload::make('image')
                        ->image()
                        ->label('Main Image')
                        ->acceptedFileTypes(['image/*'])
                        ->directory('images/main')
                        ->visibility('public')
                        ->required(),
                    Forms\Components\FileUpload::make('alt_images')
                        ->label('Alt Images')
                        ->image()
                        ->multiple()
                        ->acceptedFileTypes(['image/*'])
                        ->directory('images/alt')
                        ->visibility('public')
                        ->required(),
                ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('space')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('allowed_persons')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('availability')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('view')
                    ->label('City View')
                    ->boolean(),
                Tables\Columns\IconColumn::make('bathroom')
                    ->boolean(),
                Tables\Columns\IconColumn::make('kitchen')
                    ->boolean(),
                Tables\Columns\IconColumn::make('tv')
                    ->boolean(),
                Tables\Columns\IconColumn::make('air_condition')
                    ->boolean(),
                Tables\Columns\IconColumn::make('wifi')
                    ->boolean(),
                Tables\Columns\IconColumn::make('smoke')
                    ->boolean(),
                Tables\Columns\IconColumn::make('disabled')
                    ->boolean(),
                Tables\Columns\TextColumn::make('king_bed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('single_bed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sofa_bed')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image'),
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteDataResource\Pages;
use App\Filament\Resources\WebsiteDataResource\RelationManagers;
use App\Models\WebsiteData;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WebsiteDataResource extends Resource
{
    protected static ?string $model = WebsiteData::class;
    protected static ?string $navigationGroup = 'Website';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rooms_count')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('phone_num1')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone_num2')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('address_en')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address_ar')
                            ->required()
                            ->maxLength(255)
                            ->extraAttributes(['style' => 'direction:rtl']),
                    ])->columns(2),
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('google_maps_url')
                            ->label('Google Maps URL')
                            ->required()
                            ->reactive() // Make it reactive to trigger the extraction
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Extract coordinates once the URL is updated
                                if ($state) {
                                    $coordinates = self::extractCoordinatesFromUrl($state);
                                    if ($coordinates) {
                                        $set('latitude', $coordinates['latitude']);
                                        $set('longitude', $coordinates['longitude']);
                                    }
                                }
                            })
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('latitude')
                            ->required()
                            ->readonly()
                            ->numeric(),
                        Forms\Components\TextInput::make('longitude')
                            ->required()
                            ->readonly()
                            ->numeric(),
                    ])->columns(2),
                Forms\Components\TextInput::make('instagram_link')
                    ->required()
                    ->maxLength(255),
                Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('image_1')
                            ->label('Image 1')
                            ->image()
                            ->directory('images/website_data')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/*'])
                            ->required(),
                        Forms\Components\FileUpload::make('image_2')
                            ->label('Image 2')
                            ->image()
                            ->directory('images/website_data')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/*'])
                            ->required(),
                        Forms\Components\FileUpload::make('image_3')
                            ->label('Image 3')
                            ->image()
                            ->directory('images/website_data')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/*'])
                            ->required(),
                        Forms\Components\FileUpload::make('image_4')
                            ->label('Image 4')
                            ->image()
                            ->directory('images/website_data')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/*'])
                            ->required(),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rooms_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_num1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_num2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('instagram_link')
                    ->searchable(),
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
            'index' => Pages\ListWebsiteData::route('/'),
            // 'create' => Pages\CreateWebsiteData::route('/create'),
            'edit' => Pages\EditWebsiteData::route('/{record}/edit'),
        ];
    }

    public static function extractAddressFromUrl($url)
    {
        // Parse the URL to get the query parameters
        $urlParts = parse_url($url);

        if (isset($urlParts['query'])) {
            // Parse the query string into an associative array
            parse_str($urlParts['query'], $queryParams);

            // Return the value of 'q' parameter which contains the address
            if (isset($queryParams['q'])) {
                return urldecode($queryParams['q']); // Decode the URL-encoded address
            }
        }

        return null; // Return null if 'q' parameter is not found
    }

    public static function extractCoordinatesFromUrl($url)
    {
        if (strpos($url, 'maps.app.goo.gl') !== false) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
            curl_exec($ch);
            $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);

            $address = self::extractAddressFromUrl($finalUrl);

            $apiKey = 'AIzaSyDQ-KjfF-16mlmAqTIC5TiwQ3wVn5ZcabE';
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

            $response = file_get_contents($url);
            $data = json_decode($response, true);
            if (isset($data['results'][0]['geometry']['location'])) {
                $latitude = $data['results'][0]['geometry']['location']['lat'];
                $longitude = $data['results'][0]['geometry']['location']['lng'];

                return [
                    'latitude' => $latitude,
                    'longitude' => $longitude
                ];
            } else {
                return null;
            }
        }

        return null;
    }
}

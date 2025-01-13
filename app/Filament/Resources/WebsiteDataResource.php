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
                                    $coordinates = self::extractCoordinatesFromShortUrl($state);
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

    public static function resolveGoogleMapsUrl($shortUrl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $shortUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_exec($ch);
        $resolvedUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        return $resolvedUrl ?: null;
    }

    public static function extractCoordinatesFromUrl($url)
    {
        // Check for embedded coordinates in the URL
        if (preg_match('/@([-.\d]+),([-.\d]+)/', $url, $matches)) {
            return [
                'latitude' => $matches[1],
                'longitude' => $matches[2],
            ];
        }

        if (preg_match('/place\/([-.\d]+),([-.\d]+)/', $url, $matches)) {
            return [
                'latitude' => $matches[1],
                'longitude' => $matches[2],
            ];
        }

        return null;
    }

    public static function extractAddressFromUrl($url)
    {
        $urlParts = parse_url($url);

        if (isset($urlParts['query'])) {
            parse_str($urlParts['query'], $queryParams);
            if (isset($queryParams['q'])) {
                return urldecode($queryParams['q']); // Decode URL-encoded address
            }
        }

        return null;
    }

    public static function getCoordinatesFromAddress($address)
    {
        $apiKey = 'YOUR_GOOGLE_MAPS_API_KEY';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key=" . $apiKey;

        $response = file_get_contents($url);

        if (!$response) {
            return ['error' => 'Failed to fetch data from Google API.'];
        }

        $data = json_decode($response, true);

        if (isset($data['results'][0]['geometry']['location'])) {
            return [
                'latitude' => $data['results'][0]['geometry']['location']['lat'],
                'longitude' => $data['results'][0]['geometry']['location']['lng'],
            ];
        }

        return ['error' => 'No valid coordinates found for the given address.'];
    }

    public static function extractCoordinatesFromShortUrl($shortUrl)
    {
        $resolvedUrl = self::resolveGoogleMapsUrl($shortUrl);

        if (!$resolvedUrl) {
            return ['error' => 'Unable to resolve the shortened URL.'];
        }

        // Try extracting coordinates directly
        $coordinates = self::extractCoordinatesFromUrl($resolvedUrl);
        if ($coordinates) {
            return $coordinates;
        }

        // Fallback: Extract address and use API
        $address = self::extractAddressFromUrl($resolvedUrl);
        if (!$address) {
            return ['error' => 'No valid address found in the resolved URL.'];
        }

        return self::getCoordinatesFromAddress($address);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationalClearanceResource\Pages;
use App\Filament\Resources\LocationalClearanceResource\RelationManagers;
use App\Models\Barangays;
use App\Models\CityMunicipality;
use App\Models\LocationalClearance;
use App\Models\Province;
use App\Models\Region;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class LocationalClearanceResource extends Resource
{
    protected static ?string $model = LocationalClearance::class;
    protected static ?string $navigationIcon = 'govicon-building';
    protected static ?string $navigationLabel = 'Locational Clearance';
    protected static ?string $label = 'Locational Clearance';
    protected static ?string $slug = 'LocationalClearance';

    //    protected static ?string $navigationParentItem = 'Dashboard';

    protected static ?string $navigationGroup = 'Planning Office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Location')
                    ->icon('heroicon-s-document-text')
                    ->schema([
                        Select::make('region_id')
                            ->label('Region')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->default('6')
                            ->options(Region::orderBy('name', 'ASC')
                                ->pluck('name', 'id')
                            )
                            ->afterStateUpdated(fn(Set $set) => $set('province_id', null))
                            ->afterStateUpdated(fn(Set $set) => $set('city_municipalities_id', null))
                        ,

                        Select::make('province_id')
                            ->label('Province')
                            ->preload()
                            ->live()
                            ->options(fn(Get $get): Collection => Province::query()
                                ->where('region_id', $get('region_id'))
                                ->orderBy('name', 'asc')
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->afterStateUpdated(function (Set $set) {
                                $set('city_municipalities_id', null);
                            })
                            ->default('27')
                        ,

                        Select::make('city_municipalities_id')
                            ->label('City/Municipality')
                            ->preload()
                            ->live()
                            ->options(fn(Get $get): Collection => CityMunicipality::query()
                                ->where('province_id', $get('province_id'))
                                ->orderBy('name', 'asc')
                                ->pluck('name', 'id'))
                            ->searchable()
                        ,



                        TextInput::make('PSGC_Code')
                            ->label('PSGC Code')
                            ->disabled()
                    ])
                    ->columns(3),


                Section::make('Information')
                    ->icon('govicon-user')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('lot_number'),
                        TextInput::make('block_number'),
                        TextInput::make('street_number'),
                        TextInput::make('street_name'),

                        Select::make('subdivision_id'),
                        //                    ->options(Subdivisions::pluck('name', 'id')),
                        Select::make('barangay_id')
                            ->options(Barangays::orderBy('name', 'ASC')
                                ->pluck('name', 'id')),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([


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
            'index' => Pages\ListLocationalClearances::route('/'),
            'create' => Pages\CreateLocationalClearance::route('/create'),
            'edit' => Pages\EditLocationalClearance::route('/{record}/edit'),
        ];
    }
}

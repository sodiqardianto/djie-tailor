<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderInResource\Pages;
use App\Filament\Resources\OrderInResource\RelationManagers;
use App\Models\Model;
use App\Models\ModelType;
use App\Models\OrderIn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderInResource extends Resource
{
    protected static ?string $model = OrderIn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('model_type_id')
                    ->label('Model')
                    ->options(function () {
                        return ModelType::with('category')
                            ->get()
                            ->mapWithKeys(function ($modelType) {
                                return [$modelType->id => $modelType->category->name . ' ' . $modelType->name];
                            });
                    })
                    ->required(),                        
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model_type_id')
                ->label('Model')
                ->getStateUsing(function (OrderIn $record) {
                    return ModelType::find($record->model_type_id)->category->name . ' ' . ModelType::find($record->model_type_id)->name;
                })
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListOrderIns::route('/'),
            'create' => Pages\CreateOrderIn::route('/create'),
            'edit' => Pages\EditOrderIn::route('/{record}/edit'),
        ];
    }
}

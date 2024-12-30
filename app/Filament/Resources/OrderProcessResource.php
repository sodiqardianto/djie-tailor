<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderProcessResource\Pages;
use App\Filament\Resources\OrderProcessResource\RelationManagers;
use App\Models\OrderProcess;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderProcessResource extends Resource
{
    protected static ?string $model = OrderProcess::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_in_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('process_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('orderIn.name')
                    ->label('Nama')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pekerja')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('process.name')
                    ->label('Proses')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderProcesses::route('/'),
            // 'create' => Pages\CreateOrderProcess::route('/create'),
            // 'edit' => Pages\EditOrderProcess::route('/{record}/edit'),
        ];
    }
}

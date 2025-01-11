<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderInResource\Pages;
use App\Filament\Resources\OrderInResource\RelationManagers;
use App\Models\Collar;
use App\Models\OrderIn;
use App\Models\OrderProcess;
use App\Models\ShirtModel;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class OrderInResource extends Resource
{
    protected static ?string $model = OrderIn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Select::make('size_id')
                    ->relationship('size', 'name')
                    ->required(),
                DateTimePicker::make('deadline')
                    ->required(),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('model_type_id')
                    ->relationship('modelType', 'name')
                    ->required(),
                FileUpload::make('image')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('modelType.name')
                    ->label('Tipe Model')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('size.name')
                    ->label('Ukuran')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cut_quantity')
                    ->label('Sudah Dipotong')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return OrderProcess::where('order_in_id', $record->id)
                            ->where('process_id', 1)
                            ->sum('quantity');
                    }),
                TextColumn::make('sewing_quantity')
                    ->label('Sudah Dijahit')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return OrderProcess::where('order_in_id', $record->id)
                            ->where('process_id', 2)
                            ->sum('quantity');
                    }),
                TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('deadline')
                    ->label('Tenggat Waktu')
                    ->dateTime('d M Y')
                    ->sortable(),
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Tanggal Dihapus')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('Potong')
                        ->form([
                            Wizard::make([
                                Step::make('Potong')
                                    ->description('Pilih pemotong dan masukkan jumlah yang dipotong.')
                                    ->schema([
                                        Select::make('user_id')
                                            ->relationship('user', 'name', fn (Builder $query) => $query->where('name', '!=', 'superadmin'))
                                            ->label('Pemotong')
                                            ->placeholder('Pilih Pemotong')
                                            ->required(),
                                    ]),
                                Step::make('Kerah')
                                    ->description('Pilih model kerah yang akan diinginkan.')
                                    ->schema([
                                        Radio::make('collar_model')
                                        ->view('components.collar-radio-options')
                                        ->required(),
                                    ]),
                                Step::make('Plaket')
                                    ->description('Pilih model plaket yang akan diinginkan.')
                                    ->schema([
                                        Radio::make('placket_model')
                                        // ->options(function () {
                                        //     return \App\Models\Placket::all()
                                        //         ->pluck('id');
                                        // })
                                        // ->afterStateHydrated(function ($set, $state) {
                                        //     $set('placket_model', $state);
                                        // })
                                        ->view('components.placket-radio-options')
                                        ->required(),
                                    ]),
                            ])
                        ])
                        ->icon('heroicon-o-scissors')
                        ->visible(function (OrderIn $record) {
                            // Count the sum of quantity that has been processed
                            $sumQuantity = OrderProcess::where('order_in_id', $record->id)
                                ->where('process_id', 1)
                                ->sum('quantity');
            
                            // Check if the quantity is more than the available quantity
                            $remainingQuantity = $record->quantity - $sumQuantity;
            
                            // Show the action if the remaining quantity is more than 0
                            return $remainingQuantity > 0;
                        })
                        ->action(function (OrderIn $record, array $data) {

                            ShirtModel::create([
                                'collar_id' => $data['collar_model'],
                                'placket_id' => $data['placket_model'],
                            ]);

                            OrderProcess::create([
                                'order_in_id' => $record->id,
                                'user_id' => $data['user_id'],
                                'quantity' => 1,
                                'process_id' => 1,
                            ]);

                            Notification::make()
                                ->title('Berhasil simpan')
                                ->body('Data potong berhasil disimpan.')
                                ->success()
                                ->send();
                    }),
                    Action::make('Jahit')
                        ->form([
                            Select::make('user_id')
                                ->relationship('user', 'name', fn (Builder $query) => $query->where('name', '!=', 'superadmin'))
                                ->label('Penjahit')
                                ->placeholder('Pilih Penjahit')
                                ->required(),
                            TextInput::make('quantity')
                                ->label('Jumlah')
                                ->required()
                                ->numeric(),
                        ])
                        ->icon('heroicon-o-cog')
                        ->visible(function (OrderIn $record) {
                            // Check if there is any OrderProcess with process_id = 2
                            $exists = OrderProcess::where('order_in_id', $record->id)
                                ->where('process_id', 1)
                                ->exists();

                            if (!$exists) {
                                return false;
                            }

                            // Count the sum of quantity that has been processed
                            $sumQuantity = OrderProcess::where('order_in_id', $record->id)
                                ->where('process_id', 2)
                                ->sum('quantity');
            
                            // Check if the quantity is more than the available quantity
                            $remainingQuantity = $record->quantity - $sumQuantity;
            
                            // Show the action if the remaining quantity is more than 0
                            return $remainingQuantity > 0;
                        })
                        ->action(function (OrderIn $record, array $data) {

                            // Check if the quantity is more than the available quantity
                            if ($record->quantity < $data['quantity']) {
                                Notification::make()
                                    ->title('Gagal simpan')
                                    ->body('Jumlah yang dimasukkan melebihi jumlah yang sudah dipotong.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Check if the quantity is more than the remaining quantity
                            $sumQuantitySewing = OrderProcess::where('order_in_id', $record->id)
                                ->where('process_id', 2)
                                ->sum('quantity');

                            // Check if the quantity is more than the available quantity
                            $remainingQuantity = $record->quantity - $sumQuantitySewing;

                            // Check if the quantity is more than the available quantity
                            if ($remainingQuantity < $data['quantity']) {
                                Notification::make()
                                    ->title('Gagal simpan')
                                    ->body('Jumlah yang dimasukkan melebihi sisa jumlah yang bisa diproses.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Get quantity that has been cut
                            $cutQuantity = OrderProcess::where('order_in_id', $record->id)
                                ->where('process_id', 1)
                                ->sum('quantity');

                            // Check if the quantity is more than the available quantity
                            if ($cutQuantity < $data['quantity']) {
                                Notification::make()
                                    ->title('Gagal simpan')
                                    ->body('Jumlah yang dimasukkan melebihi jumlah yang sudah dipotong.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Check if the quantity cut is more than the quantity sewing
                            if ($cutQuantity < $sumQuantitySewing + $data['quantity']) {
                                Notification::make()
                                    ->title('Gagal simpan')
                                    ->body('Jumlah yang dimasukkan melebihi jumlah yang sudah dipotong.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            OrderProcess::create([
                                'order_in_id' => $record->id,
                                'user_id' => $data['user_id'],
                                'quantity' => $data['quantity'],
                                'process_id' => 2,
                            ]);

                            Notification::make()
                                ->title('Berhasil simpan')
                                ->body('Data jahit berhasil disimpan.')
                                ->success()
                                ->send();
                    }),
                    DeleteAction::make(),
                ]),
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

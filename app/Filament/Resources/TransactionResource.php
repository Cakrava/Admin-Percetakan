<?php

namespace App\Filament\Resources;
use App\Http\Livewire\CustomerTableModal;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\Pages\ServiceFormTransaction;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Customer;
use App\Models\material;
use App\Models\service;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Text; // Impor komponen Text
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Http\Livewire\CustomerModal;
use App\Models\payment;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationGroup = 'Transaction';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        $services = Service::join('categories', 'services.id_category', '=', 'categories.id')
        ->select('services.*', 'categories.category_name')
        ->get()
        ->groupBy('category_name');
    
    // Format data untuk groupedOptions
    $groupedOptions = [];
    foreach ($services as $category => $items) {
        $groupedOptions[$category] = $items->pluck('name_services', 'id')->toArray();
    } 
        return $form
            ->schema([


              
                   
                // Card::make()
                  
                //     ->schema([
                //         TextInput::make('tanggal_pemesanan')
                //             ->label('Tanggal pemesanan')
                //             ->default(date('Y-m-d'))
                //             ->disabled(),
                //         TextInput::make('id_pemesanan')
                //             ->label('ID Pemesanan')
                //             ->default(function () {
                //                 return 'P' . date('Ymd') . time();
                //             })
                //             ->disabled(),
                //     Select::make('payment_method')
                //         // ->required()
                //         ->label('Metode Pembayaran')
                //         ->options(payment::pluck('method', 'id'))
                //         ->default(null),
                            
                //     ])
                //     ->columnSpan(1),


                  
                     
                        
            ]);
    }

    






    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Tidak ada data')
            ->emptyStateDescription('Silakan tambahkan data baru.')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Pending')) // Hanya tampilkan data dengan status "Pending"
            ->columns([
                // Kolom ID Transaksi
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Transaksi')
                    ->searchable(),
    
                // Kolom Nama Customer
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Nama Customer')
                    ->searchable(),
    
                // Kolom Nomor Customer
                Tables\Columns\TextColumn::make('customer.number')
                    ->label('Nomor Customer')
                    ->searchable(),
    
                // Kolom Total Harga
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
    
                // Kolom Metode Pembayaran
                Tables\Columns\TextColumn::make('payment.method')
                    ->label('Metode Pembayaran')
                    ->searchable(),
    
                // Kolom Status Transaksi
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Canceled' => 'danger',
                        'Progres' => 'info',
                        'Completed' => 'success',
                        default => 'gray',
                    }),
    
                // Kolom Tanggal Transaksi
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Aksi View (Opsional)
                Tables\Actions\Action::make('cancelTransaction')
                ->label('Cancel') // Label tetap "Cancel"
                ->action(function (Transaction $record) {
                    // Ubah status menjadi "Canceled"
                    if ($record->status === 'Pending') {
                        $record->status = 'Canceled';
                        $record->save();
                    }
                })
                ->color('danger') // Tombol berwarna merah untuk mencerminkan aksi pembatalan
                ->icon('heroicon-o-x-circle') // Ikon pembatalan
                ->requiresConfirmation() // Konfirmasi sebelum aksi dijalankan
                ->tooltip('Cancel this transaction') // Tooltip sederhana
                ->visible(fn (Transaction $record) => $record->status === 'Pending'), // Tombol hanya tampil jika statusnya "Pending"
            
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), // Aksi Hapus Massal
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\NewTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/view/{record}'),
            
        ];
    }
}
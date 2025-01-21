<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\category;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
class MaterialResource extends Resource
{
protected static ?string $navigationGroup = 'Master';
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('material_name')->required(),
                                Forms\Components\TextInput::make('material_stock')->required()->default(1)->numeric(),
                                Forms\Components\Select::make('id_category')
                                    ->label('Keperuntukan')
                                    ->options(
                                        category::pluck('category_name', 'id')
                                    )->required(),
        
                                    Forms\Components\TextInput::make('material_price')->required()->default(0)->numeric(),
                                   
                            ])->columns(2)
                            ,


                        Forms\Components\Select::make('material_unit')
                            ->label('Satuan Material')
                            ->options([
                                'Ukuran' => 'Ukuran',
                                'Lembaran' => 'Lembaran',
                                'Satuan' => 'Satuan',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                // Kosongkan input material_panjang saat material_unit berubah
                                $set('material_panjang', null);
                                $set('material_lebar', null);
                                $set('material_quantity', null);
                            }), // Pastikan select bersifat reaktif
    
                        // Input panjang (hanya ditampilkan jika material_unit adalah "Ukuran")
                        Forms\Components\TextInput::make('material_size')
                        ->label('Size')
                        ->placeholder('Size material')
                       
                        ->required(fn ($get) => $get('material_unit') === 'Satuan'),

                       
                    Forms\Components\TextInput::make('material_panjang')
                        ->label('Panjang material')
                        ->numeric()
                        ->placeholder('Panjang material tersedia')
                        ->hidden(fn ($get) => $get('material_unit') !== 'Ukuran' )
                        ->required(fn ($get) => $get('material_unit') === 'Ukuran')
                        ->afterStateUpdated(function ($state, $set) {
                            // Simpan nilai material_lebar ke l_default
                            $set('p_default', $state);
                        })
                        ->afterStateHydrated(function ($state, $set) {
                            // Jika diperlukan, Anda juga bisa mengisi l_default saat state di-hydrate
                            $set('p_default', $state);
                        }),
                       
                       
                    Forms\Components\TextInput::make('material_lebar')
                        ->label('Lebar material')
                        ->numeric()
                        ->placeholder('Lebar material tersedia')
                        
                        ->hidden(fn ($get) => $get('material_unit') !== 'Ukuran' )
                        ->required(fn ($get) => $get('material_unit') === 'Ukuran')
                        ->afterStateUpdated(function ($state, $set) {
                            // Simpan nilai material_lebar ke l_default
                            $set('l_default', $state);
                        })
                        ->afterStateHydrated(function ($state, $set) {
                            // Jika diperlukan, Anda juga bisa mengisi l_default saat state di-hydrate
                            $set('l_default', $state);
                        }),

                    Forms\Components\TextInput::make('material_quantity')
                        ->label('Jumlah dalam stok')
                        ->numeric()
                        ->placeholder('Size material')
                        
                        ->hidden(fn ($get) => $get('material_unit') !== 'Lembaran' )
                        ->required(fn ($get) => $get('material_unit') === 'Lembaran')
                        ->afterStateUpdated(function ($state, $set) {
                            // Simpan nilai material_lebar ke l_default
                            $set('q_default', $state);
                        })
                        ->afterStateHydrated(function ($state, $set) {
                            // Jika diperlukan, Anda juga bisa mengisi q saat state di-hydrate
                            $set('q_default', $state);
                        }),

                       
                        // Field material_size (akan diisi otomatis)
                        // Forms\Components\Hidden::make('material_size')
                        //     ->dehydrateStateUsing(function ($get) {
                        //         $materialUnit = $get('material_unit');
    
                        //         switch ($materialUnit) {
                        //             case 'Ukuran':
                        //                 $panjang = $get('panjang');
                        //                 $lebar = $get('lebar');
                        //                 return "{$panjang}|{$lebar}";
                        //             case 'Lembaran':
                        //                 return $get('lembaran');
                        //             case 'Satuan':
                        //                 return $get('besaran');
                        //             default:
                        //                 return null;
                        //         }
                        //     }),
                      
                    ])
                    ->columns(1),
                
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Tidak ada data')
            ->emptyStateDescription('Silakan tambahkan data baru.')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('material_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.category_name')
                    ->label('Kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('material_size')
                    ->label('Size'),
                Tables\Columns\TextColumn::make('material_stock')
                    ->label('Stok unit')
                    
                    ->formatStateUsing(function ($state) {
                        // Jika material_stock null atau 0, tampilkan "Habis"
                        if (is_null($state) || $state == 0) {
                            return 'Habis';
                        }
                        // Jika tidak, tampilkan nilai aslinya
                        return $state;
                    })
                    ->color(function ($state) {
                        // Jika material_stock null atau 0, warna teks menjadi danger (merah)
                        
                        if (is_null($state) || $state == 0) {
                            return 'danger';
                        }
                        // Jika tidak, warna teks default
                        return null;
                    }),
                Tables\Columns\TextColumn::make('material_quantity')
                    ->label('Lembar tersisa')
                    ->formatStateUsing(function ($state, Material $record) {
                        // Jika material_price di atas 500, tampilkan "-"
                        if ($record->material_price > 500) {
                            return '-';
                        }
                        if ($record->material_lebar) {
                            return '-';
                        }
                        // Jika material_quantity null, kosong, atau 0, tampilkan "Habis"
                        if (is_null($state) || $state === '' || $state == 0) {
                            return 'Habis';
                        }
                        // Jika tidak, tampilkan nilai aslinya
                        return $state;
                    })
                    ->color(function ($state, Material $record) {
                        // Jika material_price di atas 500, warna default
                        if ($record->material_price > 500) {
                            return null;
                        }
                        if ($record->material_lebar) {
                            return '';
                        }
                        // Jika material_quantity null, kosong, atau 0, warna danger (merah)
                        if (is_null($state) || $state === '' || $state == 0) {
                            return 'danger';
                        }
                        // Jika material_quantity di bawah 200, warna warning (kuning)
                        if ($state < 200) {
                            return 'warning';
                        }
                        // Jika tidak, warna default
                        return null;
                    })
                    ->icon(function ($state, Material $record) {
                        // Jika material_price di atas 500, tidak tampilkan ikon
                        if ($record->material_price > 500) {
                            return null;
                        }
                        if ($record->material_lebar) {
                            return null;
                        }
                        // Tambahkan ikon peringatan jika material_panjang habis atau di bawah 200
                        if (is_null($state) || $state === '' || $state == 0) {
                            return 'heroicon-o-exclamation-circle'; // Ikon untuk "Habis"
                        }
                        
                        if ($state < 200) {
                            return 'heroicon-o-exclamation-triangle'; // Ikon untuk "Di bawah 200"
                        }
                        return null;
                    })
                    ->iconColor(function ($state, Material $record) {
                        // Jika material_price di atas 500, warna ikon default
                        if ($record->material_price > 500) {
                            return null;
                        }
                        // Sesuaikan warna ikon dengan kondisi
                        if (is_null($state) || $state === '' || $state == 0) {
                            return 'danger'; // Warna ikon untuk "Habis"
                        }
                        if ($state < 200) {
                            return 'warning'; // Warna ikon untuk "Di bawah 200"
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('material_panjang')
                    ->label('Panjang tersisa')
                    ->formatStateUsing(function ($state, Material $record) {
                        // Jika material_price di atas 500, tampilkan "-"
                        if ($record->material_price > 500) {
                            return '-';
                        }
                        if ($record->material_quantity) {
                            return '-';
                        }
                       
                        // Jika material_panjang null, kosong, atau 0, tampilkan "Habis"
                        if (is_null($state) || $state === '' || $state == 0) {
                            return 'Habis';
                        }
                        // Jika tidak, tampilkan nilai aslinya
                        return $state;
                    })
                    ->color(function ($state, Material $record) {
                        // Jika material_price di atas 500, warna default
                        if ($record->material_price > 500) {
                            return null;
                        }
                        if ($record->material_quantity) {
                            return null;
                        }
                        // Jika material_panjang null, kosong, atau 0, warna danger (merah)
                        if (is_null($state) || $state === '' || $state == 0) {
                            return 'danger';
                        }
                        // Jika material_panjang di bawah 200, warna warning (kuning)
                        if ($state < 200) {
                            return 'warning';
                        }
                        // Jika tidak, warna default
                        return null;
                    })
                    ->icon(function ($state, Material $record) {
                        // Jika material_price di atas 500, tidak tampilkan ikon
                        if ($record->material_price > 500) {
                            return null;
                        }
                        if ($record->material_quantity) {
                            return null;
                        }
                        // Tambahkan ikon peringatan jika material_panjang habis atau di bawah 200
                        if (is_null($state) || $state === '' || $state == 0) {
                            return 'heroicon-o-exclamation-circle'; // Ikon untuk "Habis"
                        }
                        if ($state < 200) {
                            return 'heroicon-o-exclamation-triangle'; // Ikon untuk "Di bawah 200"
                        }
                        return null;
                    })
                    ->iconColor(function ($state, Material $record) {
                        // Jika material_price di atas 500, warna ikon default
                        if ($record->material_price > 500) {
                            return null;
                        }
                        // Sesuaikan warna ikon dengan kondisi
                        if (is_null($state) || $state === '' || $state == 0) {
                            return 'danger'; // Warna ikon untuk "Habis"
                        }
                        if ($state < 200) {
                            return 'warning'; // Warna ikon untuk "Di bawah 200"
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('material_lebar')
                    ->label('Lebar')
                    ->formatStateUsing(function ($state, Material $record) {
                        // Jika material_price di atas 500, tampilkan "-"
                        if ($record->material_price > 500) {
                            return '-';
                        }
                        if ($record->material_unit == 'Lembaran' || $record->material_unit == 'Satuan') {
                            return '-';
                        }
                        // Jika tidak, tampilkan nilai aslinya
                        return $state;
                    }),
            ])
            ->groups([
                Tables\Grouping\Group::make('category.category_name')
                    ->label('Kategori')
                    ->collapsible(), // Opsional: Membuat grup bisa di-collapse
                Tables\Grouping\Group::make('material_name')
                    ->label('Name')
                    ->collapsible(), // Opsional: Membuat grup bisa di-collapse
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
            ])
            ->actions([
                Action::make('duplicate')
                    ->label('Clone')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('info')
                    ->action(function (Material $record) {
                        $newRecord = $record->replicate();
                        $newRecord->save();
    
                        Notification::make()
                            ->title('Berhasil Duplikat')
                            ->success()
                            ->send();
                    }),
                Action::make('restock')
                    ->label('Restock')
                    ->icon('heroicon-o-arrow-path') // Ikon untuk tombol Restock
                    ->color(function (Material $record) {
                        // Jika material_panjang, material_lebar, dan material_quantity adalah 0, warna tombol menjadi gray
                        if ($record->material_unit == 'Satuan' || $record->material_panjang == $record->p_default && $record->material_quantity == $record->q_default) {
                            return 'gray';
                        }
                        // Jika tidak, warna tombol tetap primary
                        return 'primary';
                    }) // Warna tombol
                    ->disabled(function (Material $record) {
                        // Nonaktifkan tombol jika material_panjang, material_lebar, atau material_quantity adalah 0
                        return $record->material_unit == 'Satuan' || $record->material_panjang == $record->p_default && $record->material_quantity == $record->q_default;
                    })
                    ->visible(function (Material $record) {
                        // Tombol hanya muncul jika p_default, q_default, atau l_default tidak null
                        return !is_null($record->material_panjang) || !is_null($record->material_lebar) || !is_null($record->material_quantity);
                    })
                    ->requiresConfirmation() // Menambahkan konfirmasi sebelum menjalankan aksi
                    ->modalHeading('Konfirmasi Restock')
                    ->modalDescription('Apakah Anda yakin ingin melakukan restock? Ini akan mengurangi stok unit sebanyak 1.')
                    ->action(function (Material $record) {
                        // Kurangi material_stock sebanyak 1
                        if ($record->material_stock > 0) {
                            $record->material_stock -= 1;
                        } else {
                            // Jika material_stock sudah 0, tampilkan notifikasi error
                            Notification::make()
                                ->title('Gagal Restock')
                                ->body('Stok unit sudah habis. Tidak dapat melakukan restock.')
                                ->danger()
                                ->send();
                            return;
                        }
    
                        // Reset material_quantity dengan q_default
                        $record->material_quantity = $record->q_default;
    
                        // Reset material_panjang dengan p_default
                        $record->material_panjang = $record->p_default;
    
                        // Simpan perubahan
                        $record->save();
    
                        // Notifikasi sukses
                        Notification::make()
                            ->title('Berhasil Restock')
                            ->body('Stok unit berkurang 1. Material quantity dan panjang telah direset.')
                            ->success()
                            ->send();
                    }),
                Action::make('addStock')
                    ->label('Stok')
                    ->icon('heroicon-o-plus') // Ikon untuk tombol Tambah Stok
                    ->color('success') // Warna tombol
                    ->form([
                        Forms\Components\TextInput::make('additional_stock')
                            ->label('Jumlah Stok yang Ditambahkan')
                            ->numeric()
                            ->required()
                            ->minValue(1) // Minimal 1 stok yang ditambahkan
                            ->default(1), // Nilai default
                    ])
                    ->action(function (Material $record, array $data) {
                        // Tambahkan stok ke material_stock
                        $record->material_stock += $data['additional_stock'];
    
                        // Simpan perubahan
                        $record->save();
    
                        // Notifikasi sukses
                        Notification::make()
                            ->title('Stok Berhasil Ditambahkan')
                            ->body("Stok berhasil ditambahkan sebanyak {$data['additional_stock']}.")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}

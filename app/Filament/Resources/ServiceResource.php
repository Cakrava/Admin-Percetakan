<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Material;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Actions\StaticAction;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Kolom Kiri
        Forms\Components\Card::make()
            ->schema([
                Forms\Components\Group::make([
                    // Nama Layanan
    
                    // Group untuk Kategori dan Customize (sejajar)
                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('name_services')
                        ->required()
                        ->label('Nama Layanan')
                        ->columnSpan(1),
    
                        Forms\Components\Select::make('id_category')
                        ->required()
                        ->label('Kategori')
                        ->relationship('category', 'category_name')
                        ->placeholder('Pilih Kategori')
                        ->reactive() // Agar bisa merespons perubahan
                        ->live() // Memastikan perubahan langsung diperbarui
                        ->columnSpan(1),
                        // Customize
                     ])
                    ->columns(2) // Membuat Kategori dan Customize sejajar
                    ->columnSpan(1),
                    Forms\Components\Group::make([
                        Forms\Components\Select::make('isCustomize')
                        ->required()
                        ->placeholder('Bisa di isCustomize?')
                        ->label('Customize')
                        ->options([
                            'Yes' => 'Yes',
                            'No' => 'No',
                        ])
                        ->reactive() // Agar bisa merespons perubahan
                        ->columnSpan(1),
                
    
                      
    
                    // Material
                    Forms\Components\Select::make('id_material')
                    
                    ->required(fn (callable $get) => $get('isCustomize') === 'No' || $get('isCustomize') === null)
                    ->label('Material')
                    ->options(function (callable $get) {
                        // Ambil nilai kategori yang dipilih
                        $selectedCategoryId = $get('id_category');
                
                        // Jika kategori belum dipilih, kembalikan array kosong
                        if (!$selectedCategoryId) {
                            return [];
                        }
                
                        // Ambil material yang stoknya lebih dari 0 dan sesuai dengan kategori yang dipilih
                        $materials = Material::where('material_stock', '>', 0)
                            ->where('id_category', $selectedCategoryId) // Filter berdasarkan kategori
                            ->get();
                
                        // Format opsi material
                        $options = [];
                        foreach ($materials as $material) {
                            $options[$material->id] = "{$material->material_name} ({$material->material_size}) | Stok: {$material->material_stock}";

                            // Tambahkan Sisa (material_quantity) jika tidak null atau 0
                            if (!empty($material->material_quantity) && $material->material_quantity != 0) {
                                $options[$material->id] .= " | Sisa: {$material->material_quantity}";
                            }
                            
                            // Tambahkan P (material_panjang) jika tidak null atau 0
                            if (!empty($material->material_panjang) && $material->material_panjang != 0) {
                                $options[$material->id] .= " | P: {$material->material_panjang}";
                            }
                            
                            // Tambahkan L (material_lebar) jika tidak null atau 0
                            if (!empty($material->material_lebar) && $material->material_lebar != 0) {
                                $options[$material->id] .= " | L: {$material->material_lebar}";
                            }
                        }
                
                        return $options;
                    })
                    ->reactive() // Agar bisa merespons perubahan
                    ->afterStateUpdated(function (callable $set, callable $get, $state) {
                        $material = Material::find($get('id_material'));
                        $materialPrice = $material ? $material->material_price : 0;
                        $servicePrice = $get('harga_layanan') ?? 0;
                        $set('price', $materialPrice + $servicePrice);
                    })
                    ->placeholder('Pilih Material')
                    ->searchable()
                    ->columnSpan(1),
                
             
                     ])
                    ->columns(2) // Membuat Kategori dan Customize sejajar
                    ->columnSpan(1),
    
    
                    // Value Custom
                    Forms\Components\Select::make('input_type')
                        ->required()
                        ->placeholder('Pilih value input')
                        ->label('Value type')
                        ->options([
                            'Satuan' => 'Satuan',
                            'Size' => 'Size',
                            'Quantity' => 'Quantity',
                        ])
                        ->reactive() // Agar bisa merespons perubahan
                        ->columnSpan(1)
                        ->default('Satuan')
                        ->hidden(fn (callable $get) => $get('isCustomize') === 'No' || $get('isCustomize') === null),
    
                    // Harga Layanan dan Template Harga (Hanya ditampilkan jika isCustomize = No)
                    Forms\Components\Group::make([
                        Forms\Components\Select::make('template_harga')
                            ->label('Template Harga')
                            ->options([
                                2000 => 'Rp 2.000',
                                5000 => 'Rp 5.000',
                                10000 => 'Rp 10.000',
                                15000 => 'Rp 15.000',
                                20000 => 'Rp 20.000',
                                30000 => 'Rp 30.000',
                                40000 => 'Rp 40.000',
                                50000 => 'Rp 50.000',
                                60000 => 'Rp 60.000',
                                70000 => 'Rp 70.000',
                            ])
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $set('harga_layanan', $state);
                                $materialId = $get('id_material');
                                $material = Material::find($materialId);
                                $materialPrice = $material ? $material->material_price : 0;
                                $set('price', $materialPrice + $state);
                            })
                            ->placeholder('Pilih Template Harga')
                            ->columnSpan(1)
                            ->hidden(fn (callable $get) => $get('input_type') === 'Quantity' || $get('input_type') === 'Size'),
    
                        Forms\Components\TextInput::make('harga_layanan')
                            ->required(fn (callable $get) => $get('input_type') === 'Satuan' && $get('isCustomize') === 'Yes'||  $get('isCustomize') === 'No')
                            ->label('Harga Layanan')
                            ->numeric()
                            ->reactive()
                            ->debounce(300)
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $materialId = $get('id_material');
                                $material = Material::find($materialId);
                                $materialPrice = $material ? $material->material_price : 0;
                                $set('price', $materialPrice + $state);
                            })
                            ->columnSpan(1)
                            ->hidden(fn (callable $get) => $get('input_type') === 'Quantity' || $get('input_type') === 'Size'),
                    ])
                    ->columns(2)
                    ->columnSpan(1),
    
                    // Tolak Ukur Harga (Hanya ditampilkan jika isCustomize = Yes)
                    Forms\Components\Group::make([
                        Forms\Components\Select::make('template_harga')
                        ->label('Template tolak ukur')
                        
                            ->options([
                                50 => 'Rp 50',
                                75 => 'Rp 75',
                                100 => 'Rp 100',
                                150 => 'Rp 150',
                                200 => 'Rp 200',
                                250 => 'Rp 250',
                                300 => 'Rp 300',
                                350 => 'Rp 350',
                                400 => 'Rp 400',
                                450 => 'Rp 450',
                                500 => 'Rp 500',
                                550 => 'Rp 550',
                                600 => 'Rp 600',
                            ])
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $set('harga_layanan', $state);
                                $materialId = $get('id_material');
                                $material = Material::find($materialId);
                                $materialPrice = $material ? $material->material_price : 0;
                                $set('price', $materialPrice + $state);
                            })
                            ->placeholder('Pilih Template Harga')
                            ->columnSpan(1)
                            ->hidden(fn (callable $get) => $get('isCustomize') === 'No' || $get('isCustomize') === null || $get('input_type') === 'Satuan' ),
    
                        Forms\Components\TextInput::make('harga_layanan')
                            ->required(fn (callable $get) => $get('input_type') === 'Quantity'|| $get('input_type') === 'Size')
                           
                            ->label('Harga tolak ukur')
                            ->numeric()
                            ->reactive()
                            ->debounce(300)
                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                $materialId = $get('id_material');
                                $material = Material::find($materialId);
                                $materialPrice = $material ? $material->material_price : 0;
                                $set('price', $materialPrice + $state);
                            })
                            ->columnSpan(1)
                            
                            ->hidden(fn (callable $get) => $get('isCustomize') === 'No' || $get('isCustomize') === null || $get('input_type') === 'Satuan'),
                    ])
                    ->columns(2)
                    ->columnSpan(1),
    
                    // Harga Total
                    Forms\Components\TextInput::make('price')
                        ->required()
                        ->label('Harga Total')
                        ->numeric()
                        ->readOnly()
                        ->columnSpan(1),
                        // ->hidden(fn (callable $get) => $get('isCustomize') === 'Yes' || $get('isCustomize') === null),
                      
                ])
                ->columns(1)
                ->columnSpan(1),
    
                // Kolom Kanan (Gambar)
                Forms\Components\FileUpload::make('image')
                    ->required()
                    ->label('Upload Gambar')
                    ->acceptedFileTypes(['image/png', 'image/jpeg'])
                    ->columnSpan(1),
    
                // Deskripsi
                Forms\Components\TextArea::make('descriptions')
                    ->required()
                    ->label('Deskripsi')
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        
        ->emptyStateHeading('Tidak ada data')
->emptyStateDescription('Silakan tambahkan data baru.')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                ->label('Gambar'),
                Tables\Columns\TextColumn::make('name_services')
                    ->label('Nama Layanan Produk')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.category_name')
                    ->label('Kategori'),

                Tables\Columns\TextColumn::make('material.material_name')
                    ->label('Material'),

                Tables\Columns\TextColumn::make('material.material_size')
                    ->label('Ukuran'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Total')
                    ->numeric(),

               
            ])
            ->groups([
                Tables\Grouping\Group::make('category.category_name')
                    ->label('Kategori')
                    ->collapsible(), // Opsional: Membuat grup bisa di-collapse
              
            ])
            ->actions([
                Action::make('view') // Custom action untuk menampilkan detail
                ->label('')
                ->modalHeading(fn ($record) => $record->name_services) // Judul modal
                ->modalContent(function ($record) {
                    // Tampilkan detail data di dalam modal
                    return view('filament.pages.Action.detail_service_modal', ['record' => $record]);
                })
                ->modalCancelAction(fn (StaticAction $action) => $action->label('Tutup'))
                ->modalWidth('lg')
                ->modalSubmitAction(false)
                ,
                Action::make('duplicate')
                    ->label('Clone')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('success')
                    ->action(function (Service $record) {
                        $newRecord = $record->replicate();
                        $newRecord->save();

                        Notification::make()
                            ->title('Berhasil Duplikat')
                            ->success()
                            ->send();
                    }),
             

                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
             
                    
            ])
            ->recordAction('view') // Gunakan custom action saat baris diklik
            ->recordUrl(null)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
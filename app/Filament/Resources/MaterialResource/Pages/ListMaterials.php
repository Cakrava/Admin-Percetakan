<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
public function getTabs(): array
{
 
   // Ambil semua kategori dari tabel 'categories'
   $categories = \App\Models\Category::all();

   // Buat array untuk menyimpan tab
   $tabs = [];

   // Tambahkan tab "Semua" untuk menampilkan semua material
   $tabs['all'] = Tab::make('Semua')
       ->badge(\App\Models\Material::count()); // Badge untuk jumlah semua material

   // Buat tab untuk setiap kategori
   foreach ($categories as $category) {
       $tabs[$category->id] = Tab::make($category->category_name) // Gunakan category_name sebagai label tab
           ->badge(\App\Models\Material::where('id_category', $category->id)->count()) // Hitung material berdasarkan kategori
           ->modifyQueryUsing(fn (Builder $query) => $query->where('id_category', $category->id)); // Filter material berdasarkan kategori
   }

   return $tabs;} 
}

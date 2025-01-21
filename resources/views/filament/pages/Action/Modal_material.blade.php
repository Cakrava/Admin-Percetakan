<div>
    <h2 class="text-lg font-medium mb-4">Pilih Material</h2>
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Material</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($materials as $material)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $material->material_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button
                            {{-- type="button" --}}
                            onclick="pilihMaterial('{{ $material->id }}', '{{ $material->material_name }}')"
                            class="text-indigo-600 hover:text-indigo-900"
                        >
                            Pilih
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function pilihMaterial(id, name) {
        // Kirim data ke parent component
        window.parent.postMessage({
            type: 'materialDipilih',
            data: { id: id, name: name } // Pastikan key 'name' dan 'id' sesuai
        }, '*');
    }
</script>
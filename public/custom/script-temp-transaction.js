document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('service-modal')
  const openModalBtn = document.getElementById('open-modal-btn')
  const closeModalBtn = document.querySelector('.close-modal')
  const cancelBtn = document.getElementById('cancel-btn')
  const searchInput = document.getElementById('search-service')
  const serviceList = document.getElementById('service-list')
  const tagsContainer = document.getElementById('tags-container')
  const selectedServiceInput = document.getElementById('selected-service')
  const imageContainer = document.getElementById('image-container')
  const serviceIdInput = document.getElementById('service_id')
  const tolakUkurHarga = document.getElementById('tolak_ukur_harga')
  const customizeInput = document.getElementById('customize')
  const nama_layanan = document.getElementById('nama_layanan')
  const panjangGroup = document.getElementById('panjang-group')
  const fileGroupe = document.getElementById('fileUpload')
  const lebarGroup = document.getElementById('lebar-group')
  const quantityGroup = document.getElementById('quantity-group')
  const hargaItem = document.getElementById('harga-peritem')
  const hargaInput = document.getElementById('harga')
  const imageLoader = document.getElementById('imageLoader')

  const chooseMaterialGroup = document.getElementById('choose-material-group')
  const jumlahInput = document.getElementById('jumlah')
  const panjangInput = document.getElementById('panjang')
  const lebarInput = document.getElementById('lebar')
  const addButton = document.getElementById('add-button')
  const quantityInput = document.getElementById('quantity')
  const fileLampiran = document.getElementById('input-lampiran')
  const getMaterial = document.getElementById('get-material')
  const getKategori = document.getElementById('kategori-produk')
  const getStok = document.getElementById('getStok')
  const getPanjang = document.getElementById('getPanjang')
  const getLebar = document.getElementById('getLebar')
  const getQuantity = document.getElementById('getQuantity')
  const pesanError = document.getElementById('pesan-error')

  chooseMaterialGroup.disabled = true
  jumlahInput.disabled = true
  panjangInput.disabled = true
  lebarInput.disabled = true
  addButton.disabled = true
  quantityInput.disabled = true
  fileLampiran.disabled = true
  imageLoader.style.display = 'none'
  pesanError.style.color = 'red'
  let services = []
  let material = []
  let tags = []

  // Fungsi untuk mengubah gaya tombol addButton saat disabled
  function updateButtonStyle(isDisabled) {
    if (isDisabled) {
      addButton.disabled = true
      addButton.style.backgroundColor = '#DEDEDE'
      addButton.style.cursor = 'not-allowed'
      addButton.style.color = '#A9A9A9' // Warna teks untuk keadaan disabled
      addButton.onmouseover = function () {
        this.style.backgroundColor = '#DEDEDE' // Warna hover saat disabled
      }
      addButton.onmouseout = function () {
        this.style.backgroundColor = '#DEDEDE' // Warna normal saat disabled
      }
    } else {
      resetButtonStyle() // Kembalikan gaya tombol ke kondisi default
    }
  }

  // Fungsi untuk mengembalikan gaya tombol addButton ke kondisi default
  function resetButtonStyle() {
    addButton.disabled = false
    addButton.style.backgroundColor = 'rgb(200, 115, 3)'
    addButton.style.color = 'white'
    addButton.style.border = 'none'
    addButton.style.borderRadius = '10px'
    addButton.style.padding = '10px'
    addButton.style.fontSize = '14px'
    addButton.style.cursor = 'pointer'
    addButton.style.zIndex = '2'
    addButton.style.transition = 'background-color 0.3s ease'
    addButton.onmouseover = function () {
      this.style.backgroundColor = 'rgb(255, 153, 13)' // Warna hover default
    }
    addButton.onmouseout = function () {
      this.style.backgroundColor = 'rgb(200, 115, 3)' // Warna normal default
    }
  }

  jumlahInput.addEventListener('input', function () {
    // Bersihkan input dari karakter non-angka dan hapus awalan 0
    this.value = this.value.replace(/[^0-9]/g, '')
    if (this.value.length > 1 && this.value.startsWith('0')) {
      this.value = this.value.replace(/^0+/, '') // Menghapus semua 0 di awal
    }

    // Ambil nilai dari input
    const quantity = parseInt(quantityInput.value) || 0 // Nilai quantity
    const jumlah = parseInt(this.value) || 0 // Nilai jumlah (dari jumlahInput)
    const maxQuantity = parseInt(getQuantity.value) || 0 // Nilai maksimum (getQuantity)
    const stok = parseInt(getStok.value) || 0 // Nilai stok (getStok)
    const panjang = parseInt(panjangInput.value) || 0 // Nilai panjang
    const maxPanjang = parseInt(getPanjang.value) || 0 // Nilai maksimum (getPanjang)

    // Variabel untuk menyimpan pesan error
    let errorMessage = ''

    // Pengecekan jumlah tidak boleh 0
    if (jumlah === 0) {
      errorMessage = 'Jumlah tidak valid!'
    }

    // Pengecekan stok
    if (jumlah > stok) {
      errorMessage = errorMessage
        ? `Stock tidak mencukupi!`
        : 'Stock tidak mencukupi!'
    }

    // Pengecekan quantity (hanya jika quantity > 0)
    if (quantity > 0) {
      const totalQuantity = quantity * jumlah // Hitung hasil perkalian quantity
      if (totalQuantity > maxQuantity) {
        errorMessage = errorMessage
          ? `Quantity tidak mencukupi!`
          : 'Quantity tidak mencukupi!'
      }
    }

    // Pengecekan panjang (hanya jika panjang > 0)
    if (panjang > 0) {
      const totalPanjang = jumlah * panjang // Hitung hasil perkalian panjang
      if (totalPanjang > maxPanjang) {
        errorMessage = errorMessage
          ? `Panjang material tidak mencukupi!`
          : 'Panjang material tidak mencukupi!'
      }
    }

    // Tampilkan pesan error jika ada
    if (errorMessage) {
      pesanError.textContent = errorMessage
      updateButtonStyle(true) // Nonaktifkan tombol dan ubah gaya
    } else {
      pesanError.textContent = '' // Kosongkan pesan error
      resetButtonStyle() // Kembalikan gaya tombol ke kondisi default
    }
  })

  quantityInput.addEventListener('input', function () {
    // Bersihkan input dari karakter non-angka dan hapus awalan 0
    this.value = this.value.replace(/[^0-9]/g, '')
    if (this.value.length > 1 && this.value.startsWith('0')) {
      this.value = this.value.replace(/^0+/, '') // Menghapus semua 0 di awal
    }

    // Ambil nilai dari input
    const quantity = parseInt(this.value) || 0 // Nilai quantity
    const jumlah = parseInt(jumlahInput.value) || 0 // Nilai jumlah
    const maxQuantity = parseInt(getQuantity.value) || 0 // Nilai maksimum (getQuantity)

    // Variabel untuk menyimpan pesan error
    let errorMessage = ''

    // Pengecekan quantity tidak boleh 0
    if (quantity === 0) {
      errorMessage = 'Quantity tidak valid!'
    }

    // Pengecekan quantity (hanya jika quantity > 0)
    if (quantity > 0) {
      const total = quantity * jumlah // Hitung hasil perkalian
      if (total > maxQuantity) {
        errorMessage = errorMessage
          ? `Quantity tidak mencukupi!`
          : 'Quantity tidak mencukupi!'
      }
    }

    // Tampilkan pesan error jika ada
    if (errorMessage) {
      pesanError.textContent = errorMessage
      updateButtonStyle(true) // Nonaktifkan tombol dan ubah gaya
    } else {
      pesanError.textContent = '' // Kosongkan pesan error
      resetButtonStyle() // Kembalikan gaya tombol ke kondisi default
    }
  })

  panjangInput.addEventListener('input', function () {
    // Bersihkan input dari karakter non-angka dan hapus awalan 0
    this.value = this.value.replace(/[^0-9]/g, '')
    if (this.value.length > 1 && this.value.startsWith('0')) {
      this.value = this.value.replace(/^0+/, '') // Menghapus semua 0 di awal
    }

    // Ambil nilai dari input
    const panjang = parseInt(this.value) || 0 // Nilai panjang
    const jumlah = parseInt(jumlahInput.value) || 0 // Nilai jumlah
    const maxPanjang = parseInt(getPanjang.value) || 0 // Nilai maksimum (getPanjang)

    // Variabel untuk menyimpan pesan error
    let errorMessage = ''

    // Pengecekan panjang tidak boleh 0
    if (panjang === 0) {
      errorMessage = 'Panjang tidak valid!'
    }

    // Pengecekan panjang (hanya jika panjang > 0)
    if (panjang > 0) {
      const total = panjang * jumlah // Hitung hasil perkalian
      if (total > maxPanjang) {
        errorMessage = errorMessage
          ? `${errorMessage} Dan panjang material tidak mencukupi!`
          : 'Panjang material tidak mencukupi!'
      }
    }

    // Tampilkan pesan error jika ada
    if (errorMessage) {
      pesanError.textContent = errorMessage
      updateButtonStyle(true) // Nonaktifkan tombol dan ubah gaya
    } else {
      pesanError.textContent = '' // Kosongkan pesan error
      resetButtonStyle() // Kembalikan gaya tombol ke kondisi default
    }
  })

  lebarInput.addEventListener('input', function () {
    // Bersihkan input dari karakter non-angka dan hapus awalan 0
    this.value = this.value.replace(/[^0-9]/g, '')
    if (this.value.length > 1 && this.value.startsWith('0')) {
      this.value = this.value.replace(/^0+/, '') // Menghapus semua 0 di awal
    }

    // Ambil nilai dari input
    const lebar = parseInt(this.value) || 0 // Nilai lebar
    const maxLebar = parseInt(getLebar.value) || 0 // Nilai maksimum (getLebar)

    // Variabel untuk menyimpan pesan error
    let errorMessage = ''

    // Pengecekan lebar tidak boleh 0
    if (lebar === 0) {
      errorMessage = 'Lebar tidak valid!'
    }

    // Pengecekan lebar
    if (lebar > maxLebar) {
      errorMessage = errorMessage
        ? `${errorMessage} Dan pilih material yang lebih lebar!`
        : 'Pilih material yang lebih lebar!'
    }

    // Tampilkan pesan error jika ada
    if (errorMessage) {
      pesanError.textContent = errorMessage
      updateButtonStyle(true) // Nonaktifkan tombol dan ubah gaya
    } else {
      pesanError.textContent = '' // Kosongkan pesan error
      resetButtonStyle() // Kembalikan gaya tombol ke kondisi default
    }
  })

  // Fetch data from API
  fetch('/service-form-transaction')
    .then((response) => response.json())
    .then((data) => {
      services = data.services
      material = data.materials
      populateServiceList(services)
      populateMaterials(material)
    })
    .catch((error) => console.error('Error fetching data:', error))
  // Event Listeners for modal functionality

  openModalBtn.addEventListener('click', () => {
    modal.style.display = 'flex' // Tampilkan modal
    document.body.style.overflow = 'hidden' // Mencegah scroll pada halaman di belakang modal
    chooseMaterialGroup.disabled = false
  })

  closeModalBtn.addEventListener('click', () => {
    modal.style.display = 'none' // Sembunyikan modal
    document.body.style.overflow = 'auto' // Mengembalikan scroll pada halaman di belakang modal
  })

  cancelBtn.addEventListener('click', () => {
    modal.style.display = 'none' // Sembunyikan modal
    document.body.style.overflow = 'auto' // Mengembalikan scroll pada halaman di belakang modal
  })

  window.addEventListener('click', (event) => {
    if (event.target === modal) {
      modal.style.display = 'none' // Sembunyikan modal saat klik di luar modal
      document.body.style.overflow = 'auto' // Mengembalikan scroll pada halaman di belakang modal
    }
  })
  // Add tag functionality
  searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault() // Mencegah form submit
      const searchTerm = this.value.trim()
      if (searchTerm) {
        addTag(searchTerm) // Tambahkan tag jika ada input
        this.value = '' // Kosongkan input
      }
    }
  })

  // Render tags
  function renderTags() {
    tagsContainer.innerHTML = ''
    tags.forEach((tag) => {
      const tagElement = document.createElement('div')
      tagElement.className = 'tag'
      tagElement.innerHTML = `${tag} <span class="tag-remove">&times;</span>`
      tagsContainer.appendChild(tagElement)

      // Add event listener for removing tag
      tagElement
        .querySelector('.tag-remove')
        .addEventListener('click', () => removeTag(tag))
    })
  }

  // Add and remove tags
  function addTag(tag) {
    if (tag && !tags.includes(tag)) {
      tags.push(tag)
      renderTags()
      filterServices()
    }
  }

  function removeTag(tag) {
    tags = tags.filter((t) => t !== tag)
    renderTags()
    filterServices()
  }

  // Filter services by tags and search input
  function filterServices() {
    const searchTerm = searchInput.value.toLowerCase()
    const filteredServices = services.filter((service) => {
      const serviceName = service.name_services.toLowerCase()
      const categoryName = service.category?.category_name.toLowerCase() || ''
      const matchesTags =
        tags.length === 0 ||
        tags.some(
          (tag) =>
            serviceName.includes(tag.toLowerCase()) ||
            categoryName.includes(tag.toLowerCase()),
        )
      const matchesSearch =
        !searchTerm ||
        serviceName.includes(searchTerm) ||
        categoryName.includes(searchTerm)
      return matchesTags && matchesSearch
    })
    populateServiceList(filteredServices)
  }

  searchInput.addEventListener('input', filterServices)

  // Populate materials dropdown

  function populateMaterials(materials) {
    const materialSelect = document.getElementById('choose_material')
    materialSelect.innerHTML = ''

    // Tambahkan opsi default
    const defaultOption = document.createElement('option')
    defaultOption.value = ''
    defaultOption.textContent = 'Pilih Material'
    defaultOption.disabled = true
    defaultOption.selected = true
    materialSelect.appendChild(defaultOption)

    // Filter materials berdasarkan kategoriProduk
    const filteredMaterials = materials.filter((material) => {
      if (!getKategori.value) {
        return false // Skip jika kategori tidak dipilih
      }
      return material.id_category === getKategori.value
    })

    // Jika tidak ada material yang sesuai, tampilkan pesan atau opsi default
    if (filteredMaterials.length === 0) {
      const noMaterialOption = document.createElement('option')
      noMaterialOption.value = ''
      noMaterialOption.textContent = ' - '
      noMaterialOption.disabled = true
      noMaterialOption.selected = true
      materialSelect.appendChild(noMaterialOption)
    } else {
      // Loop melalui material yang sudah difilter
      filteredMaterials.forEach((material) => {
        const quantity = material.material_quantity || 0 // Default ke 0 jika null
        const panjang = material.material_panjang || 0 // Default ke 0 jika null

        // Logika untuk menampilkan material berdasarkan kondisi
        if (
          (quantity > 0 && panjang === 0) || // Quantity > 0 dan panjang = 0
          (quantity === 0 && panjang > 0) // Quantity = 0 dan panjang > 0
        ) {
          const option = document.createElement('option')
          option.value = material.id
          option.textContent = `${material.material_name} (Size: ${material.material_size}, Stock: ${material.material_stock})`

          // Tambahkan Sisa (material_quantity) jika tidak null atau 0
          if (quantity > 0) {
            option.textContent += `, Sisa: ${quantity}`
          }

          // Tambahkan P (material_panjang) jika tidak null atau 0
          if (panjang > 0) {
            option.textContent += `, P: ${panjang}`
          }

          // Tambahkan L (material_lebar) jika tidak null atau 0
          if (material.material_lebar && material.material_lebar != 0) {
            option.textContent += `, L: ${material.material_lebar}`
          }

          // Simpan data stok sebagai atribut data-stock
          option.setAttribute('data-stock', material.material_stock)
          option.setAttribute('data-size', material.material_size)
          option.setAttribute('data-quantity', material.material_quantity)
          option.setAttribute('data-panjang', material.material_panjang)
          option.setAttribute('data-lebar', material.material_lebar)
          materialSelect.appendChild(option)
        }
      })
    }
    // Event listener untuk menangani perubahan pada dropdown material
    materialSelect.addEventListener('change', function () {
      const selectedOption =
        materialSelect.options[materialSelect.selectedIndex]

      // Jika opsi yang dipilih valid (bukan opsi default)
      if (selectedOption && selectedOption.value !== '') {
        // Ambil nilai data-stock dari opsi yang dipilih dan set ke getStok.value
        getStok.value = selectedOption.getAttribute('data-stock')
        getPanjang.value = selectedOption.getAttribute('data-panjang')
        getLebar.value = selectedOption.getAttribute('data-lebar')
        getQuantity.value = selectedOption.getAttribute('data-quantity')

        panjangInput.disabled = false
        panjangInput.style.backgroundColor = ''
        lebarInput.disabled = false
        lebarInput.style.backgroundColor = ''
        quantityInput.disabled = false
        quantityInput.style.backgroundColor = ''
        fileLampiran.disabled = false
        fileLampiran.style.backgroundColor = ''
        jumlahInput.disabled = false
        jumlahInput.style.backgroundColor = ''
      } else {
        // Jika opsi default dipilih, kosongkan getStok.value
        getStok.value = ''
        getPanjang.value = ''
        getLebar.value = ''
        getQuantity.value = ''
      }
    })
  }

  // Populate service list
  function populateServiceList(services) {
    serviceList.innerHTML = ''
    const groupedServices = services.reduce((acc, service) => {
      const categoryName = service.category?.category_name || 'Uncategorized'
      if (!acc[categoryName]) acc[categoryName] = []
      acc[categoryName].push(service)
      return acc
    }, {})

    for (const [categoryName, servicesInCategory] of Object.entries(
      groupedServices,
    )) {
      const categoryHeader = document.createElement('div')
      categoryHeader.className = 'category-header'
      categoryHeader.textContent = categoryName
      serviceList.appendChild(categoryHeader)

      servicesInCategory.forEach((service) => {
        const serviceItem = document.createElement('div')
        serviceItem.className = 'service-item'
        const serviceInfo = document.createElement('div')
        serviceInfo.className = 'service-info'
        serviceInfo.innerHTML = `
              <h3>${service.name_services}</h3>
              <p>${service.price} - ${
          service.isCustomize === 'Yes'
            ? 'Bisa dikustom'
            : 'Tidak bisa dicustom'
        }</p>
            `
        const serviceImage = document.createElement('img')
        serviceImage.src = service.image
          ? `/storage/${service.image}`
          : 'https://via.placeholder.com/50'
        serviceImage.alt = service.name_services

        serviceItem.appendChild(serviceInfo)
        serviceItem.appendChild(serviceImage)
        serviceItem.addEventListener('click', () => selectService(service))
        serviceList.appendChild(serviceItem)
      })
    }
  }

  // Select service and update form
  function selectService(service) {
    // Set nilai input berdasarkan service yang dipilih
    selectedServiceInput.value = service.name_services
    serviceIdInput.value = service.id
    nama_layanan.value = service.name_services
    customizeInput.value =
      service.isCustomize === 'Yes' ? 'Bisa dicustom' : 'Tidak bisa dicustom'
    hargaInput.value = service.price
    getMaterial.value = service.id_material
    getKategori.value = service.category.id
    getStok.value = service.material?.material_stock || ''
    hargaItem.value = service.price
    tolakUkurHarga.value = service.price
    getPanjang.value = ''
    getLebar.value = ''
    getQuantity.value = ''
    jumlahInput.value = '1'
    quantityInput.value = '1'
    panjangInput.value = '1'
    lebarInput.value = '1'
    pesanError.value = ''

    if (service.input_type === 'Satuan') {
      // Aktifkan jumlahInput dan fileLampiran
      ;[jumlahInput, fileLampiran].forEach((element) => {
        if (element) {
          element.disabled = false
          element.style.backgroundColor = '' // Kembalikan background color ke default
        }
      })

      // Nonaktifkan panjangInput, lebarInput, dan quantityInput
      ;[panjangInput, lebarInput, quantityInput].forEach((element) => {
        if (element) {
          element.disabled = true
          element.style.backgroundColor = '#dedede' // Ubah background color menjadi abu-abu
        }
      })
    } else {
      // Nonaktifkan semua input jika bukan 'Satuan'
      ;[
        panjangInput,
        lebarInput,
        quantityInput,
        jumlahInput,
        fileLampiran,
      ].forEach((element) => {
        if (element) {
          element.disabled = true
          element.style.backgroundColor = '#dedede'
        }
      })
    }

    // Handle gambar
    if (service.image) {
      const imageUrl = `/storage/${service.image}`
      imagesaja.value = service.image
      imageContainer.innerHTML = `<img src="${imageUrl}" alt="Service Image" style="width: 100%; height: 100%; object-fit: contain;">`
    } else {
      imageContainer.innerHTML = '<p class="text-red-500">Tidak ada gambar.</p>'
    }

    // Panggil fungsi tambahan
    const inputType = service.input_type
    const isCustom = service.isCustomize
    setInputFieldsVisibility(inputType, isCustom) // Pastikan fungsi ini sudah didefinisikan
    populateMaterials(material) // Pastikan fungsi ini sudah didefinisikan

    // Sembunyikan modal dan aktifkan scroll
    modal.style.display = 'none'
    document.body.style.overflow = 'auto'
  }
  // Update form visibility based on input type
  function setInputFieldsVisibility(inputType, isCustom) {
    const fileUploadGroup = document.getElementById('file-upload-group') // Pastikan ID elemen fileUpload benar

    switch (inputType) {
      case 'Size':
        panjangGroup.style.display = 'block' // Menampilkan input panjang
        lebarGroup.style.display = 'block' // Menampilkan input lebar
        quantityGroup.style.display = 'none' // Menyembunyikan input quantity
        chooseMaterialGroup.style.display = 'block' // Menampilkan dropdown material
        quantityInput.value = ''

        break
      case 'Quantity':
        panjangGroup.style.display = 'none' // Menyembunyikan input panjang
        lebarGroup.style.display = 'none' // Menyembunyikan input lebar
        quantityGroup.style.display = 'block' // Menampilkan input quantity
        chooseMaterialGroup.style.display = 'block' // Menampilkan dropdown material
        panjangInput.value = ''
        lebarInput.value = ''
        break
      default:
        panjangGroup.style.display = 'none' // Menyembunyikan input panjang
        lebarGroup.style.display = 'none' // Menyembunyikan input lebar
        quantityGroup.style.display = 'none' // Menyembunyikan input quantity
        chooseMaterialGroup.style.display = 'none' // Menyembunyikan dropdown material
        lebarInput.value = ''
        panjangInput.value = ''
        quantityInput.value = ''
        break
    } // Tampilkan atau sembunyikan fileUpload berdasarkan isCustom
    if (isCustom === 'Yes') {
      fileLampiran.required = true // Set elemen fileLampiran menjadi required
      fileGroupe.style.display = 'block' // Menampilkan fileUpload
    } else {
      fileLampiran.required = false // Tidak perlu required jika tidak digunakan
      fileGroupe.style.display = 'none' // Menyembunyikan fileUpload
    }
  }

  // Calculate price based on input
  function calculatePrice() {
    const hargaPerItem = parseFloat(hargaItem.value) || 0
    const tolakukur = parseFloat(tolakUkurHarga.value) || 0
    const jumlah = parseFloat(jumlahInput.value) || 0
    const panjang = parseFloat(panjangInput.value) || 0
    const lebar = parseFloat(lebarInput.value) || 0
    const quantity = parseFloat(quantityInput.value) || 0

    let biayaTambahan = 0
    if (panjangGroup.style.display !== 'none')
      biayaTambahan += tolakukur * panjang
    if (lebarGroup.style.display !== 'none') biayaTambahan += tolakukur * lebar
    if (quantityGroup.style.display !== 'none')
      biayaTambahan += tolakukur * quantity

    const totalHarga = (hargaPerItem + biayaTambahan) * jumlah
    hargaInput.value = totalHarga.toFixed(2)
  }

  // Add event listeners for input changes
  jumlahInput.addEventListener('input', calculatePrice)
  panjangInput.addEventListener('input', calculatePrice)
  lebarInput.addEventListener('input', calculatePrice)
  quantityInput.addEventListener('input', calculatePrice)

  addButton.addEventListener('click', async function (event) {
    event.preventDefault() // Mencegah perilaku default tombol

    imageLoader.style.display = 'block'
    const fileInput = document.getElementById('input-lampiran')
    const formData = new FormData() // Gunakan FormData untuk mengirim file

    formData.append('group_id', await getUniqueIDFromJSON())
    formData.append('service_id', serviceIdInput.value)
    formData.append('jumlah', jumlahInput.value)
    formData.append('harga', hargaInput.value)
    formData.append('quantity', quantityInput.value)
    formData.append('lebar', lebarInput.value)
    formData.append('panjang', panjangInput.value)
    formData.append('image', imagesaja.value)
    formData.append('id_material', getMaterial.value)

    if (fileInput.files.length > 0) {
      formData.append('lampiran', fileInput.files[0]) // Tambahkan file ke FormData
    }

    if (
      !formData.get('service_id') ||
      !formData.get('jumlah') ||
      !formData.get('harga')
    ) {
      alert('Harap pilih service dan pastikan semua field terisi.')
      imageLoader.style.display = 'none'
      return
    }

    try {
      const response = await fetch('/save-temp-data', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content'),
        },
        body: formData,
      })

      if (!response.ok) {
        throw new Error('Network response was not ok')
      }

      const data = await response.json()
      if (data.success) {
        resetForm()
        imageLoader.style.display = 'none'
      } else {
        alert('Gagal menyimpan data: ' + data.message)
      }
    } catch (error) {
      console.error('Error:', error)
      alert('Terjadi kesalahan saat menyimpan data.')
    } finally {
      resetForm()

      imageLoader.style.display = 'none'
    }
  })

  // Reset form after adding data
  function resetForm() {
    serviceIdInput.value = ''
    fileLampiran.value = ''

    nama_layanan.value = ''
    jumlahInput.value = '1'
    hargaInput.value = ''
    quantityInput.value = ''
    lebarInput.value = ''
    panjangInput.value = ''
    imageContainer.innerHTML = ''
    selectedServiceInput.value = ''
  }

  // Get unique ID from JSON
  async function getUniqueIDFromJSON() {
    try {
      const response = await fetch('/storage/temp/temp_unique_id.json')
      const data = await response.json()
      return data.uniqueID
    } catch (error) {
      console.error('Error fetching uniqueID:', error)
      return null
    }
  }
})

// Generate unique ID
function generateUniqueID() {
  return 'id_' + Math.random().toString(36).substr(2, 9)
}

// Save unique ID to JSON
async function saveUniqueIDToJSON(uniqueID) {
  try {
    const response = await fetch('/save-unique-id', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document
          .querySelector('meta[name="csrf-token"]')
          .getAttribute('content'),
      },
      body: JSON.stringify({ uniqueID }),
    })
    const result = await response.json()
    console.log(result.message)
  } catch (error) {
    console.error('Error:', error)
  }
}

// Fetch get
async function fetchGet() {
  try {
    await fetch('/clear-temp-data')
  } catch (error) {
    console.error('Error fetching data:', error)
  }
} // Fungsi 1: Generate Kode dengan Format P{DDMMYYYY}{HHmmss}
function generateKode() {
  const now = new Date() // Ambil waktu saat ini
  const day = String(now.getDate()).padStart(2, '0') // DD
  const month = String(now.getMonth() + 1).padStart(2, '0') // MM (bulan dimulai dari 0)
  const year = now.getFullYear() // YYYY
  const hours = String(now.getHours()).padStart(2, '0') // HH
  const minutes = String(now.getMinutes()).padStart(2, '0') // mm
  const seconds = String(now.getSeconds()).padStart(2, '0') // ss

  // Gabungkan menjadi format P{DDMMYYYY}{HHmmss}
  const kode = `P${day}${month}${year}${hours}${minutes}${seconds}`
  return kode
}

// Fungsi 2: Ambil Tanggal Lokal dalam Format YYYY-MM-DD
function getLocalDate() {
  const now = new Date() // Ambil waktu saat ini
  const year = now.getFullYear() // YYYY
  const month = String(now.getMonth() + 1).padStart(2, '0') // MM
  const day = String(now.getDate()).padStart(2, '0') // DD

  // Gabungkan menjadi format YYYY-MM-DD
  const localDate = `${year}-${month}-${day}`
  return localDate
}

window.onload = () => {
  fetchGet() // Panggil fungsi fetchGet

  const uniqueID = generateUniqueID() // Generate unique ID
  console.log('Kode unik yang dihasilkan:', uniqueID)
  saveUniqueIDToJSON(uniqueID) // Simpan unique ID ke JSON

  const kodeUnik = generateKode() // Hasil: P24032024123345 (contoh)
  const tanggalLokal = getLocalDate() // Hasil: 2025-01-08 (contoh)

  // Ambil elemen input (pastikan ID-nya sesuai di HTML)
  const autoTanggal = document.getElementById('tanggal_transaksi')
  const autoIdTransaksi = document.getElementById('id_transaksi')

  // Set nilai ke input field
  if (autoTanggal) autoTanggal.value = tanggalLokal
  if (autoIdTransaksi) autoIdTransaksi.value = kodeUnik
}

<div class="container"
    style="background-color : white; padding : 10px; margin-bottom : 20px; border-width: 1px; border-style: solid; border-color: #dedede; border-radius : 10px">
    <div class="container" style="display: flex; justify-content: space-between; gap: 20px;">
        <!-- Kolom Kiri -->


        <input type="number" id="service_id" hidden>

        <input id="tolak_ukur_harga" hidden>
        <input id="harga-peritem" hidden>
        <input id="kategori-produk" hidden>
        <input id="getPanjang" hidden>
        <input id="getLebar" hidden>
        <input id="getQuantity" hidden>
        <input id="getStok" hidden>

        <input id="get-material" hidden>
        <div class="left-column" style="flex: 1; padding: 20px; border-radius: 10px;">
            <h1>Data Service</h1>
            <br>
            <div class="form-group">
                <input type="text" id="selected-service" hidden readonly placeholder="Pilih service...">
            </div>
            <div id="service-modal" class="modal">
                <div class="modal-content">
                    <!-- Header Modal -->
                    <div class="modal-header">
                        <h2>Pilih Service</h2>
                        <span class="close-modal">&times;</span>
                    </div>

                    <!-- Body Modal -->
                    <div class="modal-body">
                        <input type="text" id="search-service" placeholder="Cari service...">
                        <div id="tags-container" class="tags-container"></div>
                        <div id="service-list" class="service-list"></div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="modal-footer">
                        <button id="cancel-btn" type="button">Cancel</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="sub-group">
                    <div class="input-group">

                        <div style="position: relative; display: flex; align-items: center;">
                            <button id="open-modal-btn" type="button"
                                style="position: absolute; left: 0; top: 0; bottom: 0; background-color: rgb(200, 115, 3); color: white; border: none; border-radius: 5px 0 0 5px; padding: 0 15px; font-size: 14px; cursor: pointer; z-index: 2; transition: background-color 0.3s ease;"
                                onmouseover="this.style.backgroundColor='rgb(255, 153, 13)'"
                                onmouseout="this.style.backgroundColor='rgb(200, 115, 3)'">
                                Cari
                            </button>
                            <input type="text" id="nama_layanan" placeholder="Nama layanan" disabled
                                style="flex-grow: 1; padding: 10px 10px 10px 70px; border: 1px solid #ccc; border-radius: 10px; font-size: 14px; background-color: #f9f9f9; cursor: not-allowed;">
                        </div>
                    </div>
                    <div class="input-group">

                        <input type="text" id="customize"
                            style="flex-grow: 1; padding: 10px 10px 10px 70px; border: 1px solid #ccc; border-radius: 10px; font-size: 14px; background-color: #f9f9f9; cursor: not-allowed;"
                            placeholder="Is this customize?" disabled>
                    </div>

                </div>
            </div>





            <div class="form-group">
                <div class="sub-group">
                    <div class="input-group" id="panjang-group">
                        <label for="panjang">Panjang</label>
                        <input type="text" id="panjang">
                    </div>
                    <div class="input-group" id="lebar-group">
                        <label for="lebar">Lebar</label>
                        <input type="text" id="lebar">
                    </div>
                    <div class="input-group" id="choose-material-group">
                        <label for="choose_material">Pilih material</label>
                        <select id="choose_material">
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                </div>

                <div class="sub-group">

                    <div class="input-group" id="quantity-group">
                        <label for="quantity">Quantity</label>
                        <input type="text" id="quantity" required>
                    </div>
                    <div class="input-group" id="jumlah-group">
                        <label for="jumlah">Jumlah beli</label>
                        <input type="text" id="jumlah" value="1" required>
                    </div>
                    <div class="input-group" id="harga-group">
                        <label for="harga">Harga</label>
                        <input type="number" id="harga" disabled>
                    </div>
                </div>

            </div>


            <div class="input-group" id="fileUpload"
                style="padding: 20px; background-color: #fafafa; border: none; border-radius: 8px;">
                <label class="text-base text-gray-800 font-semibold mb-2 block">Upload File</label>
                <input type="file" id="input-lampiran" name="input-lampiran"
                    style="width: 100%; padding: 14px; font-size: 14px; color: #333; background-color: #fff; border: 2px solid #BDBDBD; border-radius: 8px; cursor: pointer;">
                <p class="text-xs text-gray-400 mt-2">PNG, JPG, RAR, PDF</p>
            </div>


            <input type="text" id="imagesaja" hidden>
            <!-- Tambahkan tombol Add di bawah form -->

        </div>


        <div class="right-column" style="flex: 1; padding: 20px; background-color: #f9f9f9; border-radius: 10px;">
            <div id="image-container"
                style="background-color: #f9f9f9  ; width: 100%; height: 300px; border-radius: 10px; overflow: hidden; display: flex; justify-content: center; align-items: center;">

            </div>

        </div>




    </div>
    <div class="form-group"
        style="padding: 20px; width: 100%; display: flex; ; align-items: center; margin-top : -30px">


        <button id="add-button"
            style="z-index : 10;background-color: rgb(200, 115, 3); color: white; border: none; border-radius: 10px; padding: 10px; font-size: 14px; cursor: pointer; z-index: 2; transition: background-color 0.3s ease;"
            onmouseover="this.style.backgroundColor='rgb(255, 153, 13)'"
            onmouseout="this.style.backgroundColor='rgb(200, 115, 3)'">
            Tambah
        </button>
        <div class="image-preview" id="imageLoader">
            <img src="{{ asset('img/loader.gif') }}" alt="Preview"
                style="width: auto; height: 50px; margin-left : 20px; margin-top : -20px">
        </div>
    </div>
    <p id="pesan-error" style="margin-left: 20px"></p>
</div>
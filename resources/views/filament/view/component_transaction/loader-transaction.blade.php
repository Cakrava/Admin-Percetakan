<div class="overlay">
    <div class="loader">
        <img src="{{ asset('img/loader.gif') }}" alt="Loading..." style="max-width: 100px; height: auto;">
    </div>
</div>

<style>
    /* Overlay */
    .overlay {
        position: fixed; /* Tetap di tempat saat di-scroll */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Latar belakang semi-transparan */
        display: flex;
        justify-content: center; /* Pusatkan horizontal */
        align-items: center; /* Pusatkan vertikal */
        z-index: 1000; /* Pastikan overlay di atas elemen lain */
    }

    /* Loader */
    .loader {
        text-align: center; /* Pusatkan konten di dalam loader */
    }
</style>
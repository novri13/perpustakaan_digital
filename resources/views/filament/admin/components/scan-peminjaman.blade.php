<div class="space-y-4 mb-6">
  <div class="flex gap-2">
    <button id="mode-anggota" 
      class="px-4 py-2 bg-blue-600 text-black font-semibold rounded hover:bg-blue-700">
      Scan Anggota
    </button>
    <button id="mode-buku" 
      class="px-4 py-2 bg-green-600 text-black font-semibold rounded hover:bg-green-700">
      Scan Buku
    </button>
  </div>

  <p id="scan-mode" class="font-semibold mt-4">Mode: Belum dipilih</p>
  <div id="qr-reader" class="border rounded p-2 w-full max-w-md"></div>

  <!-- Hidden input kalau mau submit -->
  <input type="hidden" id="scan-result-anggota" name="anggota_qr">
  <input type="hidden" id="scan-result-buku" name="buku_qr">

  <!-- Hasil scan -->
  <p class="mt-2">Hasil scan anggota: <span id="hasil-anggota">-</span></p>
  <p class="mt-2">Hasil scan buku: <span id="hasil-buku">-</span></p>
</div>
<script src="{{asset('js/jquery.js')}}"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let currentMode = null; 
    let scanner;

    const qrReaderId = "qr-reader";
    const modeText = document.getElementById("scan-mode");
    const hasilAnggota = document.getElementById("hasil-anggota");
    const hasilBuku = document.getElementById("hasil-buku");

    // Fungsi sukses scan
    function onScanSuccess(decodedText) {
        if (currentMode === "anggota") {
            document.getElementById("scan-result-anggota").value = decodedText;
            hasilAnggota.textContent = decodedText;
            alert("Anggota terdeteksi: " + decodedText);

            //disini ajax
            $.ajax(
            {
              url     : '<?php echo url("/admin/qrcode/get_data") ?>' + decodedText,
              //dataType: 'JSON',
              type    : 'GET',
              data    :  {
                 
              },
              success : function(data)
              {
               alert(data);
              }, 
              error : function(){
                console.log('error list');
              }
            });
            //disini ajax

        } else if (currentMode === "buku") {
            document.getElementById("scan-result-buku").value = decodedText;
            hasilBuku.textContent = decodedText;
            alert("Buku terdeteksi: " + decodedText);
        } else {
            alert("Pilih mode dulu sebelum scan!");
        }
    }

    function onScanError(err) {
        // console.warn(err); // bisa diaktifkan untuk debug
    }

    function startScanner(mode) {
        currentMode = mode;
        modeText.textContent = "Mode: Scan " + (mode === "anggota" ? "Anggota" : "Buku");

        if (scanner) {
            scanner.clear().then(() => {
                scanner.render(onScanSuccess, onScanError);
            }).catch(err => console.error(err));
        } else {
            scanner = new Html5QrcodeScanner(qrReaderId, { fps: 10, qrbox: 250 });
            scanner.render(onScanSuccess, onScanError);
        }
    }

    // Tombol mode
    document.getElementById("mode-anggota").addEventListener("click", function () {
        startScanner("anggota");
    });

    document.getElementById("mode-buku").addEventListener("click", function () {
        startScanner("buku");
    });
});
</script>


{{-- <div class="space-y-4 mb-6">
    <div>
        <label class="font-semibold">Scan QR Anggota</label>
        <div id="qr-reader-anggota" class="border rounded p-2"></div>
    </div>

    <div>
        <label class="font-semibold">Scan QR Buku</label>
        <div id="qr-reader-buku" class="border rounded p-2"></div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // const scannerAnggota = new Html5QrcodeScanner("qr-reader-anggota", { fps: 10, qrbox: 200 });
        const scannerBuku = new Html5QrcodeScanner("qr-reader-buku", { fps: 10, qrbox: 200 });

        // scannerAnggota.render((text) => {
        //     window.livewire.emit('setAnggota', text);
        //     scannerAnggota.clear();
        // });

        function onScanSuccessAnggota(decodedTextAnggota, decodedResultAnggota){

            // alert(`${decodedTextAnggota}`,decodedResultAnggota);
            console.log("id Anggota : "+`${decodedTextAnggota}`);
            // window.livewire.emit('setBuku', `${decodedText}`);
            // scannerBuku.clear();
        }

        function onScanErrorAnggota(errorMessageAnggota){
            // console.log(errorMessageAnggota);
        }

         function onScanSuccessBuku(decodedTextBuku, decodedResultBuku){

            // alert(`${decodedTextBuku}`,decodedResultBuku);
            console.log("id Buku: "+`${decodedTextBuku}`);
            // window.livewire.emit('setBuku', `${decodedText}`);
            // scannerBuku.clear();
        }

        function onScanErrorBuku(errorMessageBuku){
            // console.log(errorMessageBuku);
        }

        // scannerBuku.render(onScanSuccess, onScanError) => {
        //     window.livewire.emit('setBuku', text);
        //     scannerBuku.clear();
        // });

        scannerAnggota.render(onScanSuccessAnggota, onScanErrorAnggota)
        scannerBuku.render(onScanSuccessBuku, onScanErrorBuku);
        ;
    });
</script> --}}

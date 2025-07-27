<div>
    <div id="reader" style="width: 300px;"></div>
    
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const qrReader = new Html5Qrcode("reader");

            function onScanSuccess(decodedText) {
                console.log("QR Detected:", decodedText);

                // Cek prefix → misal QR anggota = A123, QR buku = B456
                if (decodedText.startsWith('A')) {
                    Livewire.emit('setScanAnggotaId', decodedText.replace('A', ''));
                } else if (decodedText.startsWith('B')) {
                    Livewire.emit('setScanBukuId', decodedText.replace('B', ''));
                }

                qrReader.stop().then(() => {
                    console.log("Scanner stopped");
                });
            }

            qrReader.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                onScanSuccess
            );
        });
    </script>
</div>

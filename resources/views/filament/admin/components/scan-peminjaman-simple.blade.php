<div
    x-data="scanPeminjamanSimple()"
    x-init="init()"
    class="space-y-4 mb-6"
>
    <div>
        <label class="font-semibold">Scan QR Anggota</label>
        <div
            id="qr-reader-anggota"
            class="border rounded p-2 min-h-[220px] flex items-center justify-center text-sm text-gray-500 transition"
            :class="anggotaLocked ? 'bg-gray-100 text-gray-400 pointer-events-none opacity-60' : ''"
        ></div>
        <template x-if="anggotaLocked">
            <button type="button" class="mt-2 text-xs px-2 py-1 rounded bg-gray-200 hover:bg-gray-300" @click="resetAnggotaScan()">Scan Ulang Anggota</button>
        </template>
    </div>

    <div>
        <label class="font-semibold">Scan QR Buku</label>
        <div
            id="qr-reader-buku"
            class="border rounded p-2 min-h-[220px] flex items-center justify-center text-sm text-gray-500 transition"
            :class="bukuLocked ? 'bg-gray-100 text-gray-400 pointer-events-none opacity-60' : ''"
        ></div>
        <template x-if="bukuLocked">
            <button type="button" class="mt-2 text-xs px-2 py-1 rounded bg-gray-200 hover:bg-gray-300" @click="resetBukuScan()">Scan Ulang Buku</button>
        </template>
    </div>
</div>

@once
    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            function scanPeminjamanSimple() {
                return {
                    scannerAnggota: null,
                    scannerBuku: null,
                    anggotaLocked: false,
                    bukuLocked: false,

                    init() {
                        const elAnggota = document.getElementById('qr-reader-anggota');
                        const elBuku = document.getElementById('qr-reader-buku');
                        if (!elAnggota || !elBuku) return;

                        this.scannerAnggota = new Html5QrcodeScanner('qr-reader-anggota', { fps: 10, qrbox: 200 });
                        this.scannerBuku    = new Html5QrcodeScanner('qr-reader-buku', { fps: 10, qrbox: 200 });

                        this.scannerAnggota.render((decodedText) => {
                            if (this.anggotaLocked) return;
                            const kode = this.parseCode(decodedText);
                            Livewire.emit('scan-anggota', kode);
                            this.anggotaLocked = true;
                        });

                        this.scannerBuku.render((decodedText) => {
                            if (this.bukuLocked) return;
                            const kode = this.parseCode(decodedText);
                            Livewire.emit('scan-buku', kode);
                            this.bukuLocked = true;
                        });

                        window.addEventListener('lock-scan-anggota', () => this.anggotaLocked = true);
                        window.addEventListener('unlock-scan-anggota', () => this.anggotaLocked = false);
                        window.addEventListener('lock-scan-buku', () => this.bukuLocked = true);
                        window.addEventListener('unlock-scan-buku', () => this.bukuLocked = false);
                    },

                    resetAnggotaScan() {
                        this.anggotaLocked = false;
                        Livewire.emit('unlockScanAnggota');
                    },

                    resetBukuScan() {
                        this.bukuLocked = false;
                        Livewire.emit('unlockScanBuku');
                    },

                    parseCode(raw) {
                        try {
                            const obj = JSON.parse(raw);
                            return obj.id ?? obj.kode ?? raw;
                        } catch (e) {
                            return raw;
                        }
                    },
                }
            }
        </script>
    @endpush
@endonce

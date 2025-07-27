<div>
  

    <canvas id="chartPeminjaman" height="120"></canvas>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('chartPeminjaman').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Dipinjam',
                            data: @json($peminjamanData),
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        },
                        {
                            label: 'Dikembalikan',
                            data: @json($pengembalianData),
                            backgroundColor: 'rgba(75, 192, 192, 0.7)',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Grafik Peminjaman & Pengembalian Tahun {{ now()->year }}'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>

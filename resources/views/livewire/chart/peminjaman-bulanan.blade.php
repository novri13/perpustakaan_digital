<div>
    {{-- Include Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <canvas id="chart" width="400" height="200"></canvas>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('chart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Peminjaman Buku',
                        data: @json($data),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
            });
        });
    </script>
</div>

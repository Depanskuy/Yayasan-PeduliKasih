<?php
// file:///c:/laragon/www/Project_PGAI/app/Views/home/index.php
$layout = 'main';
$title  = 'Dashboard Yayasan';
?>
<h2 class="mb-4">Statistik Ringkas</h2>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h3><?= $totalDonations ?></h3>
            <p>Donasi Terkumpul (transaksi)</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h3><?= $totalCampaigns ?></h3>
            <p>Kampanye Aktif</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h3><?= $totalBeneficiaries ?></h3>
            <p>Penerima Manfaat</p>
        </div>
    </div>
</div>

<hr class="my-5">

<h3>Grafik Donasi Bulanan</h3>
<canvas id="donationChart" width="400" height="150"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch('/api/donations/monthly')
    .then(r => r.json())
    .then(data => {
        const ctx = document.getElementById('donationChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d=>d.month),
                datasets: [{
                    label: 'Total Donasi (Rp)',
                    data: data.map(d=>d.total),
                    borderColor: 'var(--secondary-color)',
                    backgroundColor: 'rgba(255,152,0,0.2)',
                    tension: .3,
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero:true }
                }
            }
        });
    });
</script>
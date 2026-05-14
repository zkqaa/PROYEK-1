/* ============================================
   DASHBOARD JS - Mie Ayam Hijau Admin
   ============================================ */

// ---- SALES CHART (hanya satu instance) ----
const salesCanvas = document.getElementById('salesChart');

if (salesCanvas) {
    const labels = (typeof salesLabels !== 'undefined' && salesLabels.length > 0)
        ? salesLabels : ['Belum ada data'];
    const data = (typeof salesData !== 'undefined' && salesData.length > 0)
        ? salesData : [0];

    new Chart(salesCanvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penjualan',
                data: data,
                backgroundColor: '#78b13a',
                borderRadius: 10,
                barThickness: 28,
                hoverBackgroundColor: '#5a9228'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#222',
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#777', font: { size: 12, weight: '600' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#eeeeee' },
                    ticks: {
                        color: '#777',
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                            if (value >= 1000)    return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });
}

// ---- SAVE REPORT BUTTON ----
const saveBtn = document.querySelector('.chart-report-btn');
if (saveBtn) {
    saveBtn.addEventListener('click', function() {
        const canvas = document.getElementById('salesChart');
        if (!canvas) return;
        const link = document.createElement('a');
        link.download = 'chart-penjualan.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}

// ---- MODAL MENU TERLARIS ----
function openMenuModal() {
    const modal = document.getElementById('menuModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeMenuModal() {
    const modal = document.getElementById('menuModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Tutup saat klik luar modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('menuModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeMenuModal();
        });
    }
});

// Tutup dengan ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMenuModal();
});

// ---- ADMIN DROPDOWN ----
function toggleAdminDropdown() {
    const dd    = document.getElementById('adminDropdown');
    const arrow = document.getElementById('adminArrow');
    if (!dd) return;
    dd.classList.toggle('show');
    if (arrow) arrow.classList.toggle('rotated');
}

document.addEventListener('click', function(e) {
    const box = document.querySelector('.admin-box');
    if (box && !box.contains(e.target)) {
        const dd    = document.getElementById('adminDropdown');
        const arrow = document.getElementById('adminArrow');
        if (dd)    dd.classList.remove('show');
        if (arrow) arrow.classList.remove('rotated');
    }
});

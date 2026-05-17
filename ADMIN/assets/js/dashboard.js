/* ============================================
   DASHBOARD JS
============================================ */

const salesCanvas = document.getElementById('salesChart');

/* =========================
   CHART
========================= */

if (salesCanvas) {

    const labels =
        (typeof salesLabels !== 'undefined' && salesLabels.length > 0)
        ? salesLabels
        : ['Belum Ada Data'];

    const data =
        (typeof salesData !== 'undefined' && salesData.length > 0)
        ? salesData
        : [0];

    new Chart(salesCanvas, {
        type: 'line',

        data: {
            labels: labels,

            datasets: [{
                label: 'Penjualan',
                data: data,

                borderColor: '#4e9829',
                backgroundColor: 'rgba(78,152,41,0.15)',

                fill: true,
                tension: 0.4,
                borderWidth: 3,

                pointBackgroundColor: '#4e9829',
                pointRadius: 4
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    callbacks: {
                        label: function(context){
                            return 'Rp ' +
                                context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },

            scales: {

                x: {
                    grid: {
                        display:false
                    },

                    ticks:{
                        color:'#666'
                    }
                },

                y: {
                    beginAtZero:true,

                    ticks:{
                        color:'#666',

                        callback:function(value){

                            return 'Rp ' +
                                value.toLocaleString('id-ID');

                        }
                    },

                    grid:{
                        color:'#eeeeee'
                    }
                }

            }
        }
    });
}

/* =========================
   SAVE REPORT
========================= */

const saveBtn = document.querySelector('.chart-report-btn');

if(saveBtn){

    saveBtn.addEventListener('click', function(){

        const canvas = document.getElementById('salesChart');

        if(!canvas) return;

        const link = document.createElement('a');

        link.download = 'chart-penjualan.png';
        link.href = canvas.toDataURL('image/png');

        link.click();

    });

}

/* =========================
   MODAL MENU
========================= */

function openMenuModal(){

    const modal = document.getElementById('menuModal');

    if(modal){
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

}

function closeMenuModal(){

    const modal = document.getElementById('menuModal');

    if(modal){
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

}

document.addEventListener('DOMContentLoaded', function(){

    const modal = document.getElementById('menuModal');

    if(modal){

        modal.addEventListener('click', function(e){

            if(e.target === modal){
                closeMenuModal();
            }

        });

    }

});

document.addEventListener('keydown', function(e){

    if(e.key === 'Escape'){
        closeMenuModal();
    }

});

/* =========================
   ADMIN DROPDOWN
========================= */

function toggleAdminDropdown(){

    const dd = document.getElementById('adminDropdown');
    const arrow = document.getElementById('adminArrow');

    if(!dd) return;

    dd.classList.toggle('show');

    if(arrow){
        arrow.classList.toggle('rotated');
    }

}

document.addEventListener('click', function(e){

    const box = document.querySelector('.admin-box');

    if(box && !box.contains(e.target)){

        const dd = document.getElementById('adminDropdown');
        const arrow = document.getElementById('adminArrow');

        if(dd){
            dd.classList.remove('show');
        }

        if(arrow){
            arrow.classList.remove('rotated');
        }

    }

});
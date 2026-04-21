const inputNama = document.getElementById('inputNama');
const inputNomor = document.getElementById('inputNomor');
const inputAlamat = document.getElementById('inputAlamat');
const inputDetailAlamat = document.getElementById('inputDetailAlamat');
const inputCatatan = document.getElementById('inputCatatan');
const btnTerapkanAlamat = document.getElementById('btnTerapkanAlamat');

const namaPelangganTampil = document.getElementById('namaPelangganTampil');
const nomorPelangganTampil = document.getElementById('nomorPelangganTampil');
const alamatTampil = document.getElementById('alamatTampil');
const mapFrame = document.getElementById('mapFrame');

const checkoutOrderBody = document.getElementById('checkoutOrderBody');
const paymentSummary = document.getElementById('paymentSummary');

const confirmCheckbox = document.getElementById('confirmOrder');
const btnBeli = document.getElementById('btnBeli');

let debounceTimer = null;

/* alamat */
function saveAddressData() {
  localStorage.setItem('checkout_nama', inputNama.value);
  localStorage.setItem('checkout_nomor', inputNomor.value);
  localStorage.setItem('checkout_alamat', inputAlamat.value);
  localStorage.setItem('checkout_detail_alamat', inputDetailAlamat.value);
  localStorage.setItem('checkout_catatan', inputCatatan.value);
}

function loadAddressData() {
  const nama = localStorage.getItem('checkout_nama');
  const nomor = localStorage.getItem('checkout_nomor');
  const alamat = localStorage.getItem('checkout_alamat');
  const detailAlamat = localStorage.getItem('checkout_detail_alamat');
  const catatan = localStorage.getItem('checkout_catatan');

  if (nama !== null) inputNama.value = nama;
  if (nomor !== null) inputNomor.value = nomor;
  if (alamat !== null) inputAlamat.value = alamat;
  if (detailAlamat !== null) inputDetailAlamat.value = detailAlamat;
  if (catatan !== null) inputCatatan.value = catatan;
}

function buildFullAddress() {
  const alamatUtama = inputAlamat.value.trim();
  const detailAlamat = inputDetailAlamat.value.trim();

  if (alamatUtama === '') return '';

  if (detailAlamat !== '') {
    return alamatUtama + ', ' + detailAlamat;
  }

  return alamatUtama;
}

function updateMapLive() {
  const fullAddress = buildFullAddress();
  if (fullAddress === '') return;

  const encodedAddress = encodeURIComponent(fullAddress);
  mapFrame.src = `https://maps.google.com/maps?q=${encodedAddress}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
  alamatTampil.textContent = fullAddress;
}

function updateTextInfo() {
  namaPelangganTampil.textContent = inputNama.value.trim() || 'Nama pelanggan';
  nomorPelangganTampil.textContent = inputNomor.value.trim() || '(+62) 812 3456 7890';
}

[inputNama, inputNomor, inputAlamat, inputDetailAlamat, inputCatatan].forEach((input) => {
  input.addEventListener('input', function () {
    saveAddressData();
  });
});

inputNama.addEventListener('input', updateTextInfo);
inputNomor.addEventListener('input', updateTextInfo);

inputAlamat.addEventListener('input', function () {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    updateMapLive();
    saveAddressData();
  }, 500);
});

inputDetailAlamat.addEventListener('input', function () {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    updateMapLive();
    saveAddressData();
  }, 500);
});

btnTerapkanAlamat.addEventListener('click', function () {
  updateTextInfo();
  updateMapLive();
  saveAddressData();
});

/* pesanan live */
function formatNumber(angka) {
  return new Intl.NumberFormat('id-ID').format(angka);
}

function formatRupiah(angka) {
  return 'Rp. ' + formatNumber(angka);
}

function renderOrder(data) {
  if (data.is_empty) {
    checkoutOrderBody.innerHTML = `
      <div class="empty-order">
        Belum ada pesanan.
      </div>
    `;
  } else {
    let itemsHtml = '';

    data.cart.forEach(item => {
      itemsHtml += `
        <div class="order-item">
          <img src="${item.gambar}" alt="${item.nama}">

          <div class="order-info">
            <h4>${item.nama}</h4>
            <p>${formatRupiah(item.harga)}</p>

            <div class="order-qty">
              <button type="button" class="qty-btn order-action-btn" data-action="minus" data-id="${item.id}">-</button>
              <strong>${item.qty}</strong>
              <button type="button" class="qty-btn order-action-btn" data-action="plus" data-id="${item.id}">+</button>
            </div>
          </div>

          <button type="button" class="delete-btn order-action-btn" data-action="remove" data-id="${item.id}">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      `;
    });

    checkoutOrderBody.innerHTML = `
      <div class="order-list">
        ${itemsHtml}
      </div>

      <div class="add-more-box">
        <div>
          <small>Apakah ada tambahan menu?</small><br>
          <span>Masih bisa menambah menu lainnya loh</span>
        </div>
        <a href="beranda.php" class="add-more-btn">Tambah +</a>
      </div>
    `;
  }

  paymentSummary.innerHTML = `
    <div class="payment-row">
      <span>Harga</span>
      <span>${formatNumber(data.cart_total)}</span>
    </div>

    <div class="payment-row">
      <span>Biaya penanganan dan pengiriman</span>
      <span>${formatNumber(data.ongkir)}</span>
    </div>

    <hr>

    <div class="payment-total">
      <strong>Total Pembayaran</strong>
      <strong>${formatNumber(data.grand_total)}</strong>
    </div>
  `;
}

async function checkoutCartRequest(action, id = null) {
  saveAddressData();

  const formData = new URLSearchParams();
  formData.append('action', action);
  if (id !== null) formData.append('id', id);

  try {
    const response = await fetch('checkout_cart_api.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: formData.toString()
    });

    const data = await response.json();

    if (data.success) {
      renderOrder(data);
    }
  } catch (error) {
    console.error('Checkout cart error:', error);
  }
}

checkoutOrderBody.addEventListener('click', function (e) {
  const btn = e.target.closest('.order-action-btn');
  if (!btn) return;

  const action = btn.dataset.action;
  const id = btn.dataset.id;

  if (action === 'remove') {
    const ok = confirm('Hapus item ini?');
    if (!ok) return;
  }

  checkoutCartRequest(action, id);
});

confirmCheckbox.addEventListener('change', function () {
  if (this.checked) {
    btnBeli.classList.add('active');
    btnBeli.disabled = false;
  } else {
    btnBeli.classList.remove('active');
    btnBeli.disabled = true;
  }
});

document.addEventListener('click', function(e){
  const btn = e.target.closest('.qty-btn');
  if(!btn) return;

  btn.classList.add('bounce');

  setTimeout(()=>{
    btn.classList.remove('bounce');
  }, 200);
});

window.addEventListener('load', function () {
  loadAddressData();
  updateTextInfo();
  updateMapLive();
});
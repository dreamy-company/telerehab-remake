import './bootstrap';
import NiceSelect from "nice-select2";
import Toastify from 'toastify-js'
import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';
document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('.select2');
    selects.forEach((select) => {
        new NiceSelect(select);
    });
});

Livewire.on('select2-rehab', () => {
    const select2 = document.getElementsByClassName('select2-rehab');
    if (select2[0]) {
        // Remove existing NiceSelect instance if it exists
        const existingSelect = select2[0].nextElementSibling;

        Livewire.dispatch('select2-rehab-initialized');
        setTimeout(() => {
            if (existingSelect) {
                console.log(existingSelect);
            }
            new NiceSelect(select2[0], {
                searchable: true,
                classNameOpen: 'nice-select-open'
            });
            // Adjust position upward
            const niceSelectDropdown = select2[0].nextElementSibling;
            if (niceSelectDropdown) {
                niceSelectDropdown.style.marginTop = '-5px';
            }
        }, 500);
    }
});

window.intlTelInput = intlTelInput;

Livewire.on('toaster-info', (message) => {
    const messageContent = message[0];
    Toastify({
        text: messageContent,
        duration: 3000,
        close: true,
        closeStyle: "color: white;",
        gravity: "bottom",
        position: "right",
        
        style: {
            background: "linear-gradient(135deg, #3490dc 0%, #2779bd 100%)",
            borderRadius: "8px",
            boxShadow: "0 4px 12px rgba(52, 144, 220, 0.3)"
        }
    }).showToast();

});

Livewire.on('toaster-success', (message) => {
    const messageContent = message[0];
    Toastify({
        text: messageContent,
        duration: 3000,
        close: true,
        closeStyle: "color: white;",
        gravity: "bottom",
        position: "right",
        
        style: {
            background: "linear-gradient(135deg, #38c172 0%, #2d995b 100%)",
            borderRadius: "8px",
            boxShadow: "0 4px 12px rgba(56, 193, 114, 0.3)"
        }
    }).showToast();

});

Livewire.on('toaster-error', (message) => {
    const messageContent = message[0];
    Toastify({
        text: messageContent,
        duration: 3000,
        close: true,
        closeStyle: "color: white;",
        gravity: "bottom",
        position: "right",
        
        style: {
            background: "linear-gradient(135deg, #e3342f 0%, #cc1f1a 100%)",
            borderRadius: "8px",
            boxShadow: "0 4px 12px rgba(227, 52, 47, 0.3)"
        }
    }).showToast();

});

/*
|------------------------------------------------------------------------------
| Penjaga Ukuran File Upload
|------------------------------------------------------------------------------
|
| Batas ukuran dibaca dari <meta name="upload-max-bytes"> yang diisi oleh
| config/upload.php, jadi angkanya cuma diatur di satu tempat.
|
| Tanpa penjaga ini file yang kebesaran tetap dikirim penuh ke server lalu baru
| ditolak — atau lebih buruk, mati di layer PHP karena post_max_size terlampaui
| tanpa pesan apa pun. Listener dipasang di document pada fase CAPTURE supaya
| jalan sebelum listener milik Livewire pada input, sehingga upload benar-benar
| bisa dibatalkan sebelum satu byte pun terkirim.
|
*/

const uploadMaxBytes = Number(
    document.querySelector('meta[name="upload-max-bytes"]')?.content ?? 0
);
const uploadMaxMb = Math.round(uploadMaxBytes / 1024 / 1024);

function uploadToastError(message) {
    Toastify({
        text: message,
        duration: 6000,
        close: true,
        closeStyle: "color: white;",
        gravity: "bottom",
        position: "right",
        style: {
            background: "linear-gradient(135deg, #e3342f 0%, #cc1f1a 100%)",
            borderRadius: "8px",
            boxShadow: "0 4px 12px rgba(227, 52, 47, 0.3)"
        }
    }).showToast();
}

document.addEventListener('change', (event) => {
    const input = event.target;

    if (!uploadMaxBytes || !(input instanceof HTMLInputElement) || input.type !== 'file') {
        return;
    }

    const oversized = Array.from(input.files ?? []).filter((file) => file.size > uploadMaxBytes);

    if (oversized.length === 0) {
        return;
    }

    // Hentikan event sebelum sampai ke listener Livewire pada input.
    event.stopImmediatePropagation();
    event.preventDefault();
    input.value = '';

    const sizeMb = (bytes) => (bytes / 1024 / 1024).toFixed(1);

    uploadToastError(
        oversized.length === 1
            ? `"${oversized[0].name}" berukuran ${sizeMb(oversized[0].size)} MB. Maksimal ${uploadMaxMb} MB per file.`
            : `${oversized.length} file melebihi batas ${uploadMaxMb} MB dan tidak diupload.`
    );
}, true);

// Jaring pengaman kalau file lolos pengecekan di atas tapi tetap ditolak server
// (mis. koneksi putus, atau endpoint temporary upload Livewire menolaknya).
// Sebelumnya kegagalan ini tidak terlihat sama sekali pada input gambar.
document.addEventListener('livewire-upload-error', () => {
    uploadToastError(
        `Upload gagal. Pastikan ukuran file di bawah ${uploadMaxMb} MB dan koneksi stabil, lalu coba lagi.`
    );
});

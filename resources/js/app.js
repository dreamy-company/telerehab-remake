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
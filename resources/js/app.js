import './bootstrap';
import NiceSelect from "nice-select2";
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
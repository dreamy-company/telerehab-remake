import './bootstrap';
import NiceSelect from "nice-select2";
document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('.select2');
    selects.forEach((select) => {
        new NiceSelect(select);
    });
});
import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

window.$ = window.jQuery = require('jquery');
window.Swal = require('sweetalert2');

// CoreUI
require('@coreui/coreui');

// Boilerplate
require('../plugins');

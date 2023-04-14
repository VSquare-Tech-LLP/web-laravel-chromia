import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()

window.$ = window.jQuery = require('jquery');
window.Swal = require('sweetalert2');

// CoreUI
window.coreui = require('@coreui/coreui');

// Boilerplate
require('../plugins');

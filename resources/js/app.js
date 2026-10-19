import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal; // disponible en cualquier <script> de las vistas Blade

Alpine.start();
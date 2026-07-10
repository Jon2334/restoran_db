<?php
// PHP di Vercel (serverless) tidak mempertahankan $_SESSION di memori antar request dengan file-based session.
// Secara default PHP menyimpan session dalam file (/tmp), tapi karena /tmp di serverless bersifat stateless dan tidak persisten antar request, maka saat pindah halaman session akan kosong.
// Vercel tidak bisa menggunakan native PHP session dengan baik tanpa session storage eksternal (seperti database / redis), ATAU JWT (JSON Web Token) di dalam cookie.

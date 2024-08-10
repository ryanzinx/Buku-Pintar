

// Fungsi untuk mendapatkan salam berdasarkan waktu
function getGreeting() {
    var date = new Date();
    var hours = date.getHours();
    var greeting;

    if (hours < 12) {
        greeting = "Selamat Pagi, ";
    } else if (hours >= 12 && hours < 15) {
        greeting = "Selamat Siang, ";
    } else if (hours >= 15 && hours < 18) {
        greeting = "Selamat Sore, ";
    } else {
        greeting = "Selamat Malam, ";
    }

    // Set teks salam ke elemen dengan id "greeting"
    $('#greeting').html(greeting + " Mau Baca Buku Apa?");
}

// Fungsi untuk mengupdate informasi waktu real-time
function updateRealTimeInfo() {
    var date = new Date();
    var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    var months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    var day = days[date.getDay()];
    var dayOfMonth = date.getDate();
    var month = months[date.getMonth()];
    var year = date.getFullYear();
    var hour = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();

    // Format jam menit detik agar selalu dua digit
    hour = (hour < 10 ? "0" : "") + hour;
    minute = (minute < 10 ? "0" : "") + minute;
    second = (second < 10 ? "0" : "") + second;

    // Membuat teks informasi waktu
    var timeInfo = "Hari ini " + day + ", " + dayOfMonth + " " + month + " " + year + "<br>" + hour + ":" + minute + ":" + second;

    // Menambahkan informasi zona waktu berdasarkan offset
    var timezoneOffset = date.getTimezoneOffset();
    var timezoneInfo = "GMT" + (timezoneOffset > 0 ? "-" : "+") + Math.abs(timezoneOffset / 60); // Misalnya: GMT+7 untuk WIB

    // Set teks informasi waktu ke elemen dengan id "real-time"
    $('#real-time').html(timeInfo + " " + timezoneInfo);

    // Perbarui informasi setiap detik
    setTimeout(updateRealTimeInfo, 1000);
}

// Memanggil fungsi getGreeting() dan updateRealTimeInfo() saat dokumen siap
$(document).ready(function() {
    getGreeting();
    updateRealTimeInfo();
});


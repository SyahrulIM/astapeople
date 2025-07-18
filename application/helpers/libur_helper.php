<?php
function cek_libur_nasional($tanggal)
{
    $cache_file = APPPATH . 'cache/libur_nasional.json';
    $tahun = date('Y', strtotime($tanggal));

    // Manual override untuk tahun 2025
    if ($tahun == '2025') {
        $manual2025 = [
            '2025-01-01' => 'Tahun Baru Masehi',
            '2025-01-27' => 'Isra Mikraj',
            '2025-01-29' => 'Tahun Baru Imlek 2576 Kongzili',
            '2025-03-29' => 'Hari Suci Nyepi',
            '2025-03-31' => 'Hari Raya Idul Fitri 1446 H',
            '2025-04-01' => 'Hari Raya Idul Fitri 1446 H (Hari Kedua)',
            '2025-04-18' => 'Wafat Isa Almasih',
            '2025-05-01' => 'Hari Buruh Internasional',
            '2025-05-12' => 'Hari Raya Waisak 2569 BE',
            '2025-05-29' => 'Kenaikan Isa Almasih',
            '2025-06-01' => 'Hari Lahir Pancasila',
            '2025-06-06' => 'Hari Raya Idul Adha 1446 H',
            '2025-06-09' => 'Cuti Bersama Idul Adha',
            '2025-06-27' => 'Tahun Baru Islam 1447 H',
            '2025-08-17' => 'Hari Kemerdekaan Republik Indonesia',
            '2025-09-05' => 'Maulid Nabi Muhammad SAW',
            '2025-12-25' => 'Hari Raya Natal',
            '2025-12-26' => 'Cuti Bersama Natal'
        ];
        return $manual2025[$tanggal] ?? false;
    }

    // Cek cache jika bukan tahun 2025
    if (!file_exists($cache_file)) {
        file_put_contents($cache_file, json_encode([]));
    }

    $cached = json_decode(file_get_contents($cache_file), true);

    // Kalau tahun belum ada di cache, ambil dari API (opsional)
    if (!isset($cached[$tahun])) {
        $url = 'https://date.nager.at/api/v3/PublicHolidays/' . $tahun . '/ID';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) return false;

        $liburs = json_decode($response, true);
        if (!is_array($liburs)) return false;

        foreach ($liburs as $libur) {
            $tanggal_libur = $libur['date'];
            $nama_libur = $libur['localName'];
            $cached[$tahun][$tanggal_libur] = $nama_libur;
        }

        file_put_contents($cache_file, json_encode($cached));
    }

    return $cached[$tahun][$tanggal] ?? false;
}
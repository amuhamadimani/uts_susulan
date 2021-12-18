<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <title>Document</title>
</head>

<?php


$arr_kode_booking = ["AL02102", "BG03025", "CR02111", "KM03075"];

$arr_data_kamar = [
    [
        "kode" => "AL",
        "nama_kamar" => "Alamanda",
        "harga" => 450000
    ],
    [
        "kode" => "BG",
        "nama_kamar" => "Bougenvile",
        "harga" => 350000
    ],
    [
        "kode" => "CR",
        "nama_kamar" => "Crysan",
        "harga" => 375000
    ],
    [
        "kode" => "KM",
        "nama_kamar" => "Kemuning",
        "harga" => 425000
    ]
];

$arr_jenis_pembayaran = [
    1 => 'Kartu Kredit',
    2 => 'Debit',
    3 => 'Cash'
];


function get_data_kamar($data, $array)
{

    $arr = [];

    foreach ($array as $key => $value) {
        if ($value['kode'] == $data) {
            $arr = $value;
        }
    }

    return $arr;
}

function rupiah($data)
{
    return number_format($data, 0, ".", ".");
}

function hitung_potongan_or_tambahan($data_harga, $jenis_pembayaran)
{
    $arr = [
        'type' => '',
        'data_harga' => $data_harga,
        'total_type' => 0,
        'total_harga' => $data_harga
    ];

    if ($jenis_pembayaran == 1) {

        $sum_data = $data_harga * 2 / 100;

        $arr['type'] = 'Tambahan';
        $arr['total_type'] = $sum_data;
        $arr['total_harga'] = $data_harga + $sum_data;
    } elseif ($jenis_pembayaran == 2) {

        $sum_data = $data_harga * 1.5 / 100;

        $arr['type'] = 'Potongan';
        $arr['total_type'] = $sum_data;
        $arr['total_harga'] = $data_harga - $sum_data;
    }

    return $arr;
}


$nama = '';
$nama_kamar = '';
$nomor_kamar = 0;
$lama = 0;
$kode_booking = '';
$lantai = 0;
$total_biaya = 0;
$jumlah = 0;
$biaya_spring_bed_tambahan = 0;
$jenis_pembayaran = '';
$type = '';
$total_type = 0;


if (isset($_POST['button_proses'])) {

    $data_kode_kamar = substr($_POST['kode_booking'], 0, 2);
    $data_nomor_kamar = substr($_POST['kode_booking'], 2, 2);
    $data_lantai = substr($_POST['kode_booking'], 4, 3);

    $data_kamar = get_data_kamar($data_kode_kamar, $arr_data_kamar);
    $harga_kamar = isset($data_kamar['harga']) ? $data_kamar['harga'] : 0;

    $jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : 0;

    $nama_kamar = isset($data_kamar['nama_kamar']) ? $data_kamar['nama_kamar'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $nomor_kamar = $data_nomor_kamar;
    $kode_booking = isset($_POST['kode_booking']) ? $_POST['kode_booking'] : '';
    $lantai = $data_lantai;
    $lama = isset($_POST['lama']) ? $_POST['lama'] : '';
    $jenis_pembayaran_post = isset($_POST['jenis_pembayaran']) ? $_POST['jenis_pembayaran'] : '';
    $jenis_pembayaran = $arr_jenis_pembayaran[$jenis_pembayaran_post];

    $biaya_spring_bed_tambahan = $jumlah > 2 ? 7500 * $jumlah : 0;

    $potongan_or_tambahan = hitung_potongan_or_tambahan($harga_kamar, $jenis_pembayaran_post);

    $total_biaya = $potongan_or_tambahan['total_harga'] + $biaya_spring_bed_tambahan;

    $type = $potongan_or_tambahan['type'];
    $total_type = $potongan_or_tambahan['total_type'];
}

?>

<body>
    <div class="container w-75 mt-5">
        <h4>Form Input</h4>
        <form class="form" method="POST">
            <div class="row mt-4">
                <div class="col">
                    <label class="form-label">Nama</label>
                    <input name="nama" type="text" class="form-control" placeholder="Nama" value="<?php echo isset($_POST['nama']) ? $_POST['nama'] : '' ?>" required>
                </div>
                <div class="col">
                    <label class="form-label">Lama</label>
                    <div class="input-group">
                        <input name="lama" type="text" class="form-control" placeholder="Lama" aria-label="Lama" value="<?php echo isset($_POST['lama']) ? $_POST['lama'] : '' ?>" required>
                        <span class="input-group-text" id="basic-addon2">Hari</span>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-6">
                    <label class="form-label">Kode Booking</label>
                    <select class="form-select" name="kode_booking" required>
                        <option>Pilih Kode Booking</option>
                        <?php
                        foreach ($arr_kode_booking as $key => $value) {
                        ?>
                            <option value="<?php echo $value ?>" <?php if (isset($_POST['kode_booking'])) echo $_POST['kode_booking'] == $value ? "selected" : '' ?>>
                                <?php echo $value ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <label class="form-label">Jumlah</label>
                    <div class="input-group">
                        <input name="jumlah" type="number" class="form-control" placeholder="Jumlah" aria-label="Jumlah" value="<?php echo isset($_POST['jumlah']) ? $_POST['jumlah'] : '' ?>" required>
                        <span class="input-group-text" id="basic-addon2">Orang</span>
                    </div>
                </div>
                <div class="col">
                    <label class="form-label">Jenis Pembayaran</label>
                    <select class="form-select" name="jenis_pembayaran" required>
                        <option>Pilih Jenis Pembayaran</option>
                        <?php
                        foreach ($arr_jenis_pembayaran as $key => $value) {
                        ?>
                            <option value="<?php echo $key ?>" <?php if (isset($_POST['jenis_pembayaran'])) echo $_POST['jenis_pembayaran'] == $key ? "selected" : '' ?>>
                                <?php echo $value ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col float-end">
                    <button class="btn btn-success" type="submit" name="button_proses">Proses</button>
                    <a href="uts_susulan.php" class="btn btn-outline-danger">Hapus</a>
                </div>
            </div>
        </form>

        <hr class="m-5">

        <div class="output">
            <h4 class="text-center pb-5">Florensia Hotel</h4>
            <div class="row">
                <div class="col-6">
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Nama</label>
                        <div class="col">
                            <input type="text" name="nama" class="form-control" readonly value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Nama Kamar</label>
                        <div class="col">
                            <input type="text" name="nama_kamar" class="form-control" readonly value="<?php echo $nama_kamar ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Nomor</label>
                        <div class="col">
                            <input type="text" name="nomor" class="form-control" readonly value="<?php echo $nomor_kamar ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Lama</label>
                        <div class="col">
                            <div class="input-group">
                                <input type="text" class="form-control" aria-label="Jumlah" readonly value="<?php echo $lama ?>">
                                <span class="input-group-text" id="basic-addon2">Hari</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Potongan/Tambahan</label>
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon2"><?php echo $type ?></span>
                                <input type="text" class="form-control" aria-label="potongan" readonly value="<?php echo $total_type ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Total Biaya</label>
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon2">Rp.</span>
                                <input name="total_biaya" type="number" class="form-control" placeholder="Jumlah" aria-label="Jumlah" readonly value="<?php echo rupiah($total_biaya) ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Kode Booking</label>
                        <div class="col">
                            <input type="text" name="nama" class="form-control" readonly value="<?php echo $kode_booking ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Lantai</label>
                        <div class="col">
                            <input type="text" name="nama" class="form-control" readonly value="<?php echo $lantai ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Jumlah</label>
                        <div class="col">
                            <div class="input-group">
                                <input type="number" class="form-control" aria-label="Jumlah" readonly value="<?php echo $jumlah ?>">
                                <span class="input-group-text" id="basic-addon2">Orang</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Jenis Pembayaran</label>
                        <div class="col">
                            <input type="text" name="nama" class="form-control" readonly value="<?php echo $jenis_pembayaran ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-4 col-form-label">Biaya Spring Bed Tambahan</label>
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon2">Rp.</span>
                                <input type="number" class="form-control" placeholder="Jumlah" aria-label="Jumlah" readonly value="<?php echo rupiah($biaya_spring_bed_tambahan) ?>">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</body>

</html>
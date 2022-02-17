<?php
    include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterima_id_mps="";
    if (isset($_GET["pesandikirim"])) {
        $pesan_diterima = $_GET["pesandikirim"];
        $pesan_diterima_id_mps = $_GET['id_mps'];
    }

?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form
        $inputIDProduksi = htmlentities(strip_tags(trim($_POST["inputIDProduksi"])));
        $inputIDMPS = htmlentities(strip_tags(trim($_POST["inputIDMPS"])));
        $inputIDPO = htmlentities(strip_tags(trim($_POST["inputIDPO"])));
        $inputIDProduk = htmlentities(strip_tags(trim($_POST["inputIDProduk"])));
        $inputNamaProduk = htmlentities(strip_tags(trim($_POST["inputNamaProduk"])));
        $inputTanggalProduksi = htmlentities(strip_tags(trim($_POST["inputTanggalProduksi"])));
        $inputQty = htmlentities(strip_tags(trim($_POST["inputQty"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));
        $inputTanggalMulaisatu = htmlentities(strip_tags(trim($_POST["inputTanggalMulaisatu"])));
        $inputTanggalMulaidua = htmlentities(strip_tags(trim($_POST["inputTanggalMulaidua"])));
        $inputTanggalMulaitiga = htmlentities(strip_tags(trim($_POST["inputTanggalMulaitiga"])));
        $inputTanggalMulaiempat = htmlentities(strip_tags(trim($_POST["inputTanggalMulaiempat"])));
        $inputTanggalSelesaisatu = htmlentities(strip_tags(trim($_POST["inputTanggalSelesaisatu"])));
        $inputTanggalSelesaidua = htmlentities(strip_tags(trim($_POST["inputTanggalSelesaidua"])));
        $inputTanggalSelesaitiga = htmlentities(strip_tags(trim($_POST["inputTanggalSelesaitiga"])));
        $inputTanggalSelesaiempat = htmlentities(strip_tags(trim($_POST["inputTanggalSelesaiempat"])));
        $inputQtysatu = htmlentities(strip_tags(trim($_POST["inputQtysatu"])));
        $inputQtydua = htmlentities(strip_tags(trim($_POST["inputQtydua"])));
        $inputQtytiga = htmlentities(strip_tags(trim($_POST["inputQtytiga"])));
        $inputQtyempat = htmlentities(strip_tags(trim($_POST["inputQtyempat"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna="";
        $penanda="";

        $query = "SELECT * FROM schedule_produksi WHERE id_mps='$inputIDMPS' and tanggal_produksi='$inputTanggalProduksi'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);

        $queryidprod = "SELECT * FROM schedule_produksi WHERE id_produksi='$inputIDProduksi'";
        $resultidprod = mysqli_query($link, $queryidprod);
        $jumlah_dataprod = mysqli_num_rows($resultidprod);

        $tanggalmulai= [$inputTanggalMulaisatu, $inputTanggalMulaidua, $inputTanggalMulaitiga, $inputTanggalMulaiempat];
        $tanggalselesai= [$inputTanggalSelesaisatu, $inputTanggalSelesaidua, $inputTanggalSelesaitiga, $inputTanggalSelesaiempat];
        $hasiltanggal=array();
        //untuk menghitung jumlah qty per periode pada schedule produksi
        for ($i=0; $i < count($tanggalmulai); $i++) { 
            $query2 = "SELECT sum(jumlah_produksi) as jumlah_produksi FROM schedule_produksi WHERE id_mps='$inputIDMPS' and tanggal_produksi between '$tanggalmulai[$i]' and '$tanggalselesai[$i]'";
            $result2 = mysqli_query($link, $query2);
            $hasil2 = mysqli_fetch_assoc($result2);
            $hasiltanggal[]=$hasil2['jumlah_produksi'];
        }

        if ($inputIDMPS=="0") {
            $pesan .="ID MPS harus diisi.";
        }
                
        else if ($jumlah_dataprod>=1) {
            $pesan .="ID Produksi yang sama sudah digunakan.";
            $penanda="iya";
        }

        else if ($inputTanggalProduksi=="") {
            $pesan .="Tanggal Produksi harus diisi.";
            $penanda="iya";
        }
        //agar range tanggal sesuai dengan yang telah ditentukan di MPS
        else if ($inputTanggalProduksi<$inputTanggalMulaisatu or $inputTanggalProduksi>$inputTanggalSelesaiempat or 
        ($inputTanggalProduksi>$inputTanggalSelesaisatu and $inputTanggalProduksi<$inputTanggalMulaidua) or 
        ($inputTanggalProduksi>$inputTanggalSelesaidua and $inputTanggalProduksi<$inputTanggalMulaitiga) or 
        ($inputTanggalProduksi>$inputTanggalSelesaitiga and $inputTanggalProduksi<$inputTanggalMulaiempat)) {
            $pesan .="Tanggal Produksi harus sesuai dengan kurun waktu yang telah ditentukan pada MPS.";
            $penanda="iya";
        }


        else if ($jumlah_data >= 1 ) {
            $pesan .= "Tanggal yang sama sudah digunakan. ";  
            $penanda="iya";
        }

        else if ($inputQty=="") {
            $pesan .="Qty harus diisi.";
            $penanda="iya";
        }

        else if ($inputTanggalProduksi>=$inputTanggalMulaisatu and $inputTanggalProduksi<=$inputTanggalSelesaisatu) {
            if ($inputQty+$hasiltanggal[0]>$inputQtysatu) {
                $pesan .="Total Qty minggu ke-1 pada schedule produksi tidak boleh melebihi Qty minggu ke-1 pada MPS.";
                $penanda="iya";
            }
        }

        else if ($inputTanggalProduksi>=$inputTanggalMulaidua and $inputTanggalProduksi<=$inputTanggalSelesaidua) {
            if ($inputQty+$hasiltanggal[1]>$inputQtydua) {
                $pesan .="Total Qty minggu ke-2 pada schedule produksi tidak boleh melebihi Qty minggu ke-2 pada MPS.";
                $penanda="iya";
            }
        }

        else if ($inputTanggalProduksi>=$inputTanggalMulaitiga and $inputTanggalProduksi<=$inputTanggalSelesaitiga) {
            if ($inputQty+$hasiltanggal[2]>$inputQtytiga) {
                $pesan .="Total Qty minggu ke-3 pada schedule produksi tidak boleh melebihi Qty minggu ke-3 pada MPS.";
                $penanda="iya";
            }
        }

        else if ($inputTanggalProduksi>=$inputTanggalMulaiempat and $inputTanggalProduksi<=$inputTanggalSelesaiempat) {
            if ($inputQty+$hasiltanggal[3]>$inputQtyempat) {
                $pesan .="Total Qty minggu ke-4 pada schedule produksi tidak boleh melebihi Qty minggu ke-4 pada MPS.";
                $penanda="iya";
            }
        }

        if ($pesan==="") {
            //jalankan query insert
            
            $query = "insert into schedule_produksi values ('$inputIDMPS','$inputIDProduksi', '$inputIDProduk', '$inputTanggalProduksi', '$inputQty', '$inputUOM')";
            $hasil = mysqli_query($link, $query);

            $query2 = "insert into pengendalian_produksi values ('$inputIDProduksi', 'Belum divalidasi', 'Belum diproduksi')";
            $hasil2 = mysqli_query($link, $query2);

            if ($hasil) {
                $pesan .="Schedule produksi berhasil ditambahkan.";
                $warna = "hijau";

                
                //untuk generate id produksi
                $query="select max(id_produksi) as id_produksi_terbesar from schedule_produksi";
                $result = mysqli_query($link, $query);
                $hasil = mysqli_fetch_array($result);
                $idscheduleproduksi = $hasil['id_produksi_terbesar'];
                $potonganurutan = (int) substr($idscheduleproduksi,4,5);
                $potonganurutan++;
            
                $hurufdepan = "PROD";
                $idscheduleproduksi = $hurufdepan. sprintf("%05s", $potonganurutan);
                $inputIDProduksi=$idscheduleproduksi;
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {
                //untuk generate id produksi
                $query="select max(id_produksi) as id_produksi_terbesar from schedule_produksi";
                $result = mysqli_query($link, $query);
                $hasil = mysqli_fetch_array($result);
                $idscheduleproduksi = $hasil['id_produksi_terbesar'];
                $potonganurutan = (int) substr($idscheduleproduksi,4,5);
                $potonganurutan++;
            
                $hurufdepan = "PROD";
                $idscheduleproduksi = $hurufdepan. sprintf("%05s", $potonganurutan);
                $inputIDProduksi=$idscheduleproduksi;
                
        if ($pesan_diterima!==""){
            $query=mysqli_query($link, "select*from mps where id_mps='$pesan_diterima_id_mps'");
            $hasil=mysqli_fetch_assoc($query);
            $inputIDMPS=$hasil['id_mps'];
            $inputIDPO= $hasil['id_po'];
            $inputIDProduk= $hasil['id_produk'];
            $inputNamaProduk=$hasil['nama_produk'];
            $inputTanggalProduksi="";
            $inputUOM=$hasil['uom'];
            $inputQty="";

            $inputTanggalMulaisatu= $hasil['tanggal_mulai_periode_1'];
            $inputTanggalMulaidua= $hasil['tanggal_mulai_periode_2'];
            $inputTanggalMulaitiga= $hasil['tanggal_mulai_periode_3'];
            $inputTanggalMulaiempat= $hasil['tanggal_mulai_periode_4'];

            $inputTanggalSelesaisatu= $hasil['tanggal_selesai_periode_1'];
            $inputTanggalSelesaidua= $hasil['tanggal_selesai_periode_2'];
            $inputTanggalSelesaitiga= $hasil['tanggal_selesai_periode_3'];
            $inputTanggalSelesaiempat= $hasil['tanggal_selesai_periode_4'];

            $inputQtysatu= $hasil['qty_periode_1'];
            $inputQtydua= $hasil['qty_periode_2'];
            $inputQtytiga= $hasil['qty_periode_3'];
            $inputQtyempat= $hasil['qty_periode_4'];

            $warna="hijau";
        }
        else {
            $inputIDMPS="";
            $inputNamaProduk="";
            $inputTanggalProduksi="";
            $inputUOM="";
            $inputQty="";
            
            $inputTanggalMulaisatu="";
            $inputTanggalMulaidua="";
            $inputTanggalMulaitiga="";
            $inputTanggalMulaiempat="";

            $inputTanggalSelesaisatu="";
            $inputTanggalSelesaidua="";
            $inputTanggalSelesaitiga="";
            $inputTanggalSelesaiempat="";

            $inputQtysatu="";
            $inputQtydua="";
            $inputQtytiga="";
            $inputQtyempat="";

            $warna="";
        }
        $pesan="";
        $penanda="";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Schedule Produksi</title>
    <?php
    include ("layout/title_import.php");
    ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

    <?php
    include ("layout/sidebar.php");
    ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php
                    include ("layout/topbar.php");
                ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php
                         // tampilkan error jika ada
                        if ($pesan_diterima !== "") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan_diterima<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                        }
                    ?>
                    <?php
                         // tampilkan error jika ada
                        if ($pesan !== "") {
                            if ($warna=="hijau") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                            }
                            else {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-danger alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                            }
                                         
                        }
                    ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Schedule Produksi</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_schedule_produksi.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduksi">ID Produksi</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDProduksi" id="inputIDProduksi" readonly value="<?php echo $inputIDProduksi?>">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDMPS">ID MPS</label>
                                    <div class="col-sm-10">
                                        
                                        <?php
                                        if ($warna=="hijau" or $penanda=="iya") {
                                            echo "<input type=\"text\" class=\"form-control\" name=\"inputIDMPS\" id=\"inputIDMPS\" readonly value=\"$inputIDMPS\">";
                                                                
                                            //hanya berupa trik agar javascript bisa berjalan tanpa error
                                            $jsArray= "var dtMPS= new Array();\n";
                                            $jsArray .= "dtMPS['" . $inputIDMPS . "'] = {idpo:'" . addslashes($inputIDPO) ."',idproduk:'" . addslashes($inputIDProduk) ."',namaproduk:'" . addslashes($inputNamaProduk) . "',
                                                uomproduk:'".addslashes($inputUOM)."',tglmulaiperiode1:'".addslashes($inputTanggalMulaisatu)."',tglmulaiperiode2:'".addslashes($inputTanggalMulaidua)."',
                                                tglmulaiperiode3:'".addslashes($inputTanggalMulaitiga)."',tglmulaiperiode4:'".addslashes($inputTanggalMulaiempat)."',tglselesaiperiode1:'".addslashes($inputTanggalSelesaisatu)."',
                                                tglselesaiperiode2:'".addslashes($inputTanggalSelesaidua)."',tglselesaiperiode3:'".addslashes($inputTanggalSelesaitiga)."',tglselesaiperiode4:'".addslashes($inputTanggalSelesaiempat)."',
                                                qtyperiode1:'".addslashes($inputQtysatu)."',qtyperiode2:'".addslashes($inputQtydua)."',qtyperiode3:'".addslashes($inputQtytiga)."',qtyperiode4:'".addslashes($inputQtyempat)."'};\n";
                                        }
                                        else {
                                            echo "<select class=\"form-control\" name=\"inputIDMPS\" id=\"inputIDMPS\" onchange=\"ubahMPS(this.value)\">";
                                            
                                                include("layout/koneksi.php");
                                                echo"<option value=\"0\" selected>----Pilih----</option>";
                                                $query = "select a.id_mps as id_mps, a.id_po as id_po, a.id_produk as id_produk, a.nama_produk as nama_produk, a.uom as uom,
                                                a.tanggal_mulai_periode_1 as tanggal_mulai_periode_1, a.tanggal_selesai_periode_1 as tanggal_selesai_periode_1, 
                                                a.qty_periode_1 as qty_periode_1, a.tanggal_mulai_periode_2 as tanggal_mulai_periode_2, 
                                                a.tanggal_selesai_periode_2 as tanggal_selesai_periode_2, a.qty_periode_2 as qty_periode_2, 
                                                a.tanggal_mulai_periode_3 as tanggal_mulai_periode_3, a.tanggal_selesai_periode_3 as tanggal_selesai_periode_3, 
                                                a.qty_periode_3 as qty_periode_3, a.tanggal_mulai_periode_4 as tanggal_mulai_periode_4, 
                                                a.tanggal_selesai_periode_4 as tanggal_selesai_periode_4, a.qty_periode_4 as qty_periode_4 
                                                from mps a, po_pk b where a.id_po=b.id_po and b.subcont='tidak'";
                                                $jsArray= "var dtMPS = new Array();\n";

                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_array($result)) {
                                                    echo "<option value=\"$hasil[id_mps]\">$hasil[id_mps]</option>";
                                                    $jsArray .= "dtMPS['" . $hasil['id_mps'] . "'] = {idpo:'" . addslashes($hasil['id_po']) ."',idproduk:'" . addslashes($hasil['id_produk']) ."',namaproduk:'" . addslashes($hasil['nama_produk']) . "',
                                                        uomproduk:'".addslashes($hasil['uom'])."',tglmulaiperiode1:'".addslashes($hasil['tanggal_mulai_periode_1'])."',tglmulaiperiode2:'".addslashes($hasil['tanggal_mulai_periode_2'])."',
                                                        tglmulaiperiode3:'".addslashes($hasil['tanggal_mulai_periode_3'])."',tglmulaiperiode4:'".addslashes($hasil['tanggal_mulai_periode_4'])."',tglselesaiperiode1:'".addslashes($hasil['tanggal_selesai_periode_1'])."',
                                                        tglselesaiperiode2:'".addslashes($hasil['tanggal_selesai_periode_2'])."',tglselesaiperiode3:'".addslashes($hasil['tanggal_selesai_periode_3'])."',tglselesaiperiode4:'".addslashes($hasil['tanggal_selesai_periode_4'])."',
                                                        qtyperiode1:'".addslashes($hasil['qty_periode_1'])."',qtyperiode2:'".addslashes($hasil['qty_periode_2'])."',qtyperiode3:'".addslashes($hasil['qty_periode_3'])."',qtyperiode4:'".addslashes($hasil['qty_periode_4'])."'};\n";
                                                }
                                            
                                            echo "</select>";
                                        }
                                        ?>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDPO">ID PO</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDPO" id="inputIDPO" readonly
                                        <?php
                                                if ($warna=="hijau" or $penanda=="iya") {
                                                    echo "value=\"$inputIDPO\"";
                                                }
                                            ?>
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDProduk" id="inputIDProduk" readonly
                                        <?php
                                                if ($warna=="hijau" or $penanda=="iya") {
                                                    echo "value=\"$inputIDProduk\"";
                                                }
                                            ?>
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaProduk">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaProduk" id="inputNamaProduk" readonly
                                        <?php
                                                if ($warna=="hijau" or $penanda=="iya") {
                                                    echo "value=\"$inputNamaProduk\"";
                                                }
                                            ?>
                                        >
                                    </div>
                                </div>


                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Minggu ke-1</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaisatu" name="inputTanggalMulaisatu" placeholder="Tanggal Mulai" readonly value="<?php echo $inputTanggalMulaisatu ?>">
                                    <input type="text" class="form-control" id="inputTanggalSelesaisatu" name="inputTanggalSelesaisatu" placeholder="Tanggal Selesai" readonly value="<?php echo $inputTanggalSelesaisatu ?>">
                                    <input type="text" class="form-control" id="inputQtysatu" name="inputQtysatu"  placeholder="Qty" min="0" readonly value="<?php echo $inputQtysatu ?>">
                                </div>

                                <div class="input-group mt-2">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Minggu ke-2</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaidua" name="inputTanggalMulaidua" placeholder="Tanggal Mulai" readonly value="<?php echo $inputTanggalMulaidua ?>">
                                    <input type="text" class="form-control" id="inputTanggalSelesaidua" name="inputTanggalSelesaidua" placeholder="Tanggal Selesai" readonly value="<?php echo $inputTanggalSelesaidua ?>">
                                    <input type="text" class="form-control" id="inputQtydua" name="inputQtydua"  placeholder="Qty" min="0" readonly value="<?php echo $inputQtydua ?>">
                                </div>

                                <div class="input-group  mt-2">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Minggu ke-3</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaitiga" name="inputTanggalMulaitiga" placeholder="Tanggal Mulai" readonly value="<?php echo $inputTanggalMulaitiga ?>">
                                    <input type="text" class="form-control" id="inputTanggalSelesaitiga" name="inputTanggalSelesaitiga" placeholder="Tanggal Selesai" readonly value="<?php echo $inputTanggalSelesaitiga ?>">
                                    <input type="text" class="form-control" id="inputQtytiga" name="inputQtytiga"  placeholder="Qty" min="0" readonly value="<?php echo $inputQtytiga ?>">
                                </div>

                                <div class="input-group  mt-2 mb-3">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text">Minggu ke-4</span>
                                    </div>
                                    <input type="text" class="form-control" id="inputTanggalMulaiempat" name="inputTanggalMulaiempat" placeholder="Tanggal Mulai" readonly value="<?php echo $inputTanggalMulaiempat ?>">
                                    <input type="text" class="form-control" id="inputTanggalSelesaiempat" name="inputTanggalSelesaiempat" placeholder="Tanggal Selesai" readonly value="<?php echo $inputTanggalSelesaiempat ?>">
                                    <input type="text" class="form-control" id="inputQtyempat" name="inputQtyempat"  placeholder="Qty" min="0" readonly value="<?php echo $inputQtyempat ?>">
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTanggalProduksi">Tanggal Prod</label>
                                    <div class="col-sm-10 input-group date">
                                    <input type="text" class="form-control datepicker datetimepicker-input" name="inputTanggalProduksi" id="inputTanggalProduksi" data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalProduksi ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputQty">Qty</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="inputQty" id="inputQty" min="0" value="<?php echo $inputQty ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputUOM" id="inputUOM" readonly 
                                        <?php
                                                if ($warna=="hijau" or $penanda=="iya") {
                                                    echo "value=\"$inputUOM\"";
                                                }
                                            ?>
                                        >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-1">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit">Simpan</button>
                                    </div>
                                    <div class="col ml-3">
                                        <button type="button" class="btn btn-dark mt-3 mb-3" data-toggle="modal" data-target="#Selesai">Selesai</button>
                                        <div class="modal fade" id="Selesai" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Peringatan</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah anda sudah selesai dan ingin keluar?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                        <a href="schedule_produksi.php" class="btn btn-primary">Iya</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>


                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID MPS</th>
                                            <th>ID Produksi</th>
                                            <th>ID Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Tanggal Produksi</th>
                                            <th>Jumlah Produksi</th>
                                            <th>UOM</th>
                                            <th>Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if ($inputIDMPS=="") {
                                                
                                            }
                                            else {
                                                include("layout/koneksi.php");
                                            
                                            
                                                $query = "SELECT a.id_mps as id_mps, a.id_produksi as id_produksi, a.id_produk as id_produk, b.nama_produk as nama_produk, 
                                                a.tanggal_produksi as tanggal_produksi, a.jumlah_produksi as jumlah_produksi, a.uom as uom 
                                                FROM schedule_produksi a, produk b where a.id_produk=b.id_produk and a.id_mps='$inputIDMPS'";
                                                $nomor=1;
                                                $result = mysqli_query($link,$query);
                                                while($hasil = mysqli_fetch_assoc($result)){
                                                    echo "<tr>";
                                                    echo "<td>$nomor</td>";
                                                    echo "<td>$hasil[id_mps]</td>";
                                                    echo "<td>$hasil[id_produksi]</td>";
                                                    echo "<td>$hasil[id_produk]</td>";
                                                    echo "<td>$hasil[nama_produk]</td>";
                                                    echo "<td>$hasil[tanggal_produksi]</td>";
                                                    echo "<td>$hasil[jumlah_produksi]</td>";
                                                    echo "<td>$hasil[uom]</td>";
                                                    
                                                    $pengantar=urlencode($hasil['id_produksi']);
                                                    $pengantardua=urlencode($hasil['id_mps']);
                                                    echo "<td><a href=\"hapus_schedule_produksi.php?id_produksi=$pengantar&id_mps=$pengantardua&hapus=tambah_sp\" class=\"btn btn-danger\">Hapus</a></td>";
                                                    echo "</tr>";
                                                    $nomor++;
    
                                                };
                                            }

                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

    
            <?php
            include ("layout/footer.php");
            ?>
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php
    include ("layout/plugin_and_logout_modal.php")
    ?>

    <!--Untuk tanggal-->
    <script type="text/javascript">
            $function () {
                $('.datepicker').datepicker({
                    startDate: '-3d';
                });

            };
        </script>

    <!--Ambil nilai select untuk dimasukkan kedalam textbox-->
    <script>
        <?php echo $jsArray; ?> 

        function ubahMPS(inputIDMPS){
            document.getElementById('inputIDPO').value = dtMPS[inputIDMPS].idpo;
            document.getElementById('inputIDProduk').value = dtMPS[inputIDMPS].idproduk;
            document.getElementById('inputNamaProduk').value = dtMPS[inputIDMPS].namaproduk;
            document.getElementById('inputUOM').value = dtMPS[inputIDMPS].uomproduk;
            document.getElementById('inputTanggalMulaisatu').value = dtMPS[inputIDMPS].tglmulaiperiode1;
            document.getElementById('inputTanggalMulaidua').value = dtMPS[inputIDMPS].tglmulaiperiode2;
            document.getElementById('inputTanggalMulaitiga').value = dtMPS[inputIDMPS].tglmulaiperiode3;
            document.getElementById('inputTanggalMulaiempat').value = dtMPS[inputIDMPS].tglmulaiperiode4;
            document.getElementById('inputTanggalSelesaisatu').value = dtMPS[inputIDMPS].tglselesaiperiode1;
            document.getElementById('inputTanggalSelesaidua').value = dtMPS[inputIDMPS].tglselesaiperiode2;
            document.getElementById('inputTanggalSelesaitiga').value = dtMPS[inputIDMPS].tglselesaiperiode3;
            document.getElementById('inputTanggalSelesaiempat').value = dtMPS[inputIDMPS].tglselesaiperiode4;
            document.getElementById('inputQtysatu').value = dtMPS[inputIDMPS].qtyperiode1;
            document.getElementById('inputQtydua').value = dtMPS[inputIDMPS].qtyperiode2;
            document.getElementById('inputQtytiga').value = dtMPS[inputIDMPS].qtyperiode3;
            document.getElementById('inputQtyempat').value = dtMPS[inputIDMPS].qtyperiode4;
        }

    </script>
</body>

</html>
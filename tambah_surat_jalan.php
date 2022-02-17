<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        $inputID = htmlentities(strip_tags(trim($_POST["inputIDPO"])));
        $inputTanggalPengiriman = htmlentities(strip_tags(trim($_POST["inputTanggalPengiriman"])));
        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna="";

        $query="select max(id_surat_jalan) as id_surat_jalan_terbesar from surat_jalan where id_surat_jalan like 'LIP-%'";
        $result = mysqli_query($link, $query);
        $hasil = mysqli_fetch_array($result);
        $idsuratjalan = $hasil['id_surat_jalan_terbesar'];
        $potonganurutan = (int) substr($idsuratjalan,4,5);
        $potonganurutan++;

        $hurufdepan = "LIP-";
        $idsuratjalan = $hurufdepan. sprintf("%05s", $potonganurutan);

        $query = "SELECT * FROM surat_jalan WHERE id_po='$inputID' and tanggal_pengiriman='$inputTanggalPengiriman'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);

        if ($jumlah_data >= 1 ) {
            $pesan .= "Tanggal atau ID PO yang sama sudah dibuat surat jalan. ";  
            $isi_select= "SELECT * from schedule_delivery where id_po='$inputID' and tanggal_pengiriman='$inputTanggalPengiriman'";
        }

        if ($pesan==="") {
            //jalankan query insert
            $query = "insert into surat_jalan (tanggal_pengiriman, id_po, id_produk, nama_produk, qty, uom) select tanggal_pengiriman, id_po, id_produk, nama_produk, qty, uom from schedule_delivery where id_po='$inputID' and tanggal_pengiriman='$inputTanggalPengiriman'";
            $hasil = mysqli_query($link, $query);
            //untuk mengganti id_sj agar unik
            $query2 = "update surat_jalan set id_surat_jalan='$idsuratjalan' where id_po='$inputID' and tanggal_pengiriman='$inputTanggalPengiriman'";
            $hasil2 = mysqli_query($link, $query2);

            if ($hasil) {
                $pesandikirim .="Surat Jalan berhasil ditambahkan.";
                $pesan_dikirim = urlencode($pesandikirim);
                header("Location: surat_jalan.php?pesandikirim=$pesan_dikirim");
                die();
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else if (isset($_POST["cari"])) {
        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputTanggalPengiriman = htmlentities(strip_tags(trim($_POST["inputTanggalPengiriman"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";
        
        if ($pesan==="") {
            $isi_select = "SELECT * from schedule_delivery where id_po='$inputID' and tanggal_pengiriman='$inputTanggalPengiriman'";
        }
    }
    else {       
        $inputID="";
        $inputTanggalPengiriman="";
        $pesan="";
        $isi_select="";
        $warna="";
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
    <title>Surat Jalan</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Surat Jalan</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_surat_jalan.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID PO</label>
                                    <div class="col-sm-10">
                                    
                                        <?php
                                                echo "<select class=\"form-control\" name=\"inputID\" id=\"inputID\">";
                                                echo "<option value=\"0\">---Pilih---</option>";
                                                $query = "select distinct id_po from schedule_delivery where (id_po, id_produk, tanggal_pengiriman) not in (select id_po, id_produk, tanggal_pengiriman from surat_jalan)";
                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_array($result)) {
                                                    echo "<option value=\"$hasil[id_po]\">$hasil[id_po]</option>";
                                                }
                                                echo "</select>";
                                            
                                        ?>
                                    
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTanggalPengiriman">Tanggal Kirim</label>
                                    <div class="col-sm-10 input-group date">
                                    <input type="text" class="form-control datepicker datetimepicker-input" name="inputTanggalPengiriman" id="inputTanggalPengiriman" data-provide="datepicker" data-date-format="yyyy-mm-dd" value="<?php echo $inputTanggalPengiriman ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                
                                <input type="hidden" name="inputIDPO" value="<?php echo $inputID?>">

                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="cari">Cari</button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Pengiriman</th>
                                                <th>ID PO</th>
                                                <th>ID Produk</th>
                                                <th>Nama Produk</th>
                                                <th>Qty</th>
                                                <th>UOM</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if ($inputID=="") {
                                                    
                                                }
                                                else {
                                                    include("layout/koneksi.php");
                                                
                                                    $query = $isi_select;
                                                    $nomor=1;
                                                    $result = mysqli_query($link,$query);
                                                    while($hasil = mysqli_fetch_assoc($result)){
                                                        echo "<tr>";
                                                        echo "<td>$nomor</td>";
                                                        echo "<td>$hasil[tanggal_pengiriman]</td>";
                                                        echo "<td>$hasil[id_po]</td>";
                                                        echo "<td>$hasil[id_produk]</td>";
                                                        echo "<td>$hasil[nama_produk]</td>";
                                                        echo "<td>$hasil[qty]</td>";
                                                        echo "<td>$hasil[uom]</td>";
                                                        echo "</tr>";
                                                        $nomor++;
                                                    };
                                                }

                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit">Buat Surat Jalan</button>
                                    </div>
                                </div>
                                
                            </form>
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
</body>

</html>
<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");

    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputIDSJ = htmlentities(strip_tags(trim($_POST["inputIDSJ"])));
        $inputTanggalPengiriman = htmlentities(strip_tags(trim($_POST["inputTanggalPengiriman"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna="";

        $idproduk = array();
        $qty = array();
        $uom = array();
        $stokproduk = array();

        $query ="select a.id_po as id_po, a.id_produk as id_produk, a.nama_produk as nama_produk, a.qty as qty,
         a.uom as uom, b.stok as stok from surat_jalan a, stok_produk b where id_surat_jalan='$inputIDSJ' and 
         a.id_produk=b.id_produk";
        $result = mysqli_query($link, $query);
        $i=0;
        while ($hasil=mysqli_fetch_assoc($result)) {
            $idproduk[$i] = $hasil['id_produk'];
            $qty[$i] = $hasil['qty'];
            $uom[$i] = $hasil['uom'];
            $stokproduk[$i] = $hasil['stok'];
            $i++;
        }

        //untuk mengetahui apakah stok produk mencukupi/tidak untuk melakukan proses pengiriman ke customer
        $peringatan=0;
        for ($k=0; $k < count($stokproduk); $k++) { 
            if ($stokproduk[$k] < $qty[$k]) {
                $peringatan = $peringatan + 1;
            }
        }

        if ($peringatan>0) {
            $pesan .="Stok produk saat ini belum tersedia untuk melakukan proses pengiriman ke customer";
        }

        if ($pesan==="") {
            //jalankan query insert
            
            $tanggalterkini= date("Y-m-d");
 
            for ($j=0; $j < count($idproduk); $j++) { 
                $idprodkeluar='OUT'.$inputIDSJ.".".$j;
                $keteranganakhir = $inputID.", ".$inputIDSJ;

                $query2 = "update stok_produk set stok=stok-$qty[$j] where id_produk='$idproduk[$j]'";
                $result2 = mysqli_query($link, $query2);

                $queryhistoribb = "insert into produk_keluar values('$idprodkeluar','$tanggalterkini',
                '$idproduk[$j]',$qty[$j],'$uom[$j]','$keteranganakhir')";
                $resulthistoribb = mysqli_query($link, $queryhistoribb);
            }

            if ($result2) {
                $pesandikirim .="Produk berhasil digunakan.";
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
    else {
        $diterima_id = $_GET["id_surat_jalan"];
        $query="select * from surat_jalan where id_surat_jalan='$diterima_id'";
        $result=mysqli_query($link, $query);
        $hasil=mysqli_fetch_assoc($result);
        $inputID=$hasil["id_po"];
        $inputTanggalPengiriman=$hasil["tanggal_pengiriman"];
        $inputIDSJ = $diterima_id;

        $pesan="";
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
                            <h6 class="m-0 font-weight-bold text-primary">Kurangi Stok Produk</h6>
                        </div>
                        <div class="card-body">
                            <form action="kurangi_stok_produk.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID PO</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputID" name="inputID" readonly value="<?php echo $inputID?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTanggalPengiriman">Tanggal Kirim</label>
                                    <div class="col-sm-10 input-group date">
                                    <input type="text" class="form-control" id="inputTanggalPengiriman" name="inputTanggalPengiriman" readonly value="<?php echo $inputTanggalPengiriman?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDSJ"></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDSJ" id="inputIDSJ" hidden value="<?php echo $inputIDSJ?>">
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
                                                
                                                    $query = "SELECT * from schedule_delivery where id_po='$inputID' and tanggal_pengiriman='$inputTanggalPengiriman'";
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
                                                };

                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3" type="submit" name="submit">Siapkan Produk</button>
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
</body>

</html>
<?php
    include("layout/session.php");
?>

<?php
    $cari_id="";

    if (isset($_GET["cari_id"])) {
        $cari_id = $_GET["cari_id"];
    }

?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form

        $inputCari = htmlentities(strip_tags(trim($_POST["inputCari"])));
        $cari_id = htmlentities(strip_tags(trim($_POST["cari_id"])));
        //menyiapkan variabel untuk pesan error
        $pesan="";
        $pesan_dikirim="";


        if ($inputCari=="0") {
            $pesan .="ID PO harus dipilih.";
        }

        if ($pesan==="") {
            $pesan_dikirim=urlencode($inputCari);
            if ($cari_id=="sd") {
                header("Location: tambah_data_schedule_delivery.php?id_po=$pesan_dikirim");
                die();
            }
            else if ($cari_id=="cetak_sd") {
                header("Location: cetak_schedule_delivery.php?id_po=$pesan_dikirim");
                die();
            }
            else if ($cari_id=="mps") {
                header("Location: tambah_mps.php?id_po=$pesan_dikirim");
                die();
            }
            else if ($cari_id=="cetak_mps") {
                header("Location: cetak_mps.php?id_po=$pesan_dikirim");
                die();
            }
            else if ($cari_id=="tambah_stok_produk") {
                header("Location: tambah_stok_produk.php?id_po=$pesan_dikirim");
                die();
            }

        }
    }
    else {
        $pesan="";
        $pesan_dikirim="";
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
    <title>Cari ID PO</title>
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
                            echo "<div class=\"col-md-12 col-sm-12 alert alert-danger alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                            <span>&times;</span></button></div>";                                         
                        }
                    ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Cari ID PO</h6>
                        </div>
                        <div class="card-body">
                            <form action="cari_id_po.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputCari">ID PO</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="inputCari" id="inputCari">
                                            <?php
                                                include("layout/koneksi.php");
                                                echo"<option value=\"0\" selected>---Pilih----</option>";
                                                if ($cari_id=="mps") {
                                                    $query = "select distinct id_po from po where (id_po, id_produk) not in (select id_po, id_produk from mps)";
                                                }
                                                if ($cari_id=="cetak_mps") {
                                                    $query = "select distinct id_po from mps where (id_po) in (select id_po from po_pk where subcont='iya')";
                                                }
                                                if ($cari_id=="sd") {
                                                    $query = "select distinct id_po from po where (id_po, id_produk) not in (select id_po, id_produk from schedule_delivery)";
                                                }
                                                if ($cari_id=="cetak_sd") {
                                                    $query = "select distinct id_po from schedule_delivery where (id_po) in (select id_po from po_pk where subcont='iya')";
                                                }
                                                if ($cari_id=="tambah_stok_produk") {
                                                    $query = "select id_po from po_pk where subcont='iya'";
                                                }
                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_assoc($result)) {
                                                    echo "<option value=\"$hasil[id_po]\">$hasil[id_po]</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-1">                                   
                                        <button class="btn btn-primary" type="submit" name="submit">Cari</button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="cari_id"></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="cari_id" id="cari_id" hidden value="<?php echo $cari_id?>">
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
<?php
    include("layout/session.php");
?>


<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form

        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputNamaBarang = htmlentities(strip_tags(trim($_POST["inputNamaBarang"])));
        $inputLeadTime = htmlentities(strip_tags(trim($_POST["inputLeadTime"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";

        if ($inputLeadTime=="") {
            $pesan .="Lead Time harus diisi.";
        }

        if ($pesan==="") {
            //jalankan query insert
            $query = "update lead_time set lead_time='$inputLeadTime' where id_barang='$inputID'";
            $hasil = mysqli_query($link, $query);

            if ($hasil) {
                $pesandikirim .="Lead Time dengan ID Barang $inputID berhasil diedit.";
                $pesan_dikirim=urlencode($pesandikirim);
                header("Location: lead_time.php?pesandikirim=$pesan_dikirim");
                die();
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {

        $id_barang=$_GET['id_barang'];

        $result=mysqli_query($link, "select * from lead_time where id_barang='$id_barang'");
        $hasil=mysqli_fetch_assoc($result);

        $inputID=$hasil['id_barang'];
        $inputNamaBarang=$hasil['nama_barang'];
        $inputLeadTime=$hasil['lead_time'];
        $pesan="";
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
    <title>Lead Time</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data  Lead Time</h6>
                        </div>
                        <div class="card-body">
                            <form action="edit_lead_time.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Barang</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaBarang">Nama Barang</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaBarang" id="inputNamaBarang" readonly value="<?php echo $inputNamaBarang ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputLeadTime">Lead Time (jumlah hari)</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="inputLeadTime" id="inputLeadTime" min="0" value="<?php echo $inputLeadTime ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit">Simpan</button>
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
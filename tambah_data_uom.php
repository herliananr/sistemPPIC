<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));
        $inputKeterangan = htmlentities(strip_tags(trim($_POST["inputKeterangan"]))); 

        //menyiapkan variabel untuk pesan error
        $pesan="";


        $query = "SELECT * FROM uom WHERE uom='$inputUOM'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);
        if ($jumlah_data >= 1 ) {
             $pesan .= "UOM yang sama sudah digunakan. ";  
        }

        else if (strlen($inputUOM)>20) {
            $pesan .="UOM hanya diisi maksimal 20 digit.";
        }

        else if (strlen($inputKeterangan)>30) {
            $pesan .="Keterangan hanya bolhe diisi maksimal 30 digit.";
        }

        if ($pesan==="") {
            //jalankan query insert
            $query = "insert into uom values ('$inputUOM', '$inputKeterangan')";
            $hasil = mysqli_query($link, $query);

            if ($hasil) {
                $pesandikirim .="UOM dengan UOM $inputUOM berhasil ditambahkan.";
                $pesan_dikirim=urlencode($pesandikirim);
                header("Location: data_uom.php?pesandikirim=$pesan_dikirim");
                die();
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {
        $inputUOM="";
        $inputKeterangan="";

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
    <title>Data UOM</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Data UOM</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_data_uom.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputUOM" id="inputUOM" required value="<?php echo $inputUOM ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputKeterangan">Keterangan</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputKeterangan" id="inputKeterangan" value="<?php echo $inputKeterangan ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3" type="submit" name="submit">Simpan</button>
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
<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");
    
        $diterima_id = $_GET["id_surat_jalan"];
        $query="select * from surat_jalan where id_surat_jalan='$diterima_id'";
        $result=mysqli_query($link, $query);
        $hasil=mysqli_fetch_assoc($result);
        $inputIDSJ=$diterima_id;
        $inputIDPO=$hasil["id_po"];
        $inputTanggalPengiriman=$hasil["tanggal_pengiriman"];
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

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Lihat Surat Jalan</h6>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDSJ">ID SJ</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputIDSJ" name="inputIDSJ" readonly value="<?php echo $inputIDSJ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDPO">ID PO</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputIDPO" name="inputIDPO" readonly value="<?php echo $inputIDPO?>">
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
                                                if ($inputIDPO=="") {
                                                    
                                                }
                                                else {
                                                    include("layout/koneksi.php");
                                                
                                                    $query = "SELECT * from schedule_delivery where id_po='$inputIDPO' and tanggal_pengiriman='$inputTanggalPengiriman'";
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
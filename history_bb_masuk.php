<?php
include("layout/session.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Histori Bahan Baku Masuk</title>
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

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Bahan Baku Masuk</h6>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID BB Masuk</th>
                                            <th>Tanggal Masuk</th>
                                            <th>ID Bahan Baku</th>
                                            <th>Nama Bahan Baku</th>
                                            <th>Qty Masuk</th>
                                            <th>UOM</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include("layout/koneksi.php");
                                            
                                            $query = "SELECT a.id_bb_masuk as id_bb_masuk, a.tanggal_masuk as tanggal_masuk, a.id_bahan_baku as id_bahan_baku, b.nama_bahan_baku as nama_bahan_baku, a.qty as qty, a.uom as uom, a.keterangan as keterangan from bahan_baku_masuk a, bahan_baku b where a.id_bahan_baku=b.id_bahan_baku";
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);
                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_bb_masuk]</td>";
                                                echo "<td>$hasil[tanggal_masuk]</td>";
                                                echo "<td>$hasil[id_bahan_baku]</td>";
                                                echo "<td>$hasil[nama_bahan_baku]</td>";
                                                echo "<td>$hasil[qty]</td>";
                                                echo "<td>$hasil[uom]</td>";
                                                echo "<td>$hasil[keterangan]</td>";
                                                $nomor++;

                                            };
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

    <!--modal untuk hapus-->


</body>

</html>
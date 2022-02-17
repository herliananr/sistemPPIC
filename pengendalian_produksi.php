<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";

    if (isset($_GET["pesandikirim"])) {
        $pesan_diterima = $_GET["pesandikirim"];
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
    <title>Pengendalian Produksi</title>
    <?php
    include ("layout/title_import.php");
    include ("layout/hak_akses.php");
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

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pengendalian Produksi</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Produksi</th>
                                            <th>ID Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Tanggal Produksi</th>
                                            <th>Jumlah Produksi</th>
                                            <th>UOM</th>
                                            <th>Status Bahan Baku</th>
                                            <th>Status Produksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include("layout/koneksi.php");
                                            
                                            $query = "SELECT a.id_produksi as id_produksi, b.id_produk as id_produk, c.nama_produk as nama_produk, 
                                            b.tanggal_produksi as tanggal_produksi, b.jumlah_produksi as jumlah_produksi, b.uom as uom, a.status_bahan_baku as status_bahan_baku, 
                                            a.status_produksi as status_produksi FROM pengendalian_produksi a, schedule_produksi b, produk c where a.id_produksi=b.id_produksi and b.id_produk=c.id_produk";
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);
                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_produksi]</td>";
                                                echo "<td>$hasil[id_produk]</td>";
                                                echo "<td>$hasil[nama_produk]</td>";
                                                echo "<td>$hasil[tanggal_produksi]</td>";
                                                echo "<td>$hasil[jumlah_produksi]</td>";
                                                echo "<td>$hasil[uom]</td>";

                                                $pengantar=urlencode($hasil['id_produksi']);
                                                if ($hasil['status_bahan_baku']=="Belum divalidasi") {
                                                    echo "<td><a href=\"statusbb_pengendalian_produksi.php?id_produksi=$pengantar\" class=\"btn btn-info $ppicdisabled $productiondisabled $qcdisabled\">$hasil[status_bahan_baku]</a></td>";
                                                    echo "<td><button class=\"btn btn-info\" disabled>$hasil[status_produksi]</a></td>";
                                                }
                                                else if($hasil['status_bahan_baku']=="Sudah divalidasi") {
                                                    if ($hasil['status_produksi'] =="Belum diproduksi" or $hasil['status_produksi'] =="Sedang diproses") {
                                                        echo "<td><button class=\"btn btn-info\" disabled>$hasil[status_bahan_baku]</a></td>";
                                                        echo "<td><a href=\"statusprod_pengendalian_produksi.php?id_produksi=$pengantar\" class=\"btn btn-info $ppicdisabled $warehousedisabled $qcdisabled\">$hasil[status_produksi]</a></td>";
                                                    }
                                                    else if ($hasil['status_produksi'] =="Selesai diproduksi") {
                                                        echo "<td><button class=\"btn btn-info\" disabled>$hasil[status_bahan_baku]</a></td>";
                                                        echo "<td><button class=\"btn btn-info\" disabled>$hasil[status_produksi]</a></td>";
                                                    }
                                                }
                                                
                                                echo "</tr>";
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
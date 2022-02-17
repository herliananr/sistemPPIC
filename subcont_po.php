<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";

    if (isset($_GET["pesandikirim"])) {
        $pesan_diterima = $_GET["pesandikirim"];
    }
    if (isset($_GET["id_po"])){
        include("layout/koneksi.php");

        $pesan_diterima_id = $_GET["id_po"];
        $query2 = "update po_pk set subcont='iya' where id_po='$pesan_diterima_id'";
        $hasil2 = mysqli_query($link, $query2);
        $pesan_diterima ="Data dengan ID PO $pesan_diterima_id dikerjakan pada subcont";
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
    <title>Subcont PO Customer</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Subcont PO Customer</h6>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID PO</th>
                                            <th>Subcont</th>
                                            <th style="<?php echo $purchasingnone?>">Edit</th>
                                            <th style="<?php echo $ppicnone?>">Cetak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include("layout/koneksi.php");
                                            
                                            $query = "SELECT id_po, subcont from po_pk";
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);
                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_po]</td>";
                                                echo "<td>$hasil[subcont]</td>";

                                                
                                                $pengantar=urlencode($hasil['id_po']);
                                                if ($hasil['subcont']=="iya") {
                                                    echo "<td  style=\"$purchasingnone\"><button class=\"btn btn-warning disabled\">Edit</button></td>";
                                                    echo "<td style=\"$ppicnone\"><a href=\"cetak_po_customer.php?id_po=$pengantar\" target=\"_BLANK\" class=\"btn btn-success\"><i class=\"fas fa-print\"></i></a></td>";
                                                }
                                                else {
                                                    echo "<td style=\"$purchasingnone\"><a href=\"subcont_po.php?id_po=$pengantar\" class=\"btn btn-warning\">Edit</a></td>";
                                                    echo "<td style=\"$ppicnone\"><a href=\"cetak_po_customer.php?id_po=$pengantar\" target=\"_BLANK\" class=\"btn btn-success disabled\"><i class=\"fas fa-print\"></i></a></td>";
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
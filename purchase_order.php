<?php
include("layout/session.php");

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
    <title>Purchase Order</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Purchase Order</h6>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Purchase Order</th>
                                            <th>ID MRP</th>
                                            <th>Cetak</th>
                                            <th>Lihat</th>
                                            <th style="<?php echo $purchasingnone?>" >Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            include("layout/koneksi.php");
                                            
                                            $query = "SELECT distinct id_purchase_order, id_mrp FROM purchase_order";
                                            $nomor=1;
                                            $result = mysqli_query($link,$query);
                                            while($hasil = mysqli_fetch_assoc($result)){
                                                echo "<tr>";
                                                echo "<td>$nomor</td>";
                                                echo "<td>$hasil[id_purchase_order]</td>";
                                                echo "<td>$hasil[id_mrp]</td>";

                                                $querycari1 = "SELECT * FROM purchase_order where id_purchase_order='$hasil[id_purchase_order]'";
                                                $resultcari1 = mysqli_query($link, $querycari1);
                                                $hasilcari1 = mysqli_fetch_assoc($resultcari1);
                                            
                                                $inputIDProduk = $hasilcari1['id_produk'];

                                                $querycari2 = mysqli_query($link, "select a.id_bahan_baku as id_bahan_baku, b.nama_bahan_baku as nama_bahan_baku, 
                                                a.tanggal_penerimaan as tanggal_penerimaan, a.qty as qty, b.uom_pemakaian_bb as uom 
                                                from purchase_order a, bom b where a.id_bahan_baku=b.id_bahan_baku and a.id_purchase_order='$hasil[id_purchase_order]' 
                                                and b.id_produk_bom='$inputIDProduk' and b.keterangan='tidak dibeli'");
                                                $jumlahcari2 = mysqli_num_rows($querycari2);

                                                $pengantar=urlencode($hasil['id_purchase_order']);

                                                if ($jumlahcari2>0) {
                                                    echo "<td><button type=\"button\" class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#Print$pengantar\"><i class=\"fas fa-print\"></i></button>
                                                    <div class=\"modal fade\" id=\"Print$pengantar\" tabindex=\"-1\">
                                                        <div class=\"modal-dialog\">
                                                            <div class=\"modal-content\">
                                                                <div class=\"modal-header\">
                                                                    <h5 class=\"modal-title\">Peringatan</h5>
                                                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class=\"modal-body\">
                                                                    <p>Versi apakah yang ingin dicetak?</p>
                                                                </div>
                                                                <div class=\"modal-footer\">
                                                                <a href=\"cetak_purchase_order.php?id_purchase_order=$pengantar&ket=pembelian\" class=\"btn btn-primary\" target=\"_BLANK\">Purchase Order</a>
                                                                <a href=\"cetak_purchase_order.php?id_purchase_order=$pengantar&ket=subcontbb\" class=\"btn btn-primary\" target=\"_BLANK\">Purchase Order (Subcont BB)</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>";
                                                }
                                                else {
                                                    echo "<td><a href=\"cetak_purchase_order.php?id_purchase_order=$pengantar&ket=pembelian\" target=\"_BLANK\" class=\"btn btn-success\"><i class=\"fas fa-print\"></i></a></td>";
                                                    
                                                }

                                                if ($jumlahcari2>0) {
                                                    echo "<td><button type=\"button\" class=\"btn btn-info\" data-toggle=\"modal\" data-target=\"#Lihat$pengantar\">Lihat</button>
                                                    <div class=\"modal fade\" id=\"Lihat$pengantar\" tabindex=\"-1\">
                                                        <div class=\"modal-dialog\">
                                                            <div class=\"modal-content\">
                                                                <div class=\"modal-header\">
                                                                    <h5 class=\"modal-title\">Peringatan</h5>
                                                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\"><span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class=\"modal-body\">
                                                                    <p>Versi apakah yang ingin dilihat?</p>
                                                                </div>
                                                                <div class=\"modal-footer\">
                                                                <a href=\"lihat_purchase_order.php?id_purchase_order=$pengantar&ket=pembelian\" class=\"btn btn-primary\" target=\"_BLANK\">Purchase Order</a>
                                                                <a href=\"lihat_purchase_order.php?id_purchase_order=$pengantar&ket=subcontbb\" class=\"btn btn-primary\" target=\"_BLANK\">Purchase Order (Subcont BB)</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>";
                                                }
                                                else {
                                                    echo "<td><a href=\"lihat_purchase_order.php?id_purchase_order=$pengantar&ket=pembelian\" target=\"_BLANK\" class=\"btn btn-info\">Lihat</a></td>";
                                                    
                                                }

                                                echo "<td style=\"$purchasingnone\" ><a href=\"hapus_purchase_order.php?id_purchase_order=$pengantar\" class=\"btn btn-danger\">Hapus</a></td>";
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
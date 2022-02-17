<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");
    
    $diterima_id = $_GET['id_mrp'];
    $query = "SELECT * FROM mrp where id_mrp='$diterima_id'";
    $result = mysqli_query($link, $query);
    $hasil = mysqli_fetch_assoc($result);

    $inputID= $hasil['id_mrp'];
    $inputIDMPS= $hasil['id_mps'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MRP</title>
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
                            <h6 class="m-0 font-weight-bold text-primary">Lihat MRP</h6>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID MRP</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDMPS">ID MPS</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputIDMPS" id="inputIDMPS" readonly value="<?php echo $inputIDMPS ?>">
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Level</th>
                                                <th>ID BOM</th>
                                                <th>Periode</th>
                                                <th>Tanggal </th>
                                                <th>ID Bahan Baku</th>
                                                <th>Gross Requirement</th>
                                                <th>Schedule Receipt</th>
                                                <th>Project On Hand</th>
                                                <th>Net Requirement</th>
                                                <th>Planned Order Receipt</th>
                                                <th>Planned Order Release</th>
                                                <th>Tanggal Penerimaan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if ($inputID=="") {
                                                    
                                                }
                                                else {
                                                    include("layout/koneksi.php");
                                                
                                                    $query = "select*from mrp where id_mrp='$inputID' order by level, periode, id_bahan_baku";
                                                    $nomor=1;
                                                    $result = mysqli_query($link,$query);
                                                    while($hasil = mysqli_fetch_assoc($result)){
                                                        echo "<tr>";
                                                        echo "<td>$nomor</td>";
                                                        echo "<td>$hasil[level]</td>";
                                                        echo "<td>$hasil[id_bom]</td>";
                                                        echo "<td>$hasil[periode]</td>";
                                                        echo "<td>$hasil[tanggal]</td>";
                                                        echo "<td>$hasil[id_bahan_baku]</td>";
                                                        echo "<td>$hasil[gross_requirement]</td>";
                                                        echo "<td>$hasil[schedule_receipt]</td>";
                                                        echo "<td>$hasil[project_on_hand]</td>";
                                                        echo "<td>$hasil[net_requirement]</td>";
                                                        echo "<td>$hasil[planned_order_receipt]</td>";
                                                        echo "<td>$hasil[planned_order_release]</td>";
                                                        echo "<td>$hasil[tanggal_penerimaan]</td>";
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
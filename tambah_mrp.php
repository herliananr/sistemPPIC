<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");
    
    if(isset($_POST["proses_mrp"])) {
        // form telah disubmit, proses data
        
        // ambil semua nilai form

        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputIDMPS = htmlentities(strip_tags(trim($_POST["inputIDMPS"])));
        $inputIDPO = htmlentities(strip_tags(trim($_POST["inputIDPO"])));
        $inputIDProduk = htmlentities(strip_tags(trim($_POST["inputIDProduk"])));
        $inputNamaProduk = htmlentities(strip_tags(trim($_POST["inputNamaProduk"])));
        $inputKeputusanStok = htmlentities(strip_tags(trim($_POST["inputKeputusanStok"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna="";
        $penanda="";

        $checkedlsesuai="";
        $checkedtidakmenyertakan="";

        $query = "SELECT * FROM mrp WHERE id_mrp='$inputID'";
        $result = mysqli_query($link, $query);
        $jumlah_data = mysqli_num_rows($result);

        //untuk mencari apakah suatu produk sudah memiliki BOM/belum
        $queryidproduk = "select * from bom where id_produk_bom='$inputIDProduk'";
        $resultidproduk = mysqli_query($link, $queryidproduk);
        $jumlah_idproduk = mysqli_num_rows($resultidproduk);

        if ($jumlah_data >= 1 ) {
            $pesan .= "ID MRP sama sudah digunakan. ";  
            $penanda= "iya";
        }

        else if ($jumlah_idproduk < 1){
            $pesandikirim .=  "Anda belum menyusun Bill of Material untuk produk $inputIDProduk. ";
            $pesan_dikirim = urlencode($pesandikirim);
            header("Location: mrp.php?pesandikirim=$pesan_dikirim&gagal=iya");
            die();
        }

        else if ($inputID==""){
            $pesan .="Mohon untuk mengisi ID MRP";
        }

        else if ($inputIDMPS=="0") {
            $pesan .="ID MPS harus diisi.";
            
        }

        switch ($inputKeputusanStok) {
            case 'sesuai':
                $checkedlsesuai="checked";
                break;
                
            case 'tidakmenyertakan':
                $checkedtidakmenyertakan="checked";
                break;

            default:
                break;

        }

        if ($pesan==="") {
            //jalankan query insert

            $query = "select * from mps where id_mps='$inputIDMPS'";
            $result = mysqli_query($link, $query);
            $hasil = mysqli_fetch_assoc($result);

            $id_mps =$hasil['id_mps'];
            $id_po = $hasil['id_po'];
            $id_produk = $hasil['id_produk'];
            $nama_produk = $hasil['nama_produk'];
            $uom = $hasil['uom'];
            $tgl_mulai_periode_1 = $hasil['tanggal_mulai_periode_1'];
            $tgl_mulai_periode_2 = $hasil['tanggal_mulai_periode_2'];
            $tgl_mulai_periode_3 = $hasil['tanggal_mulai_periode_3'];
            $tgl_mulai_periode_4 = $hasil['tanggal_mulai_periode_4'];
            $qty_periode_1 = (int)$hasil['qty_periode_1'];
            $qty_periode_2 = (int)$hasil['qty_periode_2'];
            $qty_periode_3 = (int)$hasil['qty_periode_3'];
            $qty_periode_4 = (int)$hasil['qty_periode_4'];
   
            //untuk memasukkan nilai stok produk berdasarkan pilihan radio button
            if ($inputKeputusanStok=="sesuai") {
                $query3 = "select * from stok_produk where id_produk='$id_produk'";
                $result3 = mysqli_query($link, $query3);
                $hasil3 = mysqli_fetch_assoc($result3);
                $stok_produk = (int)$hasil3['stok'];
            }
            else if ($inputKeputusanStok=="tidakmenyertakan") {
                $stok_produk = 0;
            }
            

            $query4 = "select * from lead_time where id_barang='$id_produk'";
            $result4 = mysqli_query($link, $query4);
            $hasil4 = mysqli_fetch_assoc($result4);
            $ldproduk = (int)$hasil4['lead_time'];
            
            $stok_produk_2=(int)0;
            $stok_produk_3=(int)0;
            $stok_produk_4=(int)0;
            $stok_produk_5=(int)0;

            if ($qty_periode_1>$stok_produk) {
                $nr1=$qty_periode_1-$stok_produk;
            }
            else if ($qty_periode_1<$stok_produk) {
                $nr1=0;
                $stok_produk_2=$stok_produk-$qty_periode_1;
            }
            else if ($qty_periode_1==$stok_produk) {
                $nr1=0;
                $stok_produk_2=0;
            }

            if ($qty_periode_2>$stok_produk_2) {
                $nr2=$qty_periode_2-$stok_produk_2;
            }
            else if ($qty_periode_2<$stok_produk_2) {
                $nr2=0;
                $stok_produk_3=$stok_produk_2-$qty_periode_2;
            }
            else if ($qty_periode_2==$stok_produk_2) {
                $nr2=0;
                $stok_produk_3=0;
            }

            if ($qty_periode_3>$stok_produk_3) {
                $nr3=$qty_periode_3-$stok_produk_3;
            }
            else if ($qty_periode_3<$stok_produk_3) {
                $nr3=0;
                $stok_produk_4=$stok_produk_3-$qty_periode_3;
            }
            else if ($qty_periode_3==$stok_produk_3) {
                $nr3=0;
                $stok_produk_4=0;
            }

            if ($qty_periode_4>$stok_produk_4) {
                $nr4=$qty_periode_4-$stok_produk_4;
            }
            else if ($qty_periode_4<$stok_produk_4) {
                $nr4=0;
                $stok_produk_5=$stok_produk_4-$qty_periode_4;
            }
            else if ($qty_periode_4==$stok_produk_4) {
                $nr4=0;
                $stok_produk_5=0;
            }

            $por1 = $nr1;
            $por2 = $nr2;
            $por3 = $nr3;
            $por4 = $nr4;
            $tanggal_penerimaan_1 = date ('Y-m-d', strtotime('-'.$ldproduk.' days', strtotime($tgl_mulai_periode_1)));
            $tanggal_penerimaan_2 = date ('Y-m-d', strtotime('-'.$ldproduk.' days', strtotime($tgl_mulai_periode_2)));
            $tanggal_penerimaan_3 = date ('Y-m-d', strtotime('-'.$ldproduk.' days', strtotime($tgl_mulai_periode_3)));
            $tanggal_penerimaan_4 = date ('Y-m-d', strtotime('-'.$ldproduk.' days', strtotime($tgl_mulai_periode_4)));


            $query5 = "insert into mrp values ('$inputID','$id_mps','0','$id_produk','1','$tgl_mulai_periode_1','-','$qty_periode_1','0','$stok_produk','$nr1','$por1','$por1','$tanggal_penerimaan_1')";
            $result5 = mysqli_query($link, $query5);

            $query6 = "insert into mrp values ('$inputID','$id_mps','0','$id_produk','2','$tgl_mulai_periode_2','-','$qty_periode_2','0','$stok_produk_2','$nr2','$por2','$por2','$tanggal_penerimaan_2')";
            $result6 = mysqli_query($link, $query6);

            $query7 = "insert into mrp values ('$inputID','$id_mps','0','$id_produk','3','$tgl_mulai_periode_3','-','$qty_periode_3','0','$stok_produk_3','$nr3','$por3','$por3','$tanggal_penerimaan_3')";
            $result7 = mysqli_query($link, $query7);

            $query8 = "insert into mrp values ('$inputID','$id_mps','0','$id_produk','4','$tgl_mulai_periode_4','-','$qty_periode_4','0','$stok_produk_4','$nr4','$por4','$por4','$tanggal_penerimaan_4')";
            $result8 = mysqli_query($link, $query8);
            //agar tidak mengurangi stok yang sudah ada
            //$query9 = "update stok_produk set stok='$stok_produk_5' where id_produk='$id_produk'";
            //$result9 = mysqli_query($link, $query9);

            $jumlah_array_bb="";
            $satu =1;
            $levelbb=array();
            $id_bb=array();
            $nama_bb=array();
            $stok_bb=array();
            $jumlah_pemakaian_bb=array();
            $uom_pemakaian_bb=array();
            $id_induk=array();
            $porel=array();
            $tgl_beli=array();
            $tgl_beli_akhir=array();
            $gr_bb=array();
            $netr=array();
            $plannedor=array();
            $leadtime=array();
            $stok_bb_simpan=array();

            $query10 = "select a.level as level, a.id_bahan_baku as id_bahan_baku, a.nama_bahan_baku as nama_bahan_baku, a.jml_pemakaian_bb as jml_pemakaian_bb, b.stok as stok, a.uom_pemakaian_bb as uom_pemakaian_bb, a.id_induk as id_induk, c.lead_time as lead_time from bom a, stok_bahan_baku b, lead_time c where id_produk_bom='$id_produk' and a.id_bahan_baku=b.id_bahan_baku and a.id_bahan_baku=c.id_barang order by level asc";
            $result10 = mysqli_query($link, $query10);
            $i=0;
            while ($hasil10 = mysqli_fetch_assoc($result10)){
                $levelbb[$i] = $hasil10['level'];
                $id_bb[$i] = $hasil10['id_bahan_baku'];
                $nama_bb[$i] = $hasil10['nama_bahan_baku'];
                $jumlah_pemakaian_bb[$i] = $hasil10['jml_pemakaian_bb'];

                if ($inputKeputusanStok=="tidakmenyertakan") {
                    $stok_bb[$i] = 0;
                }
                else {
                    $stok_bb[$i] = $hasil10['stok'];
                }
                
                $uom_pemakaian_bb[$i] = $hasil10['uom_pemakaian_bb'];
                $id_induk[$i] = $hasil10['id_induk'];
                $leadtime[$i] = $hasil10['lead_time'];
                $i++;
            }


            $query11 = "select * from mrp where id_mrp='$inputID'";
            $result11 = mysqli_query($link, $query11);
            $k=0;
            while ($hasil11 = mysqli_fetch_assoc($result11)){
                $porel[$k] = $hasil11['planned_order_release'];
                $tgl_beli[$k] = $hasil11['tanggal_penerimaan'];
                $k++;
            }

            
            $jumlah_array_bb = count($id_bb); //untuk menghitung jumlah baris hasil dari kueri diatas
            //untuk periode ke-1
            for ($j=0; $j < $jumlah_array_bb; $j++) { 
                if ($levelbb[$j]=="1") {
                    $gr_bb[$j]=$porel[0]*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_beli[0])));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','1','$tgl_beli[0]','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }

                else if ($levelbb[$j]=="2") {
                    $query12 = mysqli_query($link, "select*from mrp where id_mrp='$inputID' and id_mps='$id_mps' and id_bahan_baku='$id_induk[$j]'");
                    $hasillevel2 = mysqli_fetch_array($query12);

                    $porellevel2 = $hasillevel2['planned_order_release'];
                    $tgl_belilevel2 = $hasillevel2['tanggal_penerimaan'];

                    $gr_bb[$j]=$porellevel2*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_belilevel2)));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','1','$tgl_belilevel2','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }

                else if ($levelbb[$j]=="3") {
                    $query13 = mysqli_query($link, "select*from mrp where id_mrp='$inputID' and id_mps='$id_mps' and id_bahan_baku='$id_induk[$j]'");
                    $hasillevel3 = mysqli_fetch_array($query13);

                    $porellevel3 = $hasillevel3['planned_order_release'];
                    $tgl_belilevel3 = $hasillevel3['tanggal_penerimaan'];

                    $gr_bb[$j]=$porellevel3*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_belilevel3)));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','1','$tgl_belilevel3','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }
            }

            //untuk periode ke-2
            for ($j=0; $j < $jumlah_array_bb; $j++) { 
                if ($levelbb[$j]=="1") {
                    $gr_bb[$j]=$porel[1]*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb_simpan[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb_simpan[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb_simpan[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_beli[1])));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','2','$tgl_beli[1]','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb_simpan[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }

                else if ($levelbb[$j]=="2") {
                    $query12 = mysqli_query($link, "select*from mrp where id_mrp='$inputID' and id_mps='$id_mps' and id_bahan_baku='$id_induk[$j]' and periode='2'");
                    $hasillevel2 = mysqli_fetch_array($query12);

                    $porellevel2 = $hasillevel2['planned_order_release'];
                    $tgl_belilevel2 = $hasillevel2['tanggal_penerimaan'];

                    $gr_bb[$j]=$porellevel2*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb_simpan[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb_simpan[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb_simpan[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_belilevel2)));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','2','$tgl_belilevel2','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb_simpan[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }

                else if ($levelbb[$j]=="3") {
                    $query13 = mysqli_query($link, "select*from mrp where id_mrp='$inputID' and id_mps='$id_mps' and id_bahan_baku='$id_induk[$j]' and periode='2'");
                    $hasillevel3 = mysqli_fetch_array($query13);

                    $porellevel3 = $hasillevel3['planned_order_release'];
                    $tgl_belilevel3 = $hasillevel3['tanggal_penerimaan'];

                    $gr_bb[$j]=$porellevel3*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb_simpan[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb_simpan[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb_simpan[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_belilevel3)));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','2','$tgl_belilevel3','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb_simpan[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }
            }

            //untuk periode ke-3
            for ($j=0; $j < $jumlah_array_bb; $j++) { 
                if ($levelbb[$j]=="1") {
                    $gr_bb[$j]=$porel[2]*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb_simpan[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb_simpan[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb_simpan[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_beli[2])));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','3','$tgl_beli[2]','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb_simpan[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }

                else if ($levelbb[$j]=="2") {
                    $query12 = mysqli_query($link, "select*from mrp where id_mrp='$inputID' and id_mps='$id_mps' and id_bahan_baku='$id_induk[$j]' and periode='3'");
                    $hasillevel2 = mysqli_fetch_array($query12);

                    $porellevel2 = $hasillevel2['planned_order_release'];
                    $tgl_belilevel2 = $hasillevel2['tanggal_penerimaan'];

                    $gr_bb[$j]=$porellevel2*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb_simpan[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb_simpan[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb_simpan[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_belilevel2)));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','3','$tgl_belilevel2','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb_simpan[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }

                else if ($levelbb[$j]=="3") {
                    $query13 = mysqli_query($link, "select*from mrp where id_mrp='$inputID' and id_mps='$id_mps' and id_bahan_baku='$id_induk[$j]' and periode='3'");
                    $hasillevel3 = mysqli_fetch_array($query13);

                    $porellevel3 = $hasillevel3['planned_order_release'];
                    $tgl_belilevel3 = $hasillevel3['tanggal_penerimaan'];

                    $gr_bb[$j]=$porellevel3*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb_simpan[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb_simpan[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb_simpan[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_belilevel3)));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','3','$tgl_belilevel3','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb_simpan[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }
            }

            //untuk periode ke-4
            for ($j=0; $j < $jumlah_array_bb; $j++) { 
                if ($levelbb[$j]=="1") {
                    $gr_bb[$j]=$porel[3]*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb_simpan[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb_simpan[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb_simpan[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_beli[3])));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','4','$tgl_beli[3]','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb_simpan[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }

                else if ($levelbb[$j]=="2") {
                    $query12 = mysqli_query($link, "select*from mrp where id_mrp='$inputID' and id_mps='$id_mps' and id_bahan_baku='$id_induk[$j]' and periode='4'");
                    $hasillevel2 = mysqli_fetch_array($query12);

                    $porellevel2 = $hasillevel2['planned_order_release'];
                    $tgl_belilevel2 = $hasillevel2['tanggal_penerimaan'];

                    $gr_bb[$j]=$porellevel2*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb_simpan[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb_simpan[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb_simpan[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_belilevel2)));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','4','$tgl_belilevel2','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb_simpan[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }

                else if ($levelbb[$j]=="3") {
                    $query13 = mysqli_query($link, "select*from mrp where id_mrp='$inputID' and id_mps='$id_mps' and id_bahan_baku='$id_induk[$j]' and periode='4'");
                    $hasillevel3 = mysqli_fetch_array($query13);

                    $porellevel3 = $hasillevel3['planned_order_release'];
                    $tgl_belilevel3 = $hasillevel3['tanggal_penerimaan'];

                    $gr_bb[$j]=$porellevel3*$jumlah_pemakaian_bb[$j];

                    if ($gr_bb[$j]>$stok_bb_simpan[$j]) {
                        $netr[$j]=$gr_bb[$j]-$stok_bb_simpan[$j];
                        $stok_bb_simpan[$j]=0;
                    }
                    else if ($gr_bb[$j]<$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=$stok_bb_simpan[$j]-$gr_bb[$j];
                    }
                    else if ($gr_bb[$j]==$stok_bb_simpan[$j]) {
                        $netr[$j]=0;
                        $stok_bb_simpan[$j]=0;
                    }

                    $tgl_beli_akhir[$j] = date ('Y-m-d', strtotime('-'.$leadtime[$j].' days', strtotime($tgl_belilevel3)));
                    $plannedor[$j]=$netr[$j];
                    $query = "insert into mrp values ('$inputID','$id_mps','$levelbb[$j]','$id_produk','4','$tgl_belilevel3','$id_bb[$j]','$gr_bb[$j]','0','$stok_bb_simpan[$j]','$netr[$j]','$plannedor[$j]','$plannedor[$j]','$tgl_beli_akhir[$j]')";
                    $result = mysqli_query($link, $query);
                }
            }
            if ($result5) {
                $pesan .="MRP dengan ID $inputID berhasil diproses.";
                $warna = "hijau";
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }
    else {
        include("layout/koneksi.php");
        //deklarasi untuk mps
        $jsArrayMRP= "var dtIDMRP = new Array();\n";
        $query="select max(id_mrp) as id_mrp_terbesar from mrp";
        $result = mysqli_query($link, $query);
        $hasil = mysqli_fetch_array($result);
        $idmrp = $hasil['id_mrp_terbesar'];
        $potonganurutan = (int) substr($idmrp,3,4);
        $potonganurutan++;

        $hurufdepan = "MRP";
        $idmrp = $hurufdepan. sprintf("%04s", $potonganurutan);
        $jsArrayMRP .= "dtIDMRP = {idmrp:'" . $idmrp . "'};\n";

        $inputID="";
        $inputIDMPS="";
        $inputIDPO="";
        $inputIDProduk="";
        $inputNamaProduk="";

        $checkedlsesuai="";
        $checkedtidakmenyertakan="checked";
        
        $warna="";
        $penanda="";
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
                            <h6 class="m-0 font-weight-bold text-primary">Tambah MRP</h6>
                        </div>
                        <div class="card-body">
                            <form action="tambah_mrp.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID MRP</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDMPS">ID MPS</label>
                                    <div class="col-sm-10">
                                        
                                        <?php
                                            if ($warna=="hijau" or $penanda=="iya") {
                                                echo "<input type=\"text\" class=\"form-control\" name=\"inputIDMPS\" id=\"inputIDMPS\" readonly value= \"$inputIDMPS\">";
                                            }
                                            else {
                                                echo "<select class=\"form-control\" name=\"inputIDMPS\" id=\"inputIDMPS\" onchange=\"ubahMPS(this.value)\">";
                                            
                                                include("layout/koneksi.php");
                                                echo"<option value=\"0\" selected>----Pilih----</option>";
                                                $query = "select id_mps, id_po, id_produk, nama_produk from mps where (id_mps, id_po, id_produk, nama_produk) not in (select id_mps, id_po, id_produk, nama_produk from mrp)";
                                                $jsArray= "var dtMPS = new Array();\n";

                                                $result = mysqli_query($link, $query);
                                                while ($hasil=mysqli_fetch_array($result)) {
                                                    echo "<option value=\"$hasil[id_mps]\">$hasil[id_mps]</option>";
                                                    $jsArray .= "dtMPS['" . $hasil['id_mps'] . "'] = {idpo:'".addslashes($hasil['id_po'])."',idproduk:'" . addslashes($hasil['id_produk']) . "',namaproduk:'".addslashes($hasil['nama_produk'])."'};\n";
                                                }
                                            
                                                echo "</select>";
                                            }
                                            
                                        ?>

                                    </div>
                                </div>
                                                               
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDPO">ID PO</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDPO" id="inputIDPO" readonly value="<?php echo $inputIDPO ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID BOM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputIDProduk" id="inputIDProduk" readonly value="<?php echo $inputIDProduk ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputNamaProduk">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="inputNamaProduk" id="inputNamaProduk" readonly value="<?php echo $inputNamaProduk ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Stok</label>
                                    <div class="col-sm-10">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio1" name="inputKeputusanStok" class="custom-control-input" value="sesuai" <?php echo $checkedlsesuai?>>
                                                <label class="custom-control-label" for="customRadio1">Sesuai stok saat ini</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio2" name="inputKeputusanStok" class="custom-control-input" value="tidakmenyertakan" <?php echo $checkedtidakmenyertakan?>>
                                                <label class="custom-control-label" for="customRadio2">Tidak menyertakan stok saat ini</label>
                                            </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-2">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="proses_mrp">Proses MRP</button>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-dark mt-3 mb-3" data-toggle="modal" data-target="#Selesai">Selesai</button>
                                        <div class="modal fade" id="Selesai" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Peringatan</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah anda sudah selesai dan ingin keluar?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                        <a href="mrp.php" class="btn btn-primary">Iya</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>ID MPS</th>
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
                                                <th>Tanggal Penerimaan/Produksi</th>
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
                                                        echo "<td>$hasil[id_mps]</td>";
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

    <!--Untuk tanggal-->
        <script type="text/javascript">
            $function () {
                $('.datepicker').datepicker({
                    startDate: '-3d';
                });

            };
        </script>

    <!--Ambil nilai select untuk dimasukkan kedalam textbox-->
    <!--Ambil nilai ID MRP-->
    <script>
        <?php echo $jsArray; echo $jsArrayMRP; ?> 

        document.getElementById('inputID').value = dtIDMRP.idmrp;

        function ubahMPS(inputIDMPS){
            document.getElementById('inputIDPO').value = dtMPS[inputIDMPS].idpo;
            document.getElementById('inputIDProduk').value = dtMPS[inputIDMPS].idproduk;
            document.getElementById('inputNamaProduk').value = dtMPS[inputIDMPS].namaproduk;
        }

    </script>
</body>

</html>
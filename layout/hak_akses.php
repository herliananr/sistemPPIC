<?php
    include ("layout/koneksi.php");
              
    //untuk menghilangkan kolom
    $ppicnone="";
    $purchasingnone="";
    $warehousenone="";
    $productionnone="";
    $qcnone="";
    
    //untuk disabled tombol, namun tombol tersebut masih terlibat
    $ppicdisabled="";
    $warehousedisabled="";
    $productiondisabled="";
    $qcdisabled="";
    $disabled="";
    
    $query2 = "select * from karyawan where id_karyawan='$isi_sesi'";
    $result2 = mysqli_query($link, $query2);
    $hasil2 = mysqli_fetch_assoc($result2);
    if ($hasil2['peran'] == "ppic") {
        $ppicnone="display:none";
        $ppicdisabled="disabled";
    }
    else if ($hasil2['peran'] == "purchasing") {
        $purchasingnone="display:none";
    }
    else if ($hasil2['peran'] == "warehouse") {
        $warehousenone="display:none";
        $warehousedisabled="disabled";
    }
    else if ($hasil2['peran'] == "production") {
        $productionnone="display:none";
        $productiondisabled="disabled";
    }
    else if ($hasil2['peran'] == "qc") {
        $qcnone="display:none";
        $qcdisabled="disabled";
    }

?>
<?php
$diterima_id = $_GET["id_bb_keluar"];

require('vendor/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm','Letter');

$pdf->AddPage();

include("layout/session.php");
include("layout/koneksi.php");
$query = "select*from bahan_baku_keluar where id_bb_keluar='$diterima_id'";

$tanggal_keluar=date("Y-m-d"); //untuk mengambil hari ini

$result = mysqli_query($link,$query);
$hasil = mysqli_fetch_assoc($result);
$idbahanbaku = $hasil['id_bahan_baku'];
$qty = $hasil['qty'];
$uom = $hasil['uom'];
$keterangan = $hasil['keterangan'];

$explode_keterangan = explode(", ",$keterangan);

$idpo = $explode_keterangan[0];
$idmps = $explode_keterangan[1];
$tanggalpenerimaan = $explode_keterangan[2];
$namabahanbaku = $explode_keterangan[3];
$warna = $explode_keterangan[4];
$bentuk = $explode_keterangan[5];
$spesifikasi = $explode_keterangan[6];
$keterangansubcont = $explode_keterangan[7];

if ($keterangansubcont == "subcontbahanbaku") {
    $query2 = "select nama_partner, alamat from partner where keterangan like '%ID BB: $idbahanbaku%' and peran='Subcont Bahan Baku'";
    $result2 = mysqli_query($link,$query2);
    $hasil2 = mysqli_fetch_assoc($result2);
}
else if ($keterangansubcont == "subcontproduk") {
    $querycari1 = mysqli_query($link, "select id_produk from mps where id_mps='$idmps'");
    $hasilcari1 = mysqli_fetch_assoc($querycari1);
    $query2 = "select nama_partner, alamat from partner where keterangan like '%ID Produk: $hasilcari1[id_produk]%' and peran='Subcont Produk'";
    $result2 = mysqli_query($link,$query2);
    $hasil2 = mysqli_fetch_assoc($result2);
}

$pdf->SetFont('Times','B',16);
$pdf->Cell(0,7,'PT Limus Indo Persada',0,1,'C');
$pdf->SetFont('Times','U',16);
$pdf->Cell(0,7,'Surat Jalan',0,1,'C');
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,7,'ID Surat Jalan : '.'LIP-'.$diterima_id,0,1,'C');
$pdf->Cell(0,7,'Kepada : '.$hasil2['nama_partner'],0,1,'L');
$pdf->Cell(0,7,'Alamat : '.$hasil2['alamat'],0,1,'L');

if ($keterangansubcont == "subcontproduk") {
    $pdf->Cell(0,7,'ID PO : '.$idpo,0,1,'L');

}

$pdf->Cell(0,7,'Tanggal Keluar : '.$tanggal_keluar,0,1,'L');
$pdf->Cell(0,7,'Tanggal Maksimal Pengiriman : '.$tanggalpenerimaan,0,1,'L');
$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Times','B',10);

$pdf->Cell(8,6,'No',1,0,'C');
$pdf->Cell(50,6,'ID Bahan Baku',1,0,'C');
$pdf->Cell(50,6,'Nama Bahan Baku',1,0,'C');
$pdf->Cell(25,6,'Qty',1,0,'C');
$pdf->Cell(20,6,'UOM',1,0,'C');
$pdf->Cell(30,6,'Warna',1,0,'C');
$pdf->Cell(30,6,'Bentuk',1,0,'C');
$pdf->Cell(40,6,'Spesifikasi',1,1,'C');

$pdf->SetFont('Times','',10);

$pdf->Cell(8,6,'1',1,0);
$pdf->Cell(50,6,$idbahanbaku,1,0);
$pdf->Cell(50,6,$namabahanbaku,1,0);
$pdf->Cell(25,6,$qty,1,0);
$pdf->Cell(20,6,$uom,1,0);
$pdf->Cell(30,6,$warna,1,0);
$pdf->Cell(30,6,$bentuk,1,0);
$pdf->Cell(40,6,$spesifikasi,1,0);


$pdf->Cell(10,7,'',0,1);
$pdf->Cell(10,7,'',0,1);

$query3 = mysqli_query($link, "select*from karyawan where id_karyawan='$isi_sesi'");
$hasil3 = mysqli_fetch_assoc($query3);
$nama_karyawan = $hasil3['nama_karyawan'];
$peran=$hasil3['peran'];

$pdf->SetFont('Times','B',10);
$pdf->Cell(55,6,'Diterima oleh',0,0,'C');
$pdf->Cell(55,6,'Pengirim/Sopir',0,0,'C');
$pdf->Cell(55,6,'Satpam',0,0,'C');
$pdf->Cell(58,6,'Mengetahui',0,1,'C');

for ($i=0; $i < 3; $i++) { 
    $pdf->Cell(55,6,'',0,0,'C');
    $pdf->Cell(55,6,'',0,0,'C');
    $pdf->Cell(55,6,'',0,0,'C');
    $pdf->Cell(58,6,'',0,1,'C');
}

$pdf->Cell(55,6,'(.............................)',0,0,'C');
$pdf->Cell(55,6,'(.............................)',0,0,'C');
$pdf->Cell(55,6,'(.............................)',0,0,'C');
$pdf->Cell(58,6,$nama_karyawan." (".$peran.")",0,1,'C');

$pdf->Output();
?>
<?php
$diterima_id = $_GET['id_po'];

require('vendor/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm','A4');

$pdf->AddPage();
include("layout/session.php");

$pdf->SetFont('Times','B',16);
$pdf->Cell(0,7,'PT Limus Indo Persada',0,1,'C');
$pdf->SetFont('Times','I',14);
$pdf->Cell(0,7,'Schedule Delivery',0,1,'C');
$pdf->Cell(0,7,'',0,1,'C');
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,7,'ID PO : '.$diterima_id,0,1,'L');

$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Times','B',12);
$pdf->Cell(15,6,'No',1,0,'L');
$pdf->Cell(50,6,'ID Produk',1,0,'L');
$pdf->Cell(70,6,'Nama Produk',1,0,'L');
$pdf->Cell(35,6,'Tgl pengiriman',1,0,'L');
$pdf->Cell(20,6,'Qty',1,0,'L');
$pdf->Cell(30,6,'UOM',1,1,'L');
$pdf->SetFont('Times','',12);

include("layout/koneksi.php");

$query = "select*from schedule_delivery where id_po='$diterima_id' order by id_produk, tanggal_pengiriman";
$nomor=1;
$result = mysqli_query($link,$query);
while($hasil = mysqli_fetch_assoc($result)){
    $pdf->Cell(15,6,$nomor,1,0);
    $pdf->Cell(50,6,$hasil['id_produk'],1,0);
    $pdf->Cell(70,6,$hasil['nama_produk'],1,0);
    $pdf->Cell(35,6,$hasil['tanggal_pengiriman'],1,0);
    $pdf->Cell(20,6,$hasil['qty'],1,0);
    $pdf->Cell(30,6,$hasil['uom'],1,1);
    $nomor++;

};

$query2 = mysqli_query($link, "select*from karyawan where id_karyawan='$isi_sesi'");
$hasil2 = mysqli_fetch_assoc($query2);
$nama_karyawan = $hasil2['nama_karyawan'];
$pdf->Cell(10,7,'',0,1);
$pdf->SetFont('Times','B',12);
$pdf->Cell(10,7,'Mengetahui',0,1, 'L');
$pdf->Cell(10,7,'',0,1);
$pdf->Cell(10,7,'',0,1);
$pdf->Cell(10,7,$nama_karyawan,0,1, 'L');

$pdf->Output();
?>
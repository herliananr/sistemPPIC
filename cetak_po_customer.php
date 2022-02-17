<?php
$diterima_id = $_GET['id_po'];

require('vendor/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm','Letter');

$pdf->AddPage();

include("layout/session.php");

$pdf->SetFont('Times','I',16);
$pdf->Cell(0,7,'Purchase Order (PO)',0,1,'C');
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,7,'',0,1,'L');
$pdf->Cell(0,7,'Vendor : PT Limus Indo Persada',0,1,'L');
$pdf->Cell(0,7,'ID PO  :  '.$diterima_id,0,1,'L');
$pdf->SetFont('Times','I',12);
$pdf->Cell(0,7,'',0,1,'C');
$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Times','B',10);

$pdf->Cell(8,6,'No',1,0,'C');
$pdf->Cell(50,6,'ID Produk',1,0,'C');
$pdf->Cell(80,6,'Nama Produk',1,0,'C');
$pdf->Cell(30,6,'Qty',1,0,'C');
$pdf->Cell(30,6,'UOM',1,1,'C');

$pdf->SetFont('Times','',10);

include("layout/koneksi.php");

$query = "select*from po where id_po='$diterima_id'";
$nomor=1;
$result = mysqli_query($link,$query);
while($hasil = mysqli_fetch_assoc($result)){
    $pdf->Cell(8,6,$nomor,1,0);
    $pdf->Cell(50,6,$hasil['id_produk'],1,0);
    $pdf->Cell(80,6,$hasil['nama_produk'],1,0);
    $pdf->Cell(30,6,$hasil['qty'],1,0);
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
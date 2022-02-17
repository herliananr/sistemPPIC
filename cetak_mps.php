<?php
$diterima_id = $_GET['id_po'];

require('vendor/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm','A4');

$pdf->AddPage();

include("layout/session.php");

$pdf->SetFont('Times','B',16);
$pdf->Cell(0,7,'PT Limus Indo Persada',0,1,'C');
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,7,'Master Production Schedule (MPS)',0,1,'C');
$pdf->Cell(0,7,'',0,1,'C');
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,7,'ID PO : '.$diterima_id,0,1,'L');

$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Times','B',6);
$pdf->Cell(5,6,'No',1,0,'L');
$pdf->Cell(30,6,'ID Produk',1,0,'L');
$pdf->Cell(30,6,'Nama Produk',1,0,'L');
$pdf->Cell(17,6,'UOM',1,0,'L');
$pdf->Cell(17,6,'Tgl mulai m1',1,0,'L');
$pdf->Cell(17,6,'Tgl Selesai m1',1,0,'L');
$pdf->Cell(15,6,'Qty  1',1,0,'L');
$pdf->Cell(17,6,'Tgl mulai m2',1,0,'L');
$pdf->Cell(17,6,'Tgl selesai m2',1,0,'L');
$pdf->Cell(15,6,'Qty 2',1,0,'L');
$pdf->Cell(17,6,'Tgl mulai m3',1,0,'L');
$pdf->Cell(17,6,'Tgl selesai m3',1,0,'L');
$pdf->Cell(15,6,'Qty 3',1,0,'L');
$pdf->Cell(17,6,'Tgl mulai m4',1,0,'L');
$pdf->Cell(17,6,'Tgl selesai m4',1,0,'L');
$pdf->Cell(15,6,'Qty 4',1,1,'L');
$pdf->SetFont('Times','',6);

include("layout/koneksi.php");

$query = "select*from mps where id_po='$diterima_id'";
$nomor=1;
$result = mysqli_query($link,$query);
while($hasil = mysqli_fetch_assoc($result)){
    $pdf->Cell(5,6,$nomor,1,0);
    $pdf->Cell(30,6,$hasil['id_produk'],1,0);
    $pdf->Cell(30,6,$hasil['nama_produk'],1,0);
    $pdf->Cell(17,6,$hasil['uom'],1,0);
    $pdf->Cell(17,6,$hasil['tanggal_mulai_periode_1'],1,0);
    $pdf->Cell(17,6,$hasil['tanggal_selesai_periode_1'],1,0);
    $pdf->Cell(15,6,$hasil['qty_periode_1'],1,0);
    $pdf->Cell(17,6,$hasil['tanggal_mulai_periode_2'],1,0);
    $pdf->Cell(17,6,$hasil['tanggal_selesai_periode_2'],1,0);
    $pdf->Cell(15,6,$hasil['qty_periode_2'],1,0);
    $pdf->Cell(17,6,$hasil['tanggal_mulai_periode_3'],1,0);
    $pdf->Cell(17,6,$hasil['tanggal_selesai_periode_3'],1,0);
    $pdf->Cell(15,6,$hasil['qty_periode_3'],1,0);
    $pdf->Cell(17,6,$hasil['tanggal_mulai_periode_4'],1,0);
    $pdf->Cell(17,6,$hasil['tanggal_selesai_periode_4'],1,0);
    $pdf->Cell(15,6,$hasil['qty_periode_4'],1,1);
    $nomor++;

};


$pdf->Cell(0,7,'Ket : tanggal mulai "m1" menandakan tanggal mulai produksi "minggu ke-1"',0,1,'L');

$query2 = mysqli_query($link, "select*from karyawan where id_karyawan='$isi_sesi'");
$hasil2 = mysqli_fetch_assoc($query2);
$nama_karyawan = $hasil2['nama_karyawan'];
$pdf->Cell(10,7,'',0,1);
$pdf->SetFont('Times','B',8);
$pdf->Cell(10,7,'Mengetahui',0,1, 'L');
$pdf->Cell(10,7,'',0,1);
$pdf->Cell(10,7,'',0,1);
$pdf->Cell(10,7,$nama_karyawan,0,1, 'L');

$pdf->Output();
?>
<?php
$diterima_id = $_GET["id_surat_jalan"];

require('vendor/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm','Letter');

$pdf->AddPage();

include("layout/session.php");
include("layout/koneksi.php");
$query = "select*from surat_jalan where id_surat_jalan='$diterima_id'";
$result = mysqli_query($link,$query);
$hasil = mysqli_fetch_assoc($result);

$query2 = "select a.nama_partner as nama_partner, b.alamat as alamat from po_pk a, partner b
 where a.id_partner=b.id_partner and a.id_po='$hasil[id_po]'";
$result2 = mysqli_query($link,$query2);
$hasil2 = mysqli_fetch_assoc($result2);

$pdf->SetFont('Times','B',16);
$pdf->Cell(0,7,'PT Limus Indo Persada',0,1,'C');
$pdf->SetFont('Times','U',16);
$pdf->Cell(0,7,'Surat Jalan',0,1,'C');
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,7,'ID Surat Jalan : '.$diterima_id,0,1,'C');
$pdf->Cell(0,7,'Kepada : '.$hasil2['nama_partner'],0,1,'L');
$pdf->Cell(0,7,'Alamat : '.$hasil2['alamat'],0,1,'L');
$pdf->Cell(0,7,'Tanggal Pengiriman : '.$hasil['tanggal_pengiriman'],0,1,'L');
$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Times','B',10);

$pdf->Cell(8,6,'No',1,0,'C');
$pdf->Cell(50,6,'ID PO',1,0,'C');
$pdf->Cell(50,6,'ID Produk',1,0,'C');
$pdf->Cell(70,6,'Nama Produk',1,0,'C');
$pdf->Cell(15,6,'Qty',1,0,'C');
$pdf->Cell(30,6,'UOM',1,1,'C');

$pdf->SetFont('Times','',10);

$query = "SELECT * from schedule_delivery where id_po='$hasil[id_po]' and tanggal_pengiriman='$hasil[tanggal_pengiriman]'";
$nomor=1;
$result = mysqli_query($link,$query);
while($hasil = mysqli_fetch_assoc($result)){
    $pdf->Cell(8,6,$nomor,1,0);
    $pdf->Cell(50,6,$hasil['id_po'],1,0);
    $pdf->Cell(50,6,$hasil['id_produk'],1,0);
    $pdf->Cell(70,6,$hasil['nama_produk'],1,0);
    $pdf->Cell(15,6,$hasil['qty'],1,0);
    $pdf->Cell(30,6,$hasil['uom'],1,1);
    $nomor++;

};

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
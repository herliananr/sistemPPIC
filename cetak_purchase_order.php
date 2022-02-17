<?php
$diterima_id = $_GET['id_purchase_order'];
$diterima_ket = $_GET['ket'];

require('vendor/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm','Letter');

$pdf->AddPage();
include("layout/session.php");
include("layout/koneksi.php");
$query = "select*from purchase_order where id_purchase_order='$diterima_id'";
$result = mysqli_query($link,$query);
$hasil = mysqli_fetch_assoc($result);

$pdf->SetFont('Times','B',16);
$pdf->Cell(0,7,'PT Limus Indo Persada',0,1,'C');
$pdf->SetFont('Times','I',14);
$pdf->Cell(0,7,'Purchase Order (PO)',0,1,'C');
$pdf->Cell(10,7,'',0,1);
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,7,'ID PO: '.$diterima_id,0,1,'L');
$pdf->Cell(0,7,'ID Produk : '.$hasil['id_produk'],0,1,'L');
$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Times','B',10);

$pdf->Cell(8,6,'No',1,0,'C');
$pdf->Cell(30,6,'Tanggal Penerimaan',1,0,'C');
$pdf->Cell(30,6,'ID Bahan Baku',1,0,'C');
$pdf->Cell(60,6,'Nama Bahan Baku',1,0,'C');
$pdf->Cell(30,6,'Warna',1,0,'C');
$pdf->Cell(30,6,'Bentuk',1,0,'C');
$pdf->Cell(30,6,'Spesifikasi',1,0,'C');
$pdf->Cell(15,6,'Qty',1,0,'C');
$pdf->Cell(30,6,'UOM',1,1,'C');

$pdf->SetFont('Times','',10);

if ($diterima_ket=="pembelian") {
    $query = "select a.id_bahan_baku as id_bahan_baku, b.nama_bahan_baku as nama_bahan_baku, 
a.tanggal_penerimaan as tanggal_penerimaan, a.qty as qty, b.uom_pemakaian_bb as uom, 
c.warna as warna, c.bentuk as bentuk, c.spesifikasi as spesifikasi 
from purchase_order a, bom b, bahan_baku c where a.id_bahan_baku=b.id_bahan_baku and b.id_bahan_baku=c.id_bahan_baku 
and a.id_purchase_order='$diterima_id' and b.id_produk_bom='$hasil[id_produk]' and b.keterangan<>'tidak dibeli'";
}
else if ($diterima_ket=="subcontbb") {
    $query = "select a.id_bahan_baku as id_bahan_baku, b.nama_bahan_baku as nama_bahan_baku, 
a.tanggal_penerimaan as tanggal_penerimaan, a.qty as qty, b.uom_pemakaian_bb as uom, 
c.warna as warna, c.bentuk as bentuk, c.spesifikasi as spesifikasi 
from purchase_order a, bom b, bahan_baku c where a.id_bahan_baku=b.id_bahan_baku and b.id_bahan_baku=c.id_bahan_baku 
and a.id_purchase_order='$diterima_id' and b.id_produk_bom='$hasil[id_produk]' and b.keterangan='tidak dibeli'";
}

$nomor=1;
$result = mysqli_query($link,$query);
while($hasil = mysqli_fetch_assoc($result)){
    $pdf->Cell(8,6,$nomor,1,0);
    $pdf->Cell(30,6,$hasil['tanggal_penerimaan'],1,0, 'C');
    if (substr($hasil['id_bahan_baku'],0,3)=="BB0") {
        $pdf->Cell(30,6,$hasil['id_bahan_baku']."*",1,0);
    }
    else {
        $pdf->Cell(30,6,$hasil['id_bahan_baku'],1,0);
    }
    
    $pdf->Cell(60,6,$hasil['nama_bahan_baku'],1,0);
    $pdf->Cell(30,6,$hasil['warna'],1,0);
    $pdf->Cell(30,6,$hasil['bentuk'],1,0);
    $pdf->Cell(30,6,$hasil['spesifikasi'],1,0);
    $pdf->Cell(15,6,$hasil['qty'],1,0);
    $pdf->Cell(30,6,$hasil['uom'],1,1);
    $nomor++;

};


if ($diterima_ket=="subcontbb") {
    $pdf->SetFont('Times','I',10);
    $pdf->Cell(0,7,'Ket : Lembar ini ditujukan untuk subcont',0,1,'L');

}
else {
    $pdf->SetFont('Times','I',10);
    $pdf->Cell(0,7,'Ket : ID Bahan Baku yang bertanda (*) disediakan oleh customer',0,1,'L');
}



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
<?php
$diterima_id = $_GET['id_produk_bom'];
$diterima_nama = $_GET['nama_produk'];
$diterima_ket = $_GET['ket'];

require('vendor/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm','Letter');

$pdf->AddPage();

$pdf->SetFont('Times','B',16);
$pdf->Cell(0,7,'PT Limus Indo Persada',0,1,'C');
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,7,'Bill of Material (BOM)',0,1,'C');
$pdf->Cell(0,7,'',0,1,'C');
$pdf->SetFont('Times','B',10);
$pdf->Cell(0,7,'ID Produk   : '.$diterima_id,0,1,'L');

$pdf->Cell(0,7,'Nama Produk : '.$diterima_nama,0,1,'L');

$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Times','B',10);

$pdf->Cell(8,6,'No',1,0,'C');
$pdf->Cell(15,6,'Level',1,0,'C');
$pdf->Cell(30,6,'ID Bahan Baku',1,0,'C');
$pdf->Cell(50,6,'Nama Bahan Baku',1,0,'C');
$pdf->Cell(50,6,'Jumlah pemakaian BB',1,0,'C');
$pdf->Cell(50,6,'UOM pemakaian BB',1,0,'C');
$pdf->Cell(30,6,'ID Induk',1,0,'C');
$pdf->Cell(30,6,'Keterangan',1,1,'C');

$pdf->SetFont('Times','',10);

include("layout/koneksi.php");

$query = "select*from bom where id_produk_bom='$diterima_id' order by level, id_bahan_baku";
$nomor=1;
$result = mysqli_query($link,$query);

if ($diterima_ket=="aktual") {
    while($hasil = mysqli_fetch_assoc($result)){
        $pdf->Cell(8,6,$nomor,1,0);
        $pdf->Cell(15,6,$hasil['level'],1,0);
        $pdf->Cell(30,6,$hasil['id_bahan_baku'],1,0);
        $pdf->Cell(50,6,$hasil['nama_bahan_baku'],1,0);
        $pdf->Cell(50,6,$hasil['jml_pemakaian_bb'],1,0);
        $pdf->Cell(50,6,$hasil['uom_pemakaian_bb'],1,0);
        $pdf->Cell(30,6,$hasil['id_induk'],1,0);
        $pdf->Cell(30,6,$hasil['keterangan'],1,1);
        $nomor++;
    
    };
}
else if ($diterima_ket=="penawaran") {
    
    while($hasil = mysqli_fetch_assoc($result)){
        $pdf->Cell(8,6,$nomor,1,0);
        $pdf->Cell(15,6,$hasil['level'],1,0);
        $pdf->Cell(30,6,$hasil['id_bahan_baku'],1,0);
        $pdf->Cell(50,6,$hasil['nama_bahan_baku'],1,0);
        if ($hasil['uom_pemakaian_bb']=="pcs" or $hasil['uom_pemakaian_bb']=="pieces (pcs)" or $hasil['uom_pemakaian_bb']=="pcs (pieces)" 
        or $hasil['uom_pemakaian_bb']=="pieces(pcs)" or $hasil['uom_pemakaian_bb']=="pcs(pieces)" 
        or $hasil['uom_pemakaian_bb']=="set" or $hasil['uom_pemakaian_bb']=="unit") {
            $pdf->Cell(50,6,$hasil['jml_pemakaian_bb'],1,0);
        }else {
            $pdf->Cell(50,6, $hasil['jml_pemakaian_bb'] + ($hasil['jml_pemakaian_bb']*0.01),1,0);
        }
        $pdf->Cell(50,6,$hasil['uom_pemakaian_bb'],1,0);
        $pdf->Cell(30,6,$hasil['id_induk'],1,0);
        $pdf->Cell(30,6,"",1,1);
        $nomor++;

    };
}

$pdf->Output();
?>
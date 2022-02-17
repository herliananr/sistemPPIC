<?php
$diterima_id = $_GET['id_mrp'];

require('vendor/fpdf/fpdf.php');
$pdf = new FPDF('L', 'mm','Letter');

$pdf->AddPage();

$pdf->SetFont('Times','B',16);
$pdf->Cell(0,7,'Struktur MRP',0,1,'C');
$pdf->SetFont('Times','B',14);
$pdf->Cell(0,7,'ID MRP : '.$diterima_id,0,1,'C');
$pdf->SetFont('Times','I',12);
$pdf->Cell(0,7,'',0,1,'C');
$pdf->Cell(0,7,'Lot Sizing : Lot For Lot (LFL)',0,1,'L');
$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Times','B',10);

$pdf->Cell(8,6,'No',1,0,'C');
$pdf->Cell(15,6,'Level',1,0,'C');
$pdf->Cell(50,6,'ID BOM',1,0,'C');
$pdf->Cell(15,6,'Periode',1,0,'C');
$pdf->Cell(20,6,'Tanggal',1,0,'C');
$pdf->Cell(30,6,'ID Bahan Baku',1,0,'C');
$pdf->Cell(15,6,'GR',1,0,'C');
$pdf->Cell(15,6,'SR',1,0,'C');
$pdf->Cell(15,6,'POH',1,0,'C');
$pdf->Cell(15,6,'NR',1,0,'C');
$pdf->Cell(15,6,'POR',1,0,'C');
$pdf->Cell(15,6,'POREL',1,0,'C');
$pdf->Cell(30,6,'Tanggal Penerimaan',1,1,'C');

$pdf->SetFont('Times','',10);

include("layout/koneksi.php");

$query = "select*from mrp where id_mrp='$diterima_id' order by level, id_bahan_baku, periode";
$nomor=1;
$result = mysqli_query($link,$query);
while($hasil = mysqli_fetch_assoc($result)){
    $pdf->Cell(8,6,$nomor,1,0);
    $pdf->Cell(15,6,$hasil['level'],1,0);
    $pdf->Cell(50,6,$hasil['id_bom'],1,0);
    $pdf->Cell(15,6,$hasil['periode'],1,0);
    $pdf->Cell(20,6,$hasil['tanggal'],1,0);
    $pdf->Cell(30,6,$hasil['id_bahan_baku'],1,0);
    $pdf->Cell(15,6,$hasil['gross_requirement'],1,0);
    $pdf->Cell(15,6,$hasil['schedule_receipt'],1,0);
    $pdf->Cell(15,6,$hasil['project_on_hand'],1,0);
    $pdf->Cell(15,6,$hasil['net_requirement'],1,0);
    $pdf->Cell(15,6,$hasil['planned_order_receipt'],1,0);
    $pdf->Cell(15,6,$hasil['planned_order_release'],1,0);
    $pdf->Cell(30,6,$hasil['tanggal_penerimaan'],1,1);
    $nomor++;

};

$pdf->Output();
?>
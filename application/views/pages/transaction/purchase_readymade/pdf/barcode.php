<?php
$this->mypdf_class->tcpdf();

$barcode_data = $data['barcode_data'];

$page_size = array(26,70);

$pdf = new TCPDF('L','mm',$page_size,true,'UTF-8',false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(1,1,1);
$pdf->SetAutoPageBreak(false,0);
$pdf->SetFont('dejavusans','',7);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('Barcode Pdf');
$pdf->SetSubject('Barcode Pdf');

foreach($barcode_data as $row)
{
    $pdf->AddPage();

    $qrcode      = trim($row['qrcode']);
    $sku_name    = strtoupper($row['sku_name']);
    $roll_no     = ($row['roll_no']==0)?'':$row['roll_no'];
    $mrp         = round($row['mrp']);
    $offer_price = round($row['offer_price']);

    /* ===============================
       SKU CP CONVERSION (OLD LOGIC)
    =============================== */
    $sku_cp_data = explode('.', $row['sku_cp'])[0];

    $firstPart = substr($sku_cp_data,0,2);
    $lastPart  = substr($sku_cp_data,2,2);

    $map = array(
        '1'=>'A','2'=>'B','3'=>'C',
        '4'=>'D','5'=>'E','6'=>'F',
        '7'=>'G','8'=>'H','9'=>'I',
        '0'=>'Z'
    );

    $sku_cp = '';

    for($i=0;$i<2;$i++)
    {
        $digit = $firstPart[$i];
        $sku_cp .= isset($map[$digit]) ? $map[$digit] : '';
    }

    if($lastPart=='00'){
        $sku_cp .= 'ZZ';
    }elseif($lastPart=='50'){
        $sku_cp .= 'XX';
    }else{
        $sku_cp .= $lastPart;
    }

    /* PREMIUM BORDER */
    $pdf->RoundedRect(0.5,0.5,69,25,1.2,'1111');

    /* =====================================
       LEFT PANEL
    ===================================== */

    $pdf->SetFont('dejavusans','B',7);
    $pdf->SetXY(1,1);
    $pdf->Cell(55,3,$sku_name,0,1,'L');

   /* =========================
    FINAL SCAN SAFE BARCODE
    ========================= */

    $pdf->SetFillColor(255,255,255);
    $pdf->Rect(1, 5, 38, 14, 'F'); // clean white background

    $style = array(
        'position'     => '',
        'align'        => 'C',
        'stretch'      => false,
        'fitwidth'     => false,
        'border'       => false,
        'padding'      => 2,
        'fgcolor'      => array(0,0,0),
        'bgcolor'      => false,
        'text'         => false,
        'font'         => 'helvetica',
        'fontsize'     => 6
    );

    /* USE CODE 128 */
    $pdf->write1DBarcode(
        $qrcode,
        'C128',
        2,
        6,
        36,
        13,
        0.55,   // IMPORTANT: thicker bars = scanner friendly
        $style,
        'N'
    );

    /* HUMAN READABLE TEXT */
    $pdf->SetFont('helvetica','B',10);
    $pdf->SetXY(2, 18);
    $pdf->Cell(36, 3, $qrcode, 0, 0, 'C');


    /* ROLL CONDITION */
    // if(!empty($roll_no))
    // {
    //     $pdf->SetFont('dejavusans','B',5);
    //     $pdf->SetXY(1,21);
    //     $pdf->Cell(39,2,$roll_no,0,1,'C');
    // }

    /* =====================================
       RIGHT PANEL
    ===================================== */

    $pdf->Line(41,4.5,41,24);

    $pdf->SetFont('dejavusans','B',9);

    /* SHOW ONLY IF MRP > 0 */
    if($mrp > 0)
    {
        $pdf->SetXY(43,4);
        $pdf->Cell(24,4,'MRP',0,0,'L');

        $pdf->SetXY(55,4);
        $pdf->Cell(12,4,'₹ '.$mrp,0,1,'R');
    }

    /* SHOW ONLY IF OP > 1 */
    if($offer_price > 1)
    {
        $pdf->SetXY(43,10);
        $pdf->Cell(24,4,'OP',0,0,'L');

        $pdf->SetXY(55,10);
        $pdf->Cell(12,4,'₹ '.$offer_price,0,1,'R');
    }

    /* DESIGN ONLY IF EXISTS */
    if(!empty($sku_cp))
    {
        $pdf->Line(43,15,67,15);

        $pdf->SetFont('dejavusans','',8);
        $pdf->SetXY(43,16);
        $pdf->Cell(24,3,'DESIGN',0,1,'L');

        $pdf->SetFont('dejavusans','B',10);
        $pdf->SetXY(43,19);
        $pdf->Cell(24,4,$sku_cp,0,1,'L');
    }
}

$pdf->Output('Premium_Barcode_Label.pdf','I');
?>
<?php
$this->mypdf_class->tcpdf();

class MYPDF extends TCPDF {
    public function Header() {}
    public function Footer() {}
}

$pdf = new MYPDF('P', 'mm', 'A5', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('MEASUREMENT SLIP');
$pdf->SetSubject('MEASUREMENT SLIP');

/* RIGHT CUTTING FIX */
$pdf->SetMargins(5,5,5);
$pdf->SetAutoPageBreak(false);

foreach ($trans_data as $value):

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

/* =========================================================
   OUTER DOUBLE BORDER
   moved left + reduced width
========================================================= */
$pdf->SetLineWidth(1.2);
$pdf->Rect(4, 5, 136, 200);

$pdf->SetLineWidth(0.6);
$pdf->Rect(7, 8, 130, 194);

/* =========================================================
   TOP BLACK STRIP
========================================================= */
$pdf->SetFillColor(35,35,35);
$pdf->Rect(7, 8, 130, 18, 'F');

$pdf->SetFillColor(255,255,255);
$pdf->RoundedRect(12, 12, 34, 10, 3, '1111', 'F');
$pdf->RoundedRect(93, 12, 34, 10, 3, '1111', 'F');

$pdf->SetFont('helvetica','',10);
$pdf->SetTextColor(0,0,0);

$entry_no   = !empty($value['entry_no']) ? $value['entry_no'] : '';
$entry_date = !empty($value['entry_date']) ? $value['entry_date'] : '';
$trial_date = !empty($value['trial_date']) ? $value['trial_date'] : '';

$pdf->Text(14, 15, "No : ".$entry_no);
$pdf->Text(95, 15, "Date : ".$trial_date);

/* =========================================================
   MEASUREMENT MAP
========================================================= */
$measurement_map = [];
$fabric_name = '';

if(!empty($value['measurement_data'])){
    foreach($value['measurement_data'] as $m){

        $name = isset($m['measurement_name']) ? $m['measurement_name'] : '';

        $measurement_map[$name] = [
            'value1' => isset($m['value1']) ? $m['value1'] : '',
            'value2' => isset($m['value2']) ? $m['value2'] : ''
        ];
        
        if(
            isset($m['measurement_name']) &&
            strtoupper($m['measurement_name']) == 'FABRIC'
        ){
            $fabric_name = isset($m['value1']) ? $m['value1'] : '';
            break;
        }
    }
}

/* PRINT FABRIC NAME */
/* FABRIC NAME AUTO WRAP */
$pdf->SetFont('helvetica','B',9);

$pdf->SetXY(100, 27); // X , Y position

$pdf->MultiCell(
    40,                 // width
    5,                  // line height
    strtoupper($fabric_name),
    0,                  // border
    'L',                // align
    false
);

/* =========================================================
   LEFT LABELS
========================================================= */
$pdf->SetFont('helvetica','',11);

$labels = [
    "LENGTH","CHEST","WAIST","HIPS",
    "SHOULDER","SLIP","CUFF","ARMOL","ARMS"
];

$y = 38;

foreach($labels as $label){

    $pdf->Text(10, $y, $label);

    if(isset($measurement_map[$label])){

        $val1 = $measurement_map[$label]['value1'];
        $val2 = $measurement_map[$label]['value2'];

        $pdf->Text(32, $y, ": ".$val1." ".$val2);
    }

    $y += 13;
}

/* =========================================================
   SHIRT DRAWING shifted left
========================================================= */
$shirt_path = FCPATH . 'public/assets/dist/images/shirt_image.png';

// X, Y, Width, Height
$pdf->Image($shirt_path, 50, 36, 60, 40);

/* SLIP */
$pdf->Rect(64,105,38,7);

/* CUFF */
$pdf->Rect(67,112,32,16);

/* DUPATTA */
$pdf->Rect(60,132,48,28);

// Get value (example: dupatta)
$dupatta_text = '';
if(isset($measurement_map['DUPATTA'])){
    $dupatta_text = $measurement_map['DUPATTA']['value1'] . ' ' . $measurement_map['DUPATTA']['value2'];
}

$pdf->SetFont('helvetica','B',11);

// Center text inside box
// Box dimensions
$x = 60;
$y = 132;
$w = 48;
$h = 28;

// Text
$text = $dupatta_text;

// Font
$pdf->SetFont('helvetica','B',11);

// Get text height
$text_height = $pdf->getStringHeight($w, $text);

// Calculate vertical center
$start_y = $y + (($h - $text_height) / 2);

// Set position
$pdf->SetXY($x, $start_y);

// Print centered
$pdf->MultiCell($w, 0, $text, 0, 'C', false);

/* =========================================================
   NOTES SECTION
========================================================= */

/* NOTES */
$notes_x = 50;
$notes_y = 175;
$notes_w = 85;
$notes_h = 25;

$pdf->Rect($notes_x, $notes_y, $notes_w, $notes_h);

$pdf->SetFont('helvetica','B',11);
$pdf->Text($notes_x + 2, $notes_y - 5, 'NOTES');

/* GET ONLY ONE COMMON REMARK */
$notes_text = '';

if(!empty($value['measurement_data'])){
    foreach($value['measurement_data'] as $m){
        if(!empty($m['remark'])){
            $notes_text = $m['remark'];
            break;
        }
    }
}

$pdf->SetFont('helvetica','',10);
$pdf->SetXY($notes_x + 2, $notes_y + 2);

$pdf->MultiCell($notes_w - 4, 0, strtoupper($notes_text), 0, 'L', false);

/* =========================================================
   QR CODE
========================================================= */
if(!empty($value['qrcode'])){

    $qr_style = array(
        'border' => 0,
        'vpadding' => 0,
        'hpadding' => 0,
        'fgcolor' => array(0,0,0),
        'bgcolor' => false,
        'module_width' => 1,
        'module_height' => 1
    );

    $pdf->write2DBarcode(
        $value['qrcode'],
        'QRCODE,H',
        12,
        165,
        28,
        28,
        $qr_style,
        'N'
    );

    $pdf->SetFont('helvetica','B',11);
    $pdf->Text(12,195,$value['qrcode']);
}

endforeach;

$pdf->Output('ESTIMATE-MEASUREMENT-SLIP.pdf', 'I');
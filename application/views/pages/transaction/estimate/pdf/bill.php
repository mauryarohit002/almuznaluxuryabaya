<?php
$this->mypdf_class->tcpdf();

global $master_pdf;
global $company_pdf;
global $branch_pdf;

$master_pdf  = $master_data;
$company_pdf = $company_data;
$branch_pdf  = $branch_data;

class MYPDF extends TCPDF {

    public function Header() {

        global $master_pdf;
        global $branch_pdf;

        $branch_mobile = ($branch_pdf[0]['mobile'] > 0) ? $branch_pdf[0]['mobile'] : '-';

        // Black Header Background
        $this->SetFillColor(0,0,0);
        $this->Rect(0,0,148,84,'F');

        // ---------------- FIX 1 : Mobile Number Cutting ----------------
        // moved little left + reduced width
        $this->SetTextColor(212,175,55);
        $this->SetFont('helvetica','B',10);
        $this->SetXY(4,5);
        $this->Cell(130,4,'Mob.: '.$branch_mobile,0,1,'R');

        // Logo
        $logo_path = FCPATH.'public/assets/dist/images/logo.jpg';
        $logoWidth  = 140;
        $logoHeight = 88;

        $this->Image($logo_path, 37, 15, $logoWidth, $logoHeight);

        // Address
        $this->SetY(70);
        $this->SetFont('helvetica','',7);

        $address = wordwrap($branch_pdf[0]['address'], 80, "\n", true);
        $this->MultiCell(0,3,$address,0,'C',false,1);

        // Instagram
        $insta_icon = FCPATH.'public/assets/dist/images/insta_icon.png';
        $this->Image($insta_icon,66,75.7,3);

        $this->SetXY(70,73);
        $this->SetFont('helvetica','B',8);
        $this->Cell(20,8,'al_muzna',0,1,'L');

        // ---------------- FIX 2 : Right Table Cutting ----------------
        $this->SetY(85);
        $this->SetTextColor(0,0,0);

        if(empty($master_pdf[0]['customer_address'])){
            $customer_address = '<br>';
        }else{
            $customer_address = $master_pdf[0]['customer_address'];
        }
        
        $tbl_info = '
        <table cellpadding="3" width="100%" style="font-size:10px;">
            <tr>
                <td width="60%"><b>Bill No :</b> '.$master_pdf[0]['entry_no'].'</td>
                <td width="50%" align="center"><b>Date :</b> '.$master_pdf[0]['entry_date'].'</td>
            </tr>
            <tr>
                <td width="69%"><b>Customer :</b> '.$master_pdf[0]['customer_name'].' - '.$master_pdf[0]['customer_mobile'].'
                <br><small>'.$customer_address.'</small>
                </td>
                <td width="30%" align="left"><b>Delivery :</b> '.$master_pdf[0]['delivery_date'].'</td>
            </tr>
        </table>

        <table border="1" cellpadding="4" width="99%">
            <tr style="background-color:#f5f5f5;font-weight:bold;font-size:10px;">
                <th width="72%" align="center">PARTICULARS</th>
                <th width="28%" align="center">AMOUNT (Rs)</th>
            </tr>
        </table>';

        $this->writeHTML($tbl_info,true,false,false,false,'');
    }

    public function Footer() {

        global $master_pdf;

        $this->SetY(-50);

        $footer = '
        <table border="1" width="99%" cellpadding="3">
            <tr style="font-size:9.5px;">
                <td width="60%" style="font-size:7pt;">
                   <b>We are not responsible for the burkha if it remains at the shop for 
                     <br>&nbsp; more than one month after the delivery date.</b><br/>
                    <b><br>&nbsp; No burkha deliveries will be made on Sundays.</b><br/>
                    <b><br>&nbsp; After booking confirmation, design changes and cancellations will <br>&nbsp; not be accepted.</b>
                </td>
                <td width="40%">
                    <table cellpadding="2" width="100%">
                        <tr>
                            <td>Total Rs.</td>
                            <td align="right">'.$master_pdf[0]['sub_amt'].'</td>
                        </tr>';

                        if($master_pdf[0]['disc_amt'] > 0){
                            $footer .= '
                            <tr>
                                <td>Discount</td>
                                <td align="right">'.$master_pdf[0]['disc_amt'].'</td>
                            </tr>';
                        }

                        if($master_pdf[0]['round_off'] > 0){
                            $footer .= '
                            <tr>
                                <td>Round Off</td>
                                <td align="right">'.$master_pdf[0]['round_off'].'</td>
                            </tr>';
                        }

                        if($master_pdf[0]['advance_amt'] > 0){
                            $footer .= '
                            <tr>
                                <td>Advance</td>
                                <td align="right">'.$master_pdf[0]['advance_amt'].'</td>
                            </tr>';
                        }

                        $footer .= '
                        <tr style="background-color:#f0f0f0;">
                            <td><b>Balance</b></td>
                            <td align="right"><b>'.$master_pdf[0]['balance_amt'].'</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <br><br>

        <table width="100%">
            <tr>
                <td width="55%"></td>
                <td width="45%" align="center">
                    _______________<br>
                    <b>For Almuzna</b>
                </td>
            </tr>
        </table>';

        $this->writeHTML($footer,true,false,false,false,'');
    }
}


// PDF

$pdf = new MYPDF('P','mm','A5',true,'UTF-8',false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('Estimate Invoice');
$pdf->SetSubject('Estimate Invoice');

$pdf->SetMargins(5,112.7,5);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE,50);

$pdf->AddPage();


// BODY

$body = '
<table border="1" cellpadding="0" cellspacing="0" width="99%">
<tr>
<td>
<table cellpadding="5" width="100%">';

if(!empty($trans_data)){

    foreach($trans_data as $value){
        $goods_name = ($value['trans_type']=='READYMADE')
            ? 'READYMADE - ABAYA - '.$value['barcode']
            : $value['apparel_name'].' - '.$value['sku_name'];

        $body .= '
        <tr style="font-size:9px;">
            <td width="72%" align="center" style="border-bottom:1px solid black;">'.$goods_name.'</td>
            <td width="28%" align="center" style="border-left:1px solid black;border-bottom:1px solid black;">'.number_format($value['amt'],2).'</td>
        </tr>';
    }

}else{

    $body .= '
    <tr>
        <td width="72%" height="80"></td>
        <td width="28%"></td>
    </tr>';
}

$body .= '
</table>
</td>
</tr>
</table>';

$pdf->writeHTML($body,true,false,false,false,'');


// Borders

$startY = 112;
$endY   = 160;

$pdf->Line(5,$startY,5,$endY);
$pdf->Line(141.7,$startY,141.7,$endY);

$pdf->Output('Al_Muzna_Invoice.pdf','I');
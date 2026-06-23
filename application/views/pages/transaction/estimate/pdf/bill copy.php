<?php
// Include the main TCPDF library (search for installation path).
$this->mypdf_class->tcpdf(); 
global $master_pdf; 
global $company_pdf;
global $yy;
$master_pdf      = $master_data; 
$company_pdf     = $company_data;
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF 
{
    //Page header
    public function Header()  
    { 
        global $master_pdf ;  
        global $company_pdf ;

        $logo =base_url('public/assets/dist/images/logo.jpg');
        $customer_name = !empty($master_pdf[0]['customer_name'])?$master_pdf[0]['customer_name']:'';
        $customer_mobile = !empty($master_pdf[0]['customer_mobile'])?$master_pdf[0]['customer_mobile']:'';
        $customer_address = !empty($master_pdf[0]['customer_address'])?$master_pdf[0]['customer_address']:'';

        $salesman_name = !empty($master_pdf[0]['salesman_name'])?$master_pdf[0]['salesman_name']:'';

        $this->SetFont('copperplateccheavy', '', 9,false);

        $tbl_header = '<table cellpadding="3" style="border-top: 1px solid #000; border-left: 1px solid #000;border-right: 1px solid #000;"> 
                        <tr>
                            <td width="25%"></td>
                            <td width="50%" style="text-align:center; font-size: 18px;"><b>ESTIMATE</b></td>
                             <td width="25%"></td>
                        </tr>
                    </table>
                    <table cellpadding="4" cellspacing="0" style="width: 100%; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
                        <tr>
                            <!-- Left: Company Info -->
                            <td style="width: 50%; text-align: center; vertical-align: top; border-right: 1px solid #000;">
                                <span style="font-size: 26px; font-weight: bold;">'.$company_pdf[0]['company_name'].'</span><br/>
                                <span style="font-size: 12px;">'.nl2br($company_pdf[0]['address']).'</span>
                            </td>
                            <!-- Right: Party Info -->
                            <td style="width: 50%; text-align: left; vertical-align: top; padding-left: 8px;"><strong>PARTY NAME :</strong> '.$customer_name.'<br/>
                                <strong>MOB. :</strong> '.$customer_mobile.'<br/>
                                <strong>ADDRESS :</strong> '.nl2br($customer_address).'
                            </td>
                        </tr>
                    </table>
                    <table border="1" cellpadding="4" >
                        <tr>
                            <td><b>ORDER No: '.$master_pdf[0]['entry_no'].'</b></td>
                            <td><b>Order Date : '.$master_pdf[0]['entry_date'].'</b></td>
                        </tr>
                        <tr>
                            <td><b>salesman : '.$salesman_name.'</b></td>
                            <td><b>Entry By : '.$master_pdf[0]['user_name'].'</b></td>

                        </tr>
                    </table>
                   
                    <table  border="1" cellpadding="3">
                        <tr>
                            <th style="width:5%;"><b>Sr.</b></th>
                            <th style="width:50%;"><b>Description Of Goods</b></th>
                            <th style="width:15%;text-align:center;"><b>Qty</b></th>
                            <th style="width:15%;text-align:center;"><b>Rate</b></th>
                            <th style="width:15%;text-align:center;"><b>Amount</b></th>
                        </tr>
                    </table>';

        $this->writeHTML($tbl_header, true, false, false, false, '');
        $yy = $this->GetY(); 
        $yy = $yy - 6;
        
        $this->line(5,$yy,5,217);
        $this->line(15,$yy,15,217);
        $this->line(115,$yy,115,223);       
        $this->line(145,$yy,145,225);
        $this->line(175,$yy,175,223);
        $this->line(205,$yy,205,223);

        $this->SetTopMargin($yy + 1);
    }

    // Page footer
    public function Footer() 
    {
        $this->SetFont('copperplateccheavy', '', 9,false);
        global $master_pdf;
        global $company_pdf;

        $amt_words  = number_to_word($master_pdf[0]['balance_amt']);
       
        $tbl_footer ='';
        $tbl_footer .='<table width="100%"  border="0" style="border:1px solid #000;" cellpadding="4" >
            <tr>
                <th style="width:55%;text-align:right;">TOTAL</th>
                <th style="width:15%;text-align:center;"></th>
                <th style="width:15%;"></th>
                <th style="width:15%;text-align:center"><b>'.$master_pdf[0]['sub_amt'].'</b></th>
            </tr>
        </table>
       <table border="1" cellpadding="2">
        <tr>
            <td width="70%">
                <table>
                    <tr>
                        <td height="35px;">TERMS & CONDITION : <br/>
                            TRIAL DATE : '.$master_pdf[0]['trial_date'].'<br/>
                            FINISH DATE : '.$master_pdf[0]['delivery_date'].'
                        </td>
                    </tr>
                </table>
            </td>
            <td width="30%">
                <table style="line-height: 1.8;">';
                    if($master_pdf[0]['round_off'] >0){
                        $tbl_footer .='<tr>
                            <td width="42%" align="left">ROUND OFF</td>
                            <td width="58%" align="right">&nbsp;&nbsp;'.$master_pdf[0]['round_off'].'</td>
                        </tr>';
                    }
                        
                    $tbl_footer .='<tr>
                        <td width="40%" align="left">TOTAL AMT </td>
                        <td width="60%" align="right">&nbsp;&nbsp;'.$master_pdf[0]['total_amt'].'</td>
                    </tr>
                    <tr>
                        <td width="50%" align="left">ADVANCE&nbsp;AMT</td>
                        <td width="50%" align="right">'.$master_pdf[0]['advance_amt'].'</td>
                    </tr>';
                 if($master_pdf[0]['receipt_amt'] >0){
                        $tbl_footer .='<tr>
                            <td width="40%"  align="left">RECEIPT AMT </td>
                            <td width="60%" align="right">&nbsp;&nbsp;'.$master_pdf[0]['receipt_amt'].'</td>
                        </tr>';
                    }    
                $tbl_footer .='<tr>
                        <td width="50%" align="left">BALANCE&nbsp;AMT</td>
                        <td width="50%" align="right">'.$master_pdf[0]['balance_amt'].'</td>
                    </tr>
                    ';
                
            
            $tbl_footer .='</table>
            </td>       
         </tr>
        
        </table>
        <table cellpadding="1" border="1" >
            <tr>
                <td width="70%" >
                    <table>
                        <tr>
                            <td>Amt. in Words : '.$amt_words.'</td>
                        </tr>
                    </table> 
                </td>   
                <td width="30%">
                    <table>
                        <tr>
                            <td width="100%" style="text-align:center;"><br/>
                                For '.$company_pdf[0]['company_name'].'<br/>
                                <br/><br/><br/>
                                Signature
                            </td>
                        </tr>
                    </table> 
                </td>
            </tr>
        </table>';

        $this->writeHTML($tbl_footer, true, false, false, false, '');
        // Set font
        $this->SetFont('copperplateccheavy', 'I', 9);
        // Page number
        $this->Cell(0, 0, 'Page '.$this->getPageNumGroupAlias().'/'.$this->getPageGroupAlias(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array('210','297'), true, 'UTF-8', false); 
$file_name = 'ESTIMATE.pdf';
// $file_name = 'sales_invoice_pdf.pdf';
$file_path = 'ESTIMATE INVOICE.pdf';
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('ESTIMATE INVOICE Pdf');
$pdf->SetSubject('ESTIMATE INVOICE Pdf');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, 5,true);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(80);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 78);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}


$pdf->SetFont('copperplateccheavy', '', 13,false);
$body = "";
$title = "Original for Buyer";
$pdf->startPageGroup();
$pdf->AddPage();

$body .= '<table cellpadding="2">';
            foreach($trans_data as $key => $value) :

                // $goods_name = ($value['trans_type']=='READYMADE') ? $value['barcode'] : $value['apparel_name'];
                $goods_name = ($value['trans_type']=='READYMADE') ? 'READYMADE' : $value['apparel_name'];
                $qty = $value['qty'];
                $body .= '<tr style="font-size:12px;">
                            <td style="width:5%;border-bottom-color:#ccc;text-align:center;">'.($key+1).'</td>
                            <td style="width:50%;border-bottom-color:#ccc;">'.$goods_name.'</td>
                            <td style="width:15%;text-align:center;border-bottom-color:#ccc;">'.$value['qty'].'</td>
                            <td style="width:15%;text-align:center;border-bottom-color:#ccc;">'.round($value['amt']).'</td>
                            <td style="width:15%;text-align:center;border-bottom-color:#ccc;">'.round($value['amt']).'</td>
                        </tr>';
            endforeach;
$body .= '</table>'; 


$pdf->writeHTML($body, true, false, false, false, '');

//Close and output PDF document
if(true)
{

    $pdf->Output($file_path, 'I');
}


//============================================================+
// END OF FILE
//============================================================+
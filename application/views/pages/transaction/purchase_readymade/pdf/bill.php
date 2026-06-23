<?php
// Include the main TCPDF library (search for installation path).
$this->mypdf_class->tcpdf(); 
global $master_pdf; 
global $company_pdf; 
global $yy;
$master_pdf      = $master_data; 
$company_pdf    = $company_data;
// echo"<pre>";print_r($master_pdf);exit;
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF 
{
   
    public function Header()  
    { 
        global $master_pdf ;
        global $company_pdf ;
        //$logo =base_url('public/assets/dist/images/logo.jpg');
        $supplier_name = !empty($master_pdf[0]['supplier_name'])?$master_pdf[0]['supplier_name']:'';
        $supplier_mobile = !empty($master_pdf[0]['supplier_mobile'])?$master_pdf[0]['supplier_mobile']:'';
        $supplier_address = !empty($master_pdf[0]['supplier_address'])?$master_pdf[0]['supplier_address']:'';

        $this->SetFont('copperplateccheavy','', 8);
        $tbl_header = '<table cellpadding="3" style="border-top: 1px solid #000; border-left: 1px solid #000;border-right: 1px solid #000;">  
                        <tr>
                            <td width="100%" style="text-align:center;"><b>INVOICE</b></td>
                        </tr>
                    </table>
                     <table cellpadding="4" cellspacing="0" style="width: 100%; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
                        <tr>
                            <!-- Left: Company Info -->
                            <td style="width: 50%; text-align: center; vertical-align: top; border-right: 1px solid #000;">
                                <span style="font-size: 26px; font-weight: bold;">'.$company_pdf[0]['company_name'].'</span><br/>
                                <span style="font-size: 13px; font-weight: bold;">A UNIT OF ROYAL CLOTHING</span><br/>
                                <span style="font-size: 12px;">'.nl2br($company_pdf[0]['company_address']).'</span>
                            </td>

                            <!-- Right: Party Info -->
                            <td style="width: 50%; text-align: left; vertical-align: top; padding-left: 8px;"><strong>PARTY NAME :</strong> '.$supplier_name.'<br/>
                                <strong>MOB. :</strong> '.$supplier_mobile.'<br/>
                                <strong>ADDRESS :</strong> '.nl2br($supplier_address).'
                            </td>
                        </tr>
                    </table>
                   <table border="1" cellpadding="4" >
                        <tr>
                            <td><b>Entry No: '.$master_pdf[0]['entry_no'].'</b></td>
                            <td><b>entry Date : '.$master_pdf[0]['entry_date'].'</b></td>
                            <td><b>Entry By  : '.$master_pdf[0]['user_name'].'</b></td>
                        </tr>
                         <tr>
                            <td><b>Bill No: '.$master_pdf[0]['bill_no'].'</b></td>
                            <td><b>Bill Date : '.$master_pdf[0]['bill_date'].'</b></td>
                            <td></td>
                        </tr>
                    </table>
                    <table  border="1" cellpadding="3">
                        <tr>
                            <th style="width:8%;"><b>Sr.</b></th>
                            <th style="width:44%;text-align:center;"><b>SKU</b></th>
                            <th style="width:16%;text-align:center;"><b>QTY</b></th>
                            <th style="width:16%;text-align:center;"><b>Rate</b></th>
                            <th style="width:16%;text-align:right;"><b>Amount</b></th>
                        </tr>
                    </table>';

        $this->writeHTML($tbl_header, true, false, false, false, '');
        $yy = $this->GetY(); 
        $yy = $yy - 6;
        $this->line(5,$yy,5,207);
        $this->line(21,$yy,21,207);
        $this->line(109,$yy,109,207);
        $this->line(141,$yy,141,207);
        $this->line(173,$yy,173,207);
        $this->line(205,$yy,205,207);

        $this->SetTopMargin($yy + 1);
    }

    // Page footer
    public function Footer() 
    {
        $this->SetFont('copperplateccheavy', 'B', 10,false);
        global $master_pdf;
        global $company_pdf ;
      
      
        $tbl_footer ='';
        $tbl_footer .='<table width="100%"  border="0" style="border:1px solid #000;" cellpadding="4" >
            <tr style="font-size:12px;">
                <th style="width:63%;text-align:right;"><b>Total</b></th>
                <th style="width:8%;text-align:center"><b>'.$master_pdf[0]['total_qty'].'</b></th>
                <th style="width:8%;text-align:center"></th>
                <th style="width:10%;text-align:center"><b></b></th>
                <th style="width:11%;text-align:center"><b>'.$master_pdf[0]['prmm_sub_amt'].'</b></th>

            </tr>
        </table>
       <table border="1" cellpadding="2">
        <tr>
            <td width="70%" style="font-size:10px;">
                <table>
                    <tr>
                        <td height="35px;">TERMS & CONDITION : </td>
                    </tr>
                    
                </table>
            </td>
            <td width="30%">
                <table style="line-height: 1.8;">';
            
            $tbl_footer .='
                    <tr>
                        <td width="40%" style="font-size:10px;" align="left">GROSS AMT </td>
                        <td width="60%"style="font-size:10px;" align="right">'.$master_pdf[0]['prmm_sub_amt'].'</td>
                    </tr>';
                
            $tbl_footer .='
                <tr>
                    <td width="40%" style="font-size:10px;" align="left">EXTRA AMT </td>
                    <td width="60%"style="font-size:10px;" align="right">'.$master_pdf[0]['prmm_extra_amt'].'</td>
                </tr>';
                
            
            $tbl_footer .='</table>
            </td>       
         </tr>
         <tr>
            <td width="70%">
                <table>
                    <tr>
                        <td style="font-size:10px;" align="left"> </td>
                    </tr>
                </table>
            </td>
            <td width="30%">
                <table>
                    <tr>
                        <td width="50%" style="font-size:10px;" align="left">TOTAL NET AMT. </td>
                        <td width="50%" style="font-size:10px;" align="right">'.$master_pdf[0]['total_amt'].'</td>
                    </tr>
                </table>
            </td>
         </tr>
            
        </table>
        ';

        $this->writeHTML($tbl_footer, true, false, false, false, '');
        // Set font
        $this->SetFont('copperplateccheavy', 'I', 9);
        // Page number
        $this->Cell(0, 0, 'Page '.$this->getPageNumGroupAlias().'/'.$this->getPageGroupAlias(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array('210','297'), true, 'UTF-8', false); 
$file_name = 'Order.pdf';

// $file_name = 'sales_invoice_pdf.pdf';
$file_path = 'FABRIC INVOICE.pdf';
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('FABRIC INVOICE Pdf');
$pdf->SetSubject('FABRIC INVOICE Pdf');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, 5,true);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(90);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 68);

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

$body .= '<table cellpadding="2" >';
            foreach($trans_data as $key => $value) :

              
                $body .= '<tr style="font-size:11px;">
                            <td style="width:8%;border-bottom-color:#ccc;text-align:center;">'.($key+1).'</td>
                            <td style="width:44%;text-align:center;border-bottom-color:#ccc;">'.$value['sku_name'].'</td>

                            <td style="width:16%;text-align:center;border-bottom-color:#ccc;">'.$value['qty'].'</td>
                            <td style="width:16%;text-align:center;border-bottom-color:#ccc;">'.round($value['rate']).'</td>
                            <td style="width:16%;text-align:center;border-bottom-color:#ccc;">'.round($value['amt']).'</td>
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
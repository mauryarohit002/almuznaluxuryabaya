<?php
$this->mypdf_class->tcpdf();
global $yy;

class MYPDF extends TCPDF {

    public function Header(){

        global $yy;

        $header = '
        <table border="1" cellpadding="3">
            <tr>
                <td>
                    <table border="0" cellpadding="4">
                        <tr>
                            <td width="20%">
                                '.strtoupper($_SESSION['user_branch']).'
                            </td>

                            <td width="60%" align="center" style="font-size:12px;">
                                <b>KARIGAR LEDGER</b>
                            </td>

                            <td width="20%" align="right">
                                '.date('d-m-Y H:i:s a').'
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table border="1" cellpadding="4" style="font-size:8px;font-weight:bold;">
            <tr>
                <th width="5%" align="center">#</th>
                <th width="7%" align="center">DATE</th>
                <th width="7%" align="center">ENTRY NO</th>
                <th width="7%" align="center">TYPE</th>
                <th width="14%" align="center">KARIGAR</th>
                <th width="12%" align="center">HISAB AMOUNT</th>
                <th width="12%" align="center">PAYMENT AMOUNT</th>
                <th width="12%" align="center">CLOSING BALANCE</th>
                <th width="8%" align="center">STATUS</th>
                <th width="16%" align="center">REMARK</th>
            </tr>
        </table>';

        $this->writeHTMLCell(
            287,
            195,
            5,
            5,
            $header,
            0,
            0,
            0,
            true,
            'L',
            true
        );

        $yy = $this->GetY();
        $yy += 14.1;
        $this->SetTopMargin($yy);
    }

    public function Footer(){

        $this->SetY(-10);
        $this->SetFont('helvetica', 'I', 8);

        $this->Cell(
            0,
            10,
            'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(),
            0,
            false,
            'C'
        );
    }
}

/* =====================================================
   PDF SETTINGS
===================================================== */

$pdf = new MYPDF(
    'L',
    PDF_UNIT,
    PDF_PAGE_FORMAT,
    true,
    'UTF-8',
    false
);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Imran Khan');
$pdf->SetTitle('KARIGAR LEDGER');
$pdf->SetSubject('KARIGAR LEDGER');

$pdf->SetMargins(5, 0, 5);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(5);

$pdf->SetAutoPageBreak(TRUE, 12);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__).'/lang/eng.php'))
{
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$pdf->SetFont('helvetica', '', 8);

$pdf->AddPage('L');

/* =====================================================
   BODY
===================================================== */

$body = '<table border="1" cellpadding="4">';

$total_hisab   = 0;
$total_payment = 0;
$final_closing = 0;

if(!empty($data))
{
    foreach($data as $key => $value)
    {
        $total_hisab   += (float)$value['hisab_amt'];
        $total_payment += (float)$value['payment_amt'];
        $final_closing  = (float)$value['closing_amt'];

        $body .= '
        <tr>

            <td width="5%" align="center">
                '.($key+1).'
            </td>

            <td width="7%">
                '.date('d-m-Y',strtotime($value['entry_date'])).'
            </td>

            <td width="7%">
                '.$value['entry_no'].'
            </td>

            <td width="7%" align="center">
                '.$value['action'].'
            </td>

            <td width="14%">
                '.$value['karigar_name'].'
            </td>

            <td width="12%" align="right">
                '.number_format($value['hisab_amt'],2).'
            </td>

            <td width="12%" align="right">
                '.number_format($value['payment_amt'],2).'
            </td>

            <td width="12%" align="right">
                '.number_format($value['closing_amt'],2).'
            </td>

            <td width="8%" align="center">
                '.$value['label'].'
            </td>

            <td width="16%">
                '.$value['remark'].'
            </td>

        </tr>';
    }

    $body .= '
    <tr style="font-weight:bold;background-color:#f2f2f2;">

        <td width="5%"></td>

        <td width="7%"></td>

        <td width="7%"></td>

        <td width="7%"></td>

        <td width="14%" align="right">
            TOTAL
        </td>

        <td width="12%" align="right">
            '.number_format($total_hisab,2).'
        </td>

        <td width="12%" align="right">
            '.number_format($total_payment,2).'
        </td>

        <td width="12%" align="right">
            '.number_format($final_closing,2).'
        </td>

        <td width="8%" align="center">
            '.($final_closing >= 0 ? 'TO PAY' : 'TO RECEIVE').'
        </td>

        <td width="16%"></td>

    </tr>';
}
else
{
    $body .= '
    <tr>
        <td colspan="10" align="center">
            NO RECORD FOUND !!!
        </td>
    </tr>';
}

$body .= '</table>';

$pdf->writeHTML($body, true, false, false, false, '');

$pdf->IncludeJS("print();");

ob_end_clean();

$pdf->Output('KARIGAR_LEDGER.pdf', 'I');
?>
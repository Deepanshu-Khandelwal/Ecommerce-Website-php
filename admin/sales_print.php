<?php
include 'includes/session.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['print']) && !empty($_POST['date_range'])) {

    // Extract and sanitize date range
    $dateRange = explode(' - ', trim($_POST['date_range']));
    $from = date('Y-m-d 00:00:00', strtotime($dateRange[0]));
    $to   = date('Y-m-d 23:59:59', strtotime($dateRange[1]));
    $from_title = date('M d, Y', strtotime($from));
    $to_title   = date('M d, Y', strtotime($to));

    $conn = $pdo->open();

    // Generate table rows for paid sales
    function generateRow($from, $to, $conn) {
        $contents = '';
        $total = 0;

        $stmt = $conn->prepare("
            SELECT s.id AS salesid, s.sales_date, s.pay_id, s.amount, COALESCE(CONCAT(u.firstname,' ',u.lastname), s.name, 'Guest') AS buyer_name
            FROM sales s
            LEFT JOIN users u ON u.id = s.user_id
            WHERE s.status = 'paid' AND s.sales_date BETWEEN :from AND :to
            ORDER BY s.sales_date DESC
        ");
        $stmt->execute(['from' => $from, 'to' => $to]);
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($sales as $row) {
            $amount = (float)$row['amount'];
            $total += $amount;

            $txnRef = $row['pay_id'] ?: 'Order #' . $row['salesid'];

            $contents .= '
            <tr>
                <td>'.date('M d, Y', strtotime($row['sales_date'])).'</td>
                <td>'.htmlspecialchars($row['buyer_name']).'</td>
                <td>'.htmlspecialchars($txnRef).'</td>
                <td align="right">&#8377; '.number_format($amount, 2).'</td>
            </tr>';
        }

        $contents .= '
        <tr>
            <td colspan="3" align="right"><b>Total</b></td>
            <td align="right"><b>&#8377; '.number_format($total, 2).'</b></td>
        </tr>';

        return $contents;
    }

    // Generate PDF using TCPDF
    require_once('../tcpdf/tcpdf.php');  

    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle('Sales Report: '.$from_title.' - '.$to_title);  
    $pdf->setPrintHeader(false);  
    $pdf->setPrintFooter(false);  
    $pdf->SetMargins(10, 10, 10);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('helvetica', '', 11);  
    $pdf->AddPage();  

    // PDF Content (using INR currency symbol)
    $content = '
        <h2 align="center">Pavitra Sarees</h2>
        <h4 align="center">SALES REPORT</h4>
        <h4 align="center">'.$from_title.' - '.$to_title.'</h4>
        <table border="1" cellspacing="0" cellpadding="4">
            <tr style="background-color:#f2f2f2;">
                <th width="20%" align="center"><b>Date</b></th>
                <th width="30%" align="center"><b>Buyer Name</b></th>
                <th width="35%" align="center"><b>Transaction/Ref#</b></th>
                <th width="15%" align="center"><b>Amount</b></th>
            </tr>';
    $content .= generateRow($from, $to, $conn);
    $content .= '</table>';

    $pdf->writeHTML($content);
    $pdf->Output('sales_report_'.$from.'_to_'.$to.'.pdf', 'I');

    $pdo->close();
    exit();

} else {
    $_SESSION['error'] = 'Please select a date range to generate the report.';
    header('Location: sales.php');
    exit();
}
?>

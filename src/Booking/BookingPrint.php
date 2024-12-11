<?php
namespace KpakpandoEventsBooking\Booking;

use Mpdf\Mpdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\PngWriter;

class BookingPrint
{
    public static function getTicketPrint($arParams)
    {
        $bookingId = $arParams['bookingId'];
        $eventTitle = htmlspecialchars($arParams['title']);
        $ticketNumber = $arParams['reference'];
        $participantName = $arParams['fullName'];
        $eventDate = $arParams['date'];
        $time = $arParams['time'];
        //$price = $arParams['price'];
        $qty = doTypeCastInt($arParams['qty']);
        $venue = $arParams['venue'];
        $printType = array_key_exists('printType', $arParams) ? $arParams['printType'] : 'default';

        try {
            $mpdf = new Mpdf();

            //CSS for styling
            $stylesheet = <<<EOQ
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
}
.ticket-container {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
}
.header {
    background-color: #3c52e9;
    padding: 15px;
    color: #fff;
    border-radius: 8px 8px 0 0;
    text-align: center;
}
.header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
}
.content {
    padding: 20px;
}
.details {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    margin-top: 10px;
}
.details p {
    margin: 5px 0;
}
.label {
    font-weight: bold;
    color: #3c52e9;
}
.ticket-info {
    margin-top: 20px;
    font-size: 14px;
}
.qr-code {
    text-align: center;
    margin-top: 20px;
}
.qr-code img {
    border: 2px solid #4A90E2;
    border-radius: 10px;
}
EOQ;

            //Generate QR Code
            $qrData = DEF_COMMON_REDIRECT_URL."?action=validateticket&id={$bookingId}";
            $qrCode = Builder::create()
                ->writer(new PngWriter())
                ->data($qrData)
                ->size(150)
                ->margin(10)
                ->backgroundColor(new Color(255, 255, 255))
                ->foregroundColor(new Color(0, 0, 0))
                ->build();

            //Save QR code temporarily
            $qrPath = 'temp_qr.png';
            $qrCode->saveToFile($qrPath);

            //Ticket HTML content
            $html = <<<EOQ
<div class="ticket-container">
    <div class="header">
        <h2>{$eventTitle}</h2>
        <p>Your Pass to an Unforgettable Experience</p>
    </div>
    <div class="content">
        <div class="details">
            <div>
                <p><span class="label">Date:</span> {$eventDate}</p>
                <p><span class="label">Time:</span> {$time}</p>
            </div>
            <div>
                <p><span class="label">Venue:</span> {$venue}</p>
            </div>
        </div>
        <div class="ticket-info">
            <p><span class="label">Ticket Number:</span> {$ticketNumber}</p>
            <p><span class="label">Participant Name:</span> {$participantName}</p>
            <p><span class="label">Qty:</span> {$qty}</p>
        </div>
        <div class="qr-code">
            <img src='{$qrPath}' alt='QR Code'>
            <p>Scan for Verification</p>
        </div>
    </div>
</div>
EOQ;

            //Set the CSS and HTML
            $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

            switch ($printType)
            {
                //Output as a string
                case 'string':
                    return $mpdf->Output('', 'S');

                //Output the PDF to the browser
                default:
                    $mpdf->Output("{$eventTitle}.pdf", 'I');
                break;
            }
        }
        catch (\Mpdf\MpdfException $e)
        {
            echo 'Error creating PDF: ' . $e->getMessage();
        }
    }

    public static function getTicketPrintV1($arParams)
    {
        $eventTitle = $arParams['title'];
        $ticketNumber = $arParams['reference'];
        $attendeeName = $arParams['fullName'];
        $eventDate = $arParams['date'];
        $time = $arParams['time'];
        $price = $arParams['price'];
        $eventLocation = $arParams['venue'];
        $printType = array_key_exists('printType', $arParams) ? $arParams['printType'] : 'default';

        $mpdf = new Mpdf([
            'format' => [210, 100],
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 5,
        ]);


        //Generate QR Code
        $qrData = "Ticket Number: {$ticketNumber}\nName: {$attendeeName}\nEvent: {$eventTitle}";
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($qrData)
            ->size(80)
            ->margin(0)
            ->backgroundColor(new Color(255, 255, 255))
            ->foregroundColor(new Color(0, 0, 0))
            ->build();

        //Save QR code temporarily
        $qrPath = 'temp_qr.png';
        $qrCode->saveToFile($qrPath);

        //Ticket HTML content
        $html = <<<EOQ
<style>
.ticket-container {
    width: 100%;
    font-family: Arial, sans-serif;
    font-size: 10px;
    color: #333;
    border: 1px solid #ddd;
    overflow: hidden;
}
.ticket-header {
    background-color: #222;
    color: #fff;
    padding: 8px;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
}
.ticket-body {
    padding: 10px;
}
.ticket-info {
    margin: 8px 0;
}
.ticket-info strong {
    color: #222;
}
.ticket-footer {
    text-align: center;
    font-size: 8px;
    color: #666;
    margin-top: 8px;
}
.qr-code {
    text-align: center;
    margin-top: 10px;
}
.qr-code img {
    width: 80px;
    height: 80px;
}
.ticket-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}
</style>

<div class='ticket-container'>
    <div class='ticket-header'>
        {$eventTitle}
    </div>
    <div class='ticket-body'>
        <div class='ticket-info'>
            <p><strong>Attendee:</strong> {$attendeeName}</p>
            <p><strong>Date:</strong> {$eventDate}</p>
            <p><strong>Location:</strong> {$eventLocation}</p>
            <p><strong>Ticket #:</strong> {$ticketNumber}</p>
        </div>
        <div class='ticket-details'>
            <div class='qr-code'>
                <img src='{$qrPath}' alt='QR Code'>
            </div>
        </div>
        <div class='ticket-footer'>
            Please present this ticket at the event entry.
        </div>
    </div>
</div>
EOQ;

        //Write HTML to PDF
        $mpdf->WriteHTML($html);

        //Output PDF
        //$pdfOutputPath = 'event_ticket_' . $ticketNumber . '.pdf';
        //$mpdf->Output($pdfOutputPath, 'F');

        //Clean up the QR code file
        unlink($qrPath);
        
        switch ($printType)
        {
            case 'default':
                $mpdf->Output();
            break;

            case 'string':
                return $mpdf->Output('', 'S');

            default:
                $mpdf->Output();
            break;
        }
    }
}
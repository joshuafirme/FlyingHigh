<?php
namespace App\Helpers;
use DateTime;
use Cache;
use Auth;
use Mail;
use App\Mail\Mailer;
use App\Models\User;
use App\Models\LineItem;
use App\Models\HubInventory;
use App\Models\TransactionLineItems;
use File;
class Utils
{
    public static function objectToArray($data) 
    {
        return json_decode(json_encode($data), true);
    }

    public static function curlRequestWithHeaders($url, $header) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $response = curl_exec($ch);
        curl_close($ch);
		return $response;
    }

    public static function curlPost($url, $header, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        return json_decode($result);
    }

    public static function curlPut($url, $header, $data = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_PUT, 1);
        $result = curl_exec($ch);
        return json_decode($result);
    }

    public static function httpRequest($header, $method, $data, $url) 
    {
        $options = array(
            'http' => array(
                'header' => $header,
                'method' => $method,
                'content' => $data
            )
        );
        
        $context  = stream_context_create($options);
        return json_decode(file_get_contents($url, false, $context));
    }

    public static function httpPut($url, $header) 
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_PUT, true);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $fields = array("id" => 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

        //Execute the request.
        $response = curl_exec($ch);

        return $response;
    }

    public static function curlRequest($url) {
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($c);
		curl_close($c);
		return $data;
	}

    
    public function getAccessToken($request) 
    {
        $url = "https://auth-stage.youngliving.com/connect/token";
        $data  = [
            'grant_type' => 'client_credentials',
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
            'scope' => 'lf-manila'
        ];

        $header = "Content-type: application/x-www-form-urlencoded\r\n";
        
        $response = self::httpRequest($header, "POST", http_build_query($data), $url);
       
        if ($response && $response->access_token) {
            return $response;
        }
        return json_encode([
            'success' => false,
            'message' => 'Error occured, can\'t get access token.'
        ]);
    }

    public static function separateString($str, $separator = '-', $separated_num = 3) 
    {
        if (!$str) { return false; }
        $str_length = strlen($str);
        $ctr = 0;
        $out_str = '';
        for ($i = 0; $i < $str_length; $i++) {
            $ctr++;
            $out_str .= $str[$i];
            if ($ctr == $separated_num) {
                $out_str .=  $separator;
                $ctr = 0;
            }
        }
        $out_str_length = strlen($out_str);
        if ($out_str[$out_str_length-1] == '-') {
            $out_str = substr($out_str, 0, $out_str_length - 1);
        }
        return $out_str;
    }

    public static function getTaxPerItem($unit_price) 
    {
        $res =  $unit_price * 0.12;
        return self::toFixed($res);
    }
    
    public static function getWithVAT($itemUnitPrice, $pv)
    {
        $res = $itemUnitPrice + $pv;
        return self::toFixed($res);
    }

    public static function getTotalCost($itemUnitPrice, $quantity) 
    {
        $res =  $itemUnitPrice * $quantity;
        return self::toFixed($res);
    }
    

    public static function toFixed($num, $dec_p = 2)
    {
        return number_format((float)$num, $dec_p, '.', '');
    }

    public static function numFormat($num, $dec_p = 2)
    {
        return number_format($num)
;
    }

    public static function getStatusTextClass($status) {
            $status_text = 'Shipment Status - Pending';
            $status_class = 'primary';
            if ($status == 1) {
                $status_text = 'Shipped';
                $status_class = 'warning';
            } else if ($status == 2) {
                $status_text = 'Delivered';
                $status_class = 'success';
            } else if ($status == 3) {
                $status_text = 'Pickup Status - Partially Completed';
                $status_class = 'warning';
            } else if ($status == 4) {
                $status_text = 'Pickup Status - Completed';
                $status_class = 'success';
            }
            return json_encode([
                'text' => $status_text,
                'class' => $status_class
            ]);
        }

    public static function getPickupStatusBySlug($slug) 
    {
        switch ($slug) {
            case 'for-pickup':
                return 0;
                break;
            case 'picked-up':
                return 1;
                break;
            case 'overdue':
                return 2;
                break;
            case 'returned':
                return 3;
                break;
        }
    }

    public static function getPickupStatusText($status) 
    {
        switch ($status) {
            case 0:
                return 'For Pickup';
                break;
            case 1:
                return 'Picked Up';
                break;
            case 2:
                return 'Overdue';
                break;
            case 3:
                return 'Returned';
                break;
        }
    }

    public static function validateExpiration($expiration) 
    {
        return $expiration && strpos($expiration, '1970-01-01') === false ? $expiration : 'N/A';
    }

    public static function renderReport($items, $title, $headers, $columns, $date_from = "", $date_to ="")
    {  
        if (strpos($title, 'For Pickup') !== false || strpos($title, 'Picked Up') !== false
            || strpos($title, 'Returned') !== false || strpos($title, 'Overdue') !== false) { 
            return self::renderCustomReport($items, $title, $headers, $columns, $date_from, $date_to);
        }
        else {      
            if(($date_from == $date_to)
            || ($date_from == "" && $date_to == "")) {
                $date = date("F j, Y", strtotime(date('Y-m-d')));
            }else {
                $date = date("F j, Y", strtotime($date_from)) .' - '. date("F j, Y", strtotime($date_to));
            }

        $output = '';
        $output .= self::invoiceStyle();

        $output .= '<img class="logo" src="' . public_path() . "/assets/yl_logo_hd.png" . '" width="200" alt="" />
            <div class="head-2">' . $title . '</div>
            <div class="head-1">YOUNG LIVING PHILIPPINES LLC</div>
            <div class="head-2"> YOUNG LIVING PHILIPPINES LLC - PHILIPPINE BRANCH</div>';
        
        $output .= '
        <div style="width:100%">
        
        <p style="text-align:left;">Date: '. $date .'</p>
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">
            <thead>';

            foreach ($headers as $header) {
                $output .= '<th style="border: 1px solid;">' . $header . '</th>';
            }

            $output .=
            '</thead>
            <tbody>';
            
            if($items){
                foreach ($items as $data) {
                    $output .='
                    <tr>';
                        foreach ($columns as $column) {
                            if ($column == 'created_at') {
                                $data[$column] = Utils::formatDate($data[$column]);
                            }
                            if ($column == 'lot_code') {
                                $data[$column] = $data[$column] ? $data[$column] : 'N/A';
                            }
                            if (strpos($title, 'Stock Transfer') !== false && $column == 'status') {
                                    if ($data[$column] == 0) {
                                        $data[$column] = "Pending";
                                    }
                                    if ($data[$column] == 1) {
                                        $data[$column] = "Partially Transferred";
                                    }
                                    else if ($data[$column] == 2) {
                                        $data[$column] = "Transferred";
                                    }
                            }
                            $output .= '<td style="border: 1px solid; padding:10px;">'. $data[$column] .'</td>';
                        }                 
                    $output .='</tr>';
                } 
            }
            else{
                echo "No data found";
            }
          
            $output .='
            </tbody>
        </table>
            </div>';
    
        return $output;
        }
  
    }

    public static function renderCustomReport($items, $title, $headers, $columns, $date_from, $date_to)
    {  
        $hub_inventory = new HubInventory;
        $trans_line_item = new TransactionLineItems;
        if($date_from == $date_to) {
            $date = date("F j, Y", strtotime($date_from));
        }else {
            $date = date("F j, Y", strtotime($date_from)) .' - '. date("F j, Y", strtotime($date_to));
        }
        
        $output = '
        <div style="width:100%">
        <h1 style="text-align:center;">Flying High Energy Express</h1>
        <h2 style="text-align:center;">'. $title .'</h2>
        
        <p style="text-align:left;">Date: '. $date .'</p>
        <table width="100%" style="border-collapse:collapse; border: 1px solid;">
            <thead>';
        
            if (strpos($title, 'Inbound Transfer') !== false) {
                foreach ($headers as $header) {
                    $output .= '<th style="border: 1px solid;">' . $header . '</th>';
                }
                $output .= '<th style="border: 1px solid;">Order Number</th>';
                $output .= '<th style="border: 1px solid;">SKU</th>';
                $output .= '<th style="border: 1px solid;">Description</th>';
                $output .=
                '</thead>
                <tbody>';
                if($items){
                    foreach ($items as $data) {
                        $output .='<tr>';
                            $trans_line_items = $trans_line_item->getLineItems($data['transactionReferenceNumber']);
                            $line_items_count = count($trans_line_items);
                            foreach ($columns as $key => $column) {
                                $output .= '<td style="border: 1px solid; padding:10px;" rowspan="'.$line_items_count.'">'. $data[$column] .'</td>';
                                if ($key == count($columns)-1) {
                                    foreach ($trans_line_items as $key => $item) {
                                        if ($key == 0) {
                                            $output .= '<td style="border: 1px solid; padding:10px;">'. $item->orderNumber .'</td>';
                                            $output .= '<td style="border: 1px solid; padding:10px;">'. $item->itemNumber .'</td>';
                                            $output .= '<td style="border: 1px solid; padding:10px;">'. $item->description .'</td>';
                                        }
                                    }
                                }
                            }              
                        $output .='</tr>';

                        foreach ($trans_line_items as $key => $item) {
                            if ($hub_inventory->ignoreOtherSKU($item->itemNumber)) {
                                continue;
                            }
                            if ($key != 0) {
                                $output .='<tr>';
                                    $output .= '<td style="border: 1px solid; padding:10px;">'. $item->orderNumber .'</td>';
                                    $output .= '<td style="border: 1px solid; padding:10px;">'. $item->itemNumber .'</td>';
                                    $output .= '<td style="border: 1px solid; padding:10px;">'. $item->description .'</td>';
                                $output .='</tr>';
                            }
                        }
                    } 
                }
                else{
                    echo "No data found";
                }
            }
            else {
                $line_item = new LineItem;
                foreach ($headers as $header) {
                    $output .= '<th style="border: 1px solid;">' . $header . '</th>';
                }
                    $output .= '<th style="border: 1px solid;">Line Items</th>';
                $output .=
                '</thead>
                <tbody>';
                if($items){
                    foreach ($items as $data) {
                        $output .='<tr>';
                            $line_items = $line_item->getLineItems($data['orderId']);
                            $line_items_count = count($line_items)-4;
                            foreach ($columns as $key => $column) {
                                if ($column == 'custName') { 
                                    $output .= '<td style="border: 1px solid; padding:10px;" rowspan="'.$line_items_count.'">'. $data['custName'] . '<br>' . $data['customerEmail'] .'</td>';
                                }
                                else {
                                    $output .= '<td style="border: 1px solid; padding:10px;" rowspan="'.$line_items_count.'">'. $data[$column] .'</td>';
                                }
                                if ($key == count($columns)-1) {
                                    foreach ($line_items as $key => $item) {
                                        if ($key == 0) {
                                            $output .= '<td style="border: 1px solid; padding:10px;">'. $item->description .'</td>';
                                        }
                                    }
                                }
                            }              
                        $output .='</tr>';

                        foreach ($line_items as $key => $item) {
                            if ($hub_inventory->ignoreOtherSKU($item->partNumber)) {
                                continue;
                            }
                            if ($key != 0) {
                                $output .='<tr>';
                                $output .= '<td style="border: 1px solid; padding:10px;">'. $item->description .'</td>';
                                $output .='</tr>';
                            }
                        }
                    } 
                }
                else{
                    echo "No data found";
                }
            }
          
            $output .='
            </tbody>
        </table>
            </div>';
    
        return $output;
    }


    static function invoiceStyle() {
        return "<style>
            @page { margin: 18px 38px 15px 38px; }
            * { font-family: Calibri, sans-serif; }
            .text-left { text-align:left; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .text-bold { font-style: bold; }
            .phrase { font-size: 12px; }
            .head-3 { font-size: 14px; }
            .head-2 { font-size: 12px; font-style:bold; text-transform: uppercase; }
            .head-1 { font-size: 15px; font-style:bold; margin-top: 5px; }
            .logo { 
                object-fit: cover;
                position: absolute;
                right: 0;
                top: 1px;
            }
            .serial-number { 
                font-style: bold;
                font-size: 24px;
                position: absolute;
                right: 13px;
                top: 58px;
                color: #FF0000; 
            }
            .text-info { 
                margin-top: 3px; 
                font-size: 12px;
            }
            .mb-1 { margin-bottom:10px; }
            .ml-1 { margin-left:10px; }
            .ml-2 { margin-left:20px; }
            .ml-3 { margin-left:30px; }
            .mr-2 { margin-right:20px; }
            .mr-3 { margin-right:30px; }
            .mr-100 { margin-right:100px; }
            .mt-08 { margin-top:8px; }
            .mt-1 { margin-top:10px; }
            .mt-2 { margin-top:20px; }
            .mt-3 { margin-top:30px; }
            .mt-4 { margin-top:40px !important; }
            .mt-5 { margin-top:50px; }
            .mt-100 { margin-top:100px; }
            .float-right { float:right; }
            table { font-size: 12px; }
            td, th { padding: 2px !important; }
            th { border: 1px solid; }
            .table-computation td, th, .table-items th { padding: 2px !important; margin: 2px !important; }
            .table-footer td, th { padding: 10px !important; border: 1px solid; }
            .border-solid { border: 1px solid; }
            .border-bl { border-bottom: 1px solid black; border-left: 1px solid black; }
            .border-br { border-bottom: 1px solid black; border-right: 1px solid black; }
            .border-b { border-bottom: 1px solid black; }
            .border-lr { border-left: 1px solid black; border-right: 1px solid black; }
            .border-l { border-left: 1px solid black; }
            .border-r { border-right: 1px solid black; }
            span.peso {
                font-family: DejaVu Sans; sans-serif;
            }
            footer {
                position: fixed;
                bottom: 0px;
                left: 0px;
                right: 0px;
                margin-bottom: 0px;
            }
        </style>";
    }

    public function fileUpdoad($request, $folder_to_save = "img", $root = "assets/", $file_name = "") 
    {
        $img_path = "";
        if($request->hasFile('image')){ 
            if ($file_name=="") {
                $file_name = uniqid() . "." . $request->image->extension();
            }
            $request->image->move(public_path($root . $folder_to_save), $file_name);
            $img_path = $root . $folder_to_save . "/" . $file_name;
        }

        return $img_path;
    }

    public function removeFile($file_path)
    {
        if(File::exists(public_path($file_path))){
            File::delete(public_path($file_path));
            return true;
        }
        return 'file_not_exists.';
    }

    public static function formatDate($date, $format_time = true) 
    {
        if ($format_time) {
            return date('M d, Y h:m:s', strtotime($date));
        }
        return date('M d, Y', strtotime($date));
    }

    public static function CSVExporter($data, $type)
    {  
        $fileName = $type . '-' . date('Y-m-d h:m:s') . '.csv';
     
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = ""; 

        if ($type == 'subscribers') {
            $columns = array('Email', 'Status', 'Subscription Date');

            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($data as $item) {
                    if ( $item->status == 1 ) {
                        $status = 'Verified';
                    }
                    else if ( $item->status == 0 ) {
                        $status = 'Unverified';
                    }
                    $row['Email']              = $item->email;
                    $row['Status']             = $item->status == 1 ? 'Verified' : 'Unverified';
                    $row['Subscription Date']  = $item->created_at;

                    fputcsv($file, array($row['Email'], $row['Status'], $row['Subscription Date']));
                }

                fclose($file);
            };
        }

        return response()->stream($callback, 200, $headers);
    }

    public static function sendMail($email, $subject = "", $message = "", $mail_type = "") {
        
        if ($mail_type == 'confirm_subscription') {

            $key = self::getToken(100);
            $confirmation_link = url('/subscription/confirm?key=' . $key);

            Subscription::create([
                'email' => $email,
                'key' => $key,
                'status' => 0
            ]);
            
            $subject = "Confirm your subscription for Ang Pamilya Muna - Party List";
            $message = Utils::confirmSubscriptionTemplate($confirmation_link);
        }
        Mail::to($email)->send(new Mailer($subject, $message));
        return true;
    }

    public static function confirmSubscriptionTemplate($confirmation_link) {
     
        return "<p>Hi there,</p>
        <p>Please confirm your subscription for the Ang Pamilya Muna - Party List.</p>
        <!-- Action -->
        <a href='{$confirmation_link}' class='button button--green' target='_blank'>Click here to confirm your subscription!</a> <br><br>
        <p>If you received this email by mistake, simply delete it. You won't be subscribed if you don't click the confirmation link above.</p>
        <p>Thanks,
        <br>Ang Pamilya Muna - Party List</p>
        ";
    }

    public static function isEmailExists($email) {
        $res = Subscription::where('email', $email)->value('email');
        return isset($res) && $res ? true : false;
    }

    public static function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    public static function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::crypto_rand_secure(0, $max-1)];
        }

        return $token;
    }

    public static function convertNumberToWord($num = false, $currency = "")
    {
        $orig_num = $num;
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven',
            'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
        );
        $list2 = array('', 'Ten', 'Twenty', 'thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');
        $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'Octillion', 'Nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        $centavo = "";

        if (str_contains($orig_num, ".")) {
            $centavo = explode('.', $orig_num); 
            $centavo = $centavo[1];
            if ($centavo != "00") {
                $cent_text = $centavo > 0 ? " centavos" : "centavo";
                $centavo = " and " . self::convertNumberToWord($centavo) . $cent_text;
            }
            else {
                $centavo = "";
            }
        }

        return implode(' ', $words) . $currency . $centavo;
    }

    public static function is_decimal( $val )
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }

    static function timeAgo($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public static function abbreviateMonthNumber($month_number) {
        switch ($month_number) {
        case 1:
            return "JAN";
            break;
        case 2:
            return "FEB";
            break;
        case 3:
            return "MAR";
            break;
        case 4:
            return "APR";
            break;
        case 5:
            return "MAY";
            break;
        case 6:
            return "JUN";
            break;
        case 7:
            return "JUL";
            break;               
        case 8:
            return "AUG";
             break;                
        case 9:
            return "SEP";
            break;
        case 10:
            return "OCT";
            break;
         case 11:
            return "NOV";
            break;
        case 12:
            return "DEC";
            break;
        }
    }
}
?>

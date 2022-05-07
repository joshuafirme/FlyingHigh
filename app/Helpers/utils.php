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

    public static function getStatusTextClass($status) {
            $status_text = 'Unclaimed';
            $status_class = 'primary';
            if ($status == 1) {
                $status_text = 'Completed';
                $status_class = 'success';
            } else if ($status == 2) {
                $status_text = 'Overdue';
                $status_class = 'danger';
            } else if ($status == 3) {
                $status_text = 'Partially Completed';
                $status_class = 'warning';
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

    public static function renderReport($items, $title, $headers, $columns, $date_from, $date_to)
    {  
        if (strpos($title, 'For Pickup') !== false || strpos($title, 'Picked Up') !== false
            || strpos($title, 'Returned') !== false || strpos($title, 'Overdue') !== false
            || strpos($title, 'Inbound Transfer') !== false) { 
            return self::renderCustomReport($items, $title, $headers, $columns, $date_from, $date_to);
        }
        else {      if($date_from == $date_to) {
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

    public function formatDate($date) 
    {
        return date('M d, Y h:i a', strtotime($date));
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

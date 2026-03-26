<?php

namespace App\Action;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Helpers
{
    public static function codeId(int $az_customer_id) : string
    {
        $config_value = DB::table('az_config')->where('config_name', 'base_identifiant')->first('config_value')->config_value ?? 0;
        $tab = str_split($config_value + $az_customer_id, 2);
        return implode('-', $tab);
    }

    public static function isPremium(int $customer_number) : bool
    {
        if($customer_number)
        {
            $reservation = DB::table('reservation')
                                    ->join('sales', 'sales.Sales_Numbers', '=', 'reservation.sales_invoice')
                                    ->where('reservation.customers', $customer_number)
                                    ->whereIn('reservation.items', [62,63,64,65,66,67])
                                    ->select('reservation.ending_date', 'reservation.sales_invoice', 'sales.Amount_Paid')
                                    ->orderByDesc('reservation.reservation_id')
                                    ->first();
            if($reservation){
                $remaining_day = \Carbon\Carbon::parse('now')->diffInDays($reservation->ending_date);
                return $remaining_day > 0 && $reservation->Amount_Paid > 0;
            }
        }
        return false;
    }

    public static function customer_dbSet(?string $db_name) : bool
    {
        config()->set('database.connections.mysql2.database', str_replace(" ", "",$db_name));
        DB::purge('mysql2');
        $result=config('database.connections.mysql2.database');
        return $result !=null;
    }

    public static function companies_dbSet(?string $db_name) : bool
    {
        config()->set('database.connections.mysqlcompanies.database', str_replace(" ", "",$db_name));
        DB::purge('mysqlcompanies');
        $result=config('database.connections.mysqlcompanies.database');
        return $result !=null;
    }
    
    public static function subscriptionPlan(int $customer_number)
    {
        return DB::table('reservation')
                ->join('sales', 'sales.Sales_Numbers', '=', 'reservation.sales_invoice')
                ->where('reservation.customers', $customer_number)
                ->whereIn('reservation.items', [62,63,64,65,66,67])
                ->select('reservation.items', 'reservation.ending_date', 'reservation.sales_invoice', 'sales.Amount_Paid')
                ->orderByDesc('reservation.reservation_id')
                ->first();
        
    }
    
    public static function companyCurrency(int $id_currency = 0, ?int $id = null)
    {
        if(!$id_currency)
            $id_currency = DB::connection('mysql2')->table('website_info')
                                ->where('Customers_Numbers', $id ? $id : auth()->user()->linked_company['Customers_Numbers'])
                                ->first('currency')->currency ?? 1;
        
        return DB::table('currency_rate')
                ->where('id_currency', $id_currency)
                ->first();
        
    }
    
    public static function formatDate($date, $format='d/m/Y H:i')
    {
        return date_format(date_create($date), $format);
    }

    public static function formatMonthYear($dateString) {
        $date = new \DateTime($dateString);
        $formatter = new \IntlDateFormatter(
            app()->getLocale() == 'fr' ? 'fr_FR' :'en_US',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            'Europe/Paris',
            \IntlDateFormatter::GREGORIAN,
            'MMMM yyyy'
        );
        return $formatter->format($date);
    }

    public static function customerAddress(?int $customer_number)
    {
        $address = '';
        if($customer_number)
        {
            $record = DB::table('customers')
                        ->leftJoin('country', 'country.code', '=', 'customers.Country')
                        ->leftJoin('city', 'city.code_city', '=', 'customers.City')
                        ->where('customers.Customers_Numbers', $customer_number)
                        ->select('customers.Adresses', 'customers.Appt', 'customers.Postal_Code','city.Name as city_name', 'customers.City', 
                        'customers.Country', 'city.fr_Name as city_fr_name',  'country.Name as country_name', 'country.fr_Name as country_fr_name')
                        ->first();
            if($record)
            {
                if($record->Appt)
                    $address .= 'Appt n° '. $record->Appt . ' - ' . $record->Adresses;
                else
                    $address = $record->Adresses;
            
                if($record->City || $record->Country)
                {
                    $address .= ',';
                    if($record->City)
                    {
                        $address .= ' ';
                        if(app()->getLocale() == 'fr')
                            $address .= $record->city_fr_name;
                        else
                            $address .= $record->city_name;
    
                        if($record->Country)
                            $address .= ' -';
                    }
                    if($record->Country)
                    {
                        $address .= ' ';
                        if(app()->getLocale() == 'fr')
                            $address .= $record->country_fr_name;
                        else
                            $address .= $record->country_name;
                    }
                }
                if($record->Postal_Code)
                    $address .= '. '. $record->Postal_Code;
            }
        }
        return $address;
    }

    public static function getPath(string $name, int $privacy = 1) : string
    {
        $basepath = $privacy ? storage_path($name) : public_path($name);
        if (!file_exists($basepath)) {
            mkdir($basepath, 0777, true);
        }
        return $basepath;
    }
    
    public static function uploadFileToDisk(mixed $file, string $filename, string $extension) : bool|string
    {
        $basepath = self::getPath("app/private/uploads");
      
        $path = $basepath . '/'. $filename;
   
        if(in_array($extension, ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'zip', 'rar', 'tz', 'tar', 'text', 'docx', 'xls', 'xlsx']))
        {
            $istored = file_put_contents($path, gettype($file) == "string" ? $file : file_get_contents($file));
            if($istored)
                return $path;
        }

        return false;
    }

    public static function romanNum($key): string
    {
        $tab = [
            'Article I - ',
            'Article II - ',
            'Article III - ',
            'Article IV - ',
            'Article V - ',
            'Article VI - ',
            'Article VII - ',
            'Article VIII - ',
            'Article IX - ',
            'Article X - ',
            'Article XI - ',
            'Article XII - ',
            'Article XIII - ',
            'Article XIV - ',
            'Article XV - ',
            'Article XVI - ',
            'Article XVII - ',
            'Article XVII - ',
            'Article XIX - ',
            'Article XX - ',
            'Article XXI - ',
            'Article XXII - ',
            'Article XXIII - ',
            'Article XXIV - ',
            'Article XXV - ',
            'Article XXVI - ',
            'Article XXVII - ',
            'Article XXVIII - ',
            'Article XXIX - ',
            'Article XXX - ',
        ];
        return $tab[$key] ?? '';
    }
    public static function encryptData($data): bool|string
    {
        return openssl_encrypt(base64_encode($data), config('az_secret.cipher'), config('az_secret.key'));
    }

    public static function decryptData(string $data): bool|string
    {
        return base64_decode(openssl_decrypt($data, config('az_secret.cipher'), config('az_secret.key')));;
    }

    public static function formatEmails($tab): Collection
    {
        $reception = collect();
        foreach ($tab as $mails) {
            $mails = (array) $mails;
            if (key_exists('E-mails', $mails)) {
                if ($mails['E-mails'] != null and $mails['E-mails'] != "") {
                    $email = str_replace(" ", "", $mails['E-mails']);

                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $reception->push($email);
                    }
                }
            } elseif (key_exists('Email', $mails)) {
                if ($mails['Email'] != null and $mails['Email'] != "") {
                    $email = str_replace(" ", "", $mails['Email']);
                   
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $reception->push($email);
                    }
                }
            } elseif (key_exists('email', $mails)) {
                if ($mails['email'] != null and $mails['email'] != "") {
                    $email = str_replace(" ", "", $mails['email']);
                   
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $reception->push($email);
                    }
                }
            }
        }
        return $reception;
    }

    public static function translate(string $text, string $to_version, bool $required = false) : ?string
    {
        $translateText = null;
        $tr = new GoogleTranslate($to_version);
        try {
            $translateText = $tr->translate($text);
        } catch (\Exception $e) {
            if ($required) {
                $translateText = $text;
            }
        }

        return $translateText;
    }

    public static function getLocale(string $code_lang) : string
    {
        $tab = explode('-', $code_lang);
        return $tab[0] == 'fr' || $tab[0] == 'FR' ? 'fr' : 'en'; 
    }

    public static function getUrl(string $url) : string
    {
        if($user = auth()->user()){
            $url = $url . (str_contains($url, '?') ? '&' : '?'). 'az_token=' . $user->access_token;
        }
        return $url; 
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class IDGenerator extends Model
{
    use HasFactory;

    public static function generateID() {
        return round(microtime(true) * 1000);  
    }

    public static function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public static function generateRandString($numbers = 30) {
        return Str::random($numbers);
    }

    public static function generateIDandRandString($numbers = 30) {
        return round(microtime(true) * 1000) . '-' . Str::random($numbers);
    }

    public static function generateBillNumber($areaCode) {
        return $areaCode . substr(IDGenerator::generateID(), 6);
    }

    public static function getPercentage($dividend, $divisor) {
        $dividend = floatval($dividend);
        $divisor = floatval($divisor);

        if ($dividend==0 | $divisor == 0) {
            return 0;
        } else {
            $quotient = $dividend/$divisor;
            return round($quotient * 100, 2);
        }
    }

    public static function getAverage($dividend, $divisor) {
        $dividend = floatval($dividend);
        $divisor = floatval($divisor);

        if ($dividend==0 | $divisor == 0) {
            return 0;
        } else {
            $quotient = $dividend/$divisor;
            return $quotient;
        }
    }

    public static function getDifference($minuend, $subtrahend) {
        $minuend = floatval($minuend);
        $subtrahend = floatval($subtrahend);

        return $minuend - $subtrahend;
    }

    public static function getDaysDifference($from, $to) {        
        $datediff = strtotime($to) - strtotime($from);

        return round($datediff / (60 * 60 * 24));
    }
}

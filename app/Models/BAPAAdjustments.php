<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BAPAAdjustments
 * @package App\Models
 * @version May 25, 2022, 10:58 am PST
 *
 * @property string $BAPAName
 * @property string $ServicePeriod
 * @property string $DiscountPercentage
 * @property string $DiscountAmount
 * @property string $NumberOfConsumers
 * @property string $SubTotal
 * @property string $NetAmount
 * @property string $UserId
 * @property string $Route
 */
class BAPAAdjustments extends Model
{
    // use SoftDeletes;

    use HasFactory;

    public $table = 'Cashier_BAPAAdjustments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $fillable = [
        'id',
        'BAPAName',
        'ServicePeriod',
        'DiscountPercentage',
        'DiscountAmount',
        'NumberOfConsumers',
        'SubTotal',
        'NetAmount',
        'UserId',
        'Route',
        'Status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'BAPAName' => 'string',
        'ServicePeriod' => 'string',
        'DiscountPercentage' => 'string',
        'DiscountAmount' => 'string',
        'NumberOfConsumers' => 'string',
        'SubTotal' => 'string',
        'NetAmount' => 'string',
        'UserId' => 'string',
        'Route' => 'string',
        'Status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'id' => 'string',
        'BAPAName' => 'nullable|string|max:500',
        'ServicePeriod' => 'nullable',
        'DiscountPercentage' => 'nullable|string|max:255',
        'DiscountAmount' => 'nullable|string|max:255',
        'NumberOfConsumers' => 'nullable|string|max:255',
        'SubTotal' => 'nullable|string|max:255',
        'NetAmount' => 'nullable|string|max:255',
        'UserId' => 'nullable|string|max:255',
        'Route' => 'nullable|string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'Status' => 'nullable|string|max:255',
    ];

    public static function convertNumberToWord($num = false) {

        $ones = array(
            0 =>"ZERO",
            1 => "ONE",
            2 => "TWO",
            3 => "THREE",
            4 => "FOUR",
            5 => "FIVE",
            6 => "SIX",
            7 => "SEVEN",
            8 => "EIGHT",
            9 => "NINE",
            10 => "TEN",
            11 => "ELEVEN",
            12 => "TWELVE",
            13 => "THIRTEEN",
            14 => "FOURTEEN",
            15 => "FIFTEEN",
            16 => "SIXTEEN",
            17 => "SEVENTEEN",
            18 => "EIGHTEEN",
            19 => "NINETEEN",
            "014" => "FOURTEEN",
            '0' =>"ZERO",
            '01' => "ONE",
            '02' => "TWO",
            '03' => "THREE",
            '04' => "FOUR",
            '05' => "FIVE",
            '06' => "SIX",
            '07' => "SEVEN",
            '08' => "EIGHT",
            '09' => "NINE",
            '000' => "ZERO",
            '8' => "EIGHT",
        );
        $tens = array( 
            0 => "ZERO",
            1 => "TEN",
            2 => "TWENTY",
            3 => "THIRTY", 
            4 => "FORTY", 
            5 => "FIFTY", 
            6 => "SIXTY", 
            7 => "SEVENTY", 
            8 => "EIGHTY", 
            9 => "NINETY",
            '0' =>"ZERO",
            '01' => "ONE",
            '02' => "TWO",
            '03' => "THREE",
            '04' => "FOUR",
            '05' => "FIVE",
            '06' => "SIX",
            '07' => "SEVEN",
            '08' => "EIGHT",
            '09' => "NINE",
        ); 
        $hundreds = array( 
            "HUNDRED", 
            "THOUSAND", 
            "MILLION", 
            "BILLION", 
            "TRILLION", 
            "QUARDRILLION" 
        ); /*limit t quadrillion */
        $num = number_format($num,2,".",","); 
        $num_arr = explode(".",$num); 
        $wholenum = trim($num_arr[0]); 
        $decnum = trim($num_arr[1]); 
        $whole_arr = array_reverse(explode(",",$wholenum)); 
        krsort($whole_arr,1); 
        $rettxt = ""; 
        foreach($whole_arr as $key => $i){  
            
            while(substr($i,0,1)=="0")
                $i=substr($i,1,5);
                
                if(intval($i) < 20){ 
                    $xnum = intval($i);
                    $tnum = $ones[$xnum];

                    /* echo "getting:".$i; */
                    $rettxt .= $tnum; 
                } elseif ($i < 100){ 
                    if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
                    if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
                } else { 
                    if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
                    if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
                    if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
                } 
                if($key > 0){ 
                    $rettxt .= " ".$hundreds[$key]." "; 
                }
        } 
        $rettxt .= " PESOS";
        if($decnum > 0){
            $rettxt .= " and ";
            if($decnum < 20){
                $rettxt .= $ones[$decnum];
            } elseif ($decnum < 100){
                $rettxt .= $tens[substr($decnum,0,1)];
                $rettxt .= " ".$ones[substr($decnum,1,1)];
            }
            $rettxt .= " cents";
        }
        return $rettxt;
    }
}

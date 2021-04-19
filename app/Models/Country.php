<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mintbridge\EloquentAuditing\Auditable;
use Mintbridge\EloquentAuditing\AuditableInterface;

class Country extends Model implements AuditableInterface
{

    use Auditable;

    const MEASURE_SYSTEM_METRIC   = 'METRIC';
    const MEASURE_SYSTEM_IMPERIAL = 'IMPERIAL';
    const CURRENCY_SYMBOL_LEFT    = 'left';
    const CURRENCY_SYMBOL_RIGHT   = 'right';

    public static function getMeasureSystemOptions()
    {
        return [
            self::MEASURE_SYSTEM_METRIC   => self::MEASURE_SYSTEM_METRIC,
            self::MEASURE_SYSTEM_IMPERIAL => self::MEASURE_SYSTEM_IMPERIAL,
        ];
    }

    public static function getCurrencySymbol()
    {
        return [
            self::CURRENCY_SYMBOL_LEFT  => self::CURRENCY_SYMBOL_LEFT,
            self::CURRENCY_SYMBOL_RIGHT => self::CURRENCY_SYMBOL_RIGHT,
        ];
    }

    public static function getCountryDateFormat()
    {
        return array("yyyy-MM-dd" => "yyyy-MM-dd", "dd/MM/yyyy" => "dd/MM/yyyy", "d/MM/yyyy" => "d/MM/yyyy", "yyyy-M-d" => "yyyy-M-d", 'dd-MM-yyyy' => 'dd-MM-yyyy', "MM/dd/yyyy" => "MM/dd/yyyy");
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}

<?php

namespace Vudev\JsonWebToken;

/**
 * Date converting
 * 
 * A string with a short date, converted to a unixtime date
 * 
 * @author Mamedov Vusal
 */
class DateConvert {
    /**
	 * Converted format string time to unixtime
	 * 
	 * @param string $str
	 * @access public
     * @static
	 * @return int
	 */
    public static function to_unixtime(string $str): int
    {   
        $types = [
            'ms' => 'milliseconds',
            's' => 'seconds',
            'min' => 'minutes',
            'h' => 'hours',
            'd' => 'days',
            'mon' => 'months',
            'y' => 'years',
        ];
        $sel_type = '';

        foreach ($types as $key => $val) {
            preg_match('/'.$key.'/', $str, $matches);

            if ($matches[0]) {
                $sel_type = $matches[0];

                break;
            }
        }

        switch ($sel_type) {
            case 's':
                list($_seconds) = explode($sel_type, $str);

                $ret_time = time() + (int)$_seconds;

                break;
            case 'min':
                list($_minutes) = explode($sel_type, $str);

                $ret_time = time() + (60 * (int)$_minutes);

                break;
            case 'h':
                list($_hours) = explode($sel_type, $str);

                $ret_time = time() + (60 * 60 * (int)$_hours);
                
                break;
            case 'd':
                list($_days) = explode($sel_type, $str);

                $ret_time = time() + (60 * 60 * 24 * (int)$_days);
                
                break;
            case 'mon':
                list($_months) = explode($sel_type, $str);

                $ret_time = time() + (60 * 60 * 24 * 30 * (int)$_months);
                
                break;
            case 'y':
                list($_years) = explode($sel_type, $str);

                $ret_time = time() + (60 * 60 * 24 * 30 * 12 * (int)$_years);
                
                break;
        }

        return $ret_time;
    }
}
<?php

namespace App\Validators;
use DateTime;

class Datepicker
{

    public function validate(
        $attribute,
        $value,
        $parameters,
        $validator
    ){

        if(!empty($value)) {

            if( validateDate($value,'d/m/Y') ) {
                $value = str_replace('/', '-', $value);
            } else if( validateDate($value,'Y-m-d') ) {
            } else if( !validateDate($value) ) {
                return false;
            }

            $minMax = [];

            if( $parameters ) {
                foreach ($parameters as $k => $v) {
                    if(!$v) continue;
                    $v = explode(':', $v);
                    $minMax[$v[0]] = $v[1];
                }
            }

            if( isset($minMax['min']) ){
                if( strtotime( $minMax['min'] ) > strtotime( $value ) ) return false;
            }

            if( isset($minMax['max']) ){
                if( strtotime( $minMax['max'] ) < strtotime( $value ) ) return false;
            }

            return true;
        }

        return false;
    }

    public function message(
        $message, $attribute, $rule, $parameters
    ){

        $value = request()->$attribute;

        if(!empty($value)) {

            if( validateDate($value,'d/m/Y') ) {
                $value = str_replace('/', '-', $value);
            } else if( validateDate($value,'Y-m-d') ) {
            } else if( !validateDate($value) ) {
                return 'Incorrect date format';
            }

            $minMax = [];

            if( $parameters ) {
                foreach ($parameters as $k => $v) {
                    $v = explode(':', $v);
                    $minMax[$v[0]] = $v[1];
                }
            }

            if( isset($minMax['min']) ){
                if( strtotime( $minMax['min'] ) > strtotime( $value ) ) return 'Date must be on or after '.date('jS M Y', strtotime( $minMax['min'] ));
            }

            if( isset($minMax['max']) ){
                if( strtotime( $minMax['max'] ) < strtotime( $value ) ) return 'Date must be on or before '.date('jS M Y', strtotime( $minMax['max'] ));
            }

            return true;
        }

        return 'Incorrect date format';
    }

}
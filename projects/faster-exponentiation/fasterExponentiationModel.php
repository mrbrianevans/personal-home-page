<?php


class fasterExponentiationModel
{
    public function traditionalExponentiation($x, $exponent){
        return $x ** $exponent;
    }

    public function fasterExponentiation($x, $exponent){
        //Run complex routine to separate out the binary exponents like 2, 4, 8, 16 etc
        $numberOfExponents = (int)log($exponent, 2);
        $powers[0] = $x;
        for($i=1; $i<$numberOfExponents+1; $i++){
            $powers[$i] = $powers[$i-1] ** 2;
        }
        $answer = $powers[count($powers)-1];
        
    }
}
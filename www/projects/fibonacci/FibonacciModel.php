<?php


class FibonacciModel
{
    /**
     * @param $term int The term to calculate
     * This function uses recursion to calculate any term of the Fibonacci sequence
     * @return int The corresponding term of the Fibonacci sequence
     */
    public static function calculateFibonacciTerm(int $term)
    {
        switch ($term) {
            case 0:
                return 0;
            case 1:
            case 2:
                return 1;
            default:
                return self::calculateFibonacciTerm($term - 1) + self::calculateFibonacciTerm($term - 2);
        }
    }

    private array $calculatedTerms;
    public function __construct()
    {
        $this->calculatedTerms = [0=>0, 1=>1, 2=>1];
    }

    public function calculateFibonacciWithSaves(int $term)
    {
        if(isset($this->calculatedTerms[$term])) return $this->calculatedTerms[$term];
        else{
            $result = $this->calculateFibonacciWithSaves($term-1) + $this->calculateFibonacciWithSaves($term-2);
            $this->calculatedTerms[$term] = $result;
            return $result;
        }
    }
}

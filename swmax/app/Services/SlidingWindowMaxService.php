<?php

namespace App\Services;

use InvalidArgumentException;
use SplDoublyLinkedList;

class SlidingWindowMaxService
{
    /**
     * @param float[] $arr
     * @param int   $k
     * @return float[]
     */
    public function compute(array $arr, int $k): array
    {
        $n = count($arr);
        if ($k < 1 || $k > $n) {
            throw new InvalidArgumentException("Invalid k={$k} for n={$n}");
        }

        $dq = new SplDoublyLinkedList();
        $res = [];

        for ($i = 0; $i < $n; $i++) {

            if (!$dq->isEmpty() && $dq->bottom() <= $i - $k) {
                $dq->shift();
            }

            while (!$dq->isEmpty() && $arr[$dq->top()] <= $arr[$i]) {
                $dq->pop();
            }

            $dq->push($i);

            if ($i >= $k - 1) {
                $res[] = (float)$arr[$dq->bottom()];
            }
        }
        return $res;
    }
}
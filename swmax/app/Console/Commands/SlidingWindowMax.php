<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SlidingWindowMaxService;
use InvalidArgumentException;

class SlidingWindowMax extends Command
{
    protected $signature = 'metrics:swmax {--k=} {arr* : Числа (int|float)}';
    protected $description = 'Compute sliding window maximums';

    public function handle(SlidingWindowMaxService $svc)
    {
        $kRaw = $this->option('k');

        // 1) k — строго целое
        if (!is_string($kRaw) || !preg_match('/^-?\d+$/', $kRaw)) {
            $this->error("Некорректное значение для --k='{$kRaw}'. Ожидалось целое число (например: 1, 2, 3).");
            return Command::FAILURE;
        }
        $k = (int)$kRaw;

        // 2) arr — числа (int|float), строго проверяем is_numeric
        $arr = [];
        foreach ($this->argument('arr') as $i => $val) {
            if (!is_numeric($val)) {
                $this->error("Некорректное значение #{$i}: '{$val}'. Ожидалось число (int|float).");
                return Command::FAILURE;
            }
            $arr[] = (float)$val; // сохраняем дробные значения
        }

        try {
            $result = $svc->compute($arr, $k);
            $this->line(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION));
            return Command::SUCCESS;
        } catch (InvalidArgumentException $e) {
            $this->error("Ошибка: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
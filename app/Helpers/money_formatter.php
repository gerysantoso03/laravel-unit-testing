<?php

if(!function_exists('format_rupiah')){
    /**
     * Format price to Indonesian rupiah
     * 
     * @param int|float $value
     * @return string
     */
    function format_rupiah($value): string {
        if ($value === null) {
            return 'Rp 0';
        }

        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
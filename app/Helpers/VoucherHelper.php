<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class VoucherHelper
{
    /**
     * Generate debit voucher number with prefix (DV-1, DV-2, etc.)
     */
    public static function generateDebitVoucherNumber()
    {
        $prefix = 'DV'; // Debit Voucher
        $nextNumber = self::getNextNumber('debit_vouchers', 'voucher_number', $prefix);
        return $prefix . '-' . $nextNumber;
    }

    /**
     * Generate credit voucher number with prefix (CV-1, CV-2, etc.)
     */
    public static function generateCreditVoucherNumber()
    {
        $prefix = 'CV'; // Credit Voucher
        $nextNumber = self::getNextNumber('credit_vouchers', 'voucher_number', $prefix);
        return $prefix . '-' . $nextNumber;
    }

    /**
     * Generate journal entry number with prefix (JV-1, JV-2, etc.)
     */
    public static function generateJournalEntryNumber()
    {
        $prefix = 'JV'; // Journal Voucher
        $nextNumber = self::getNextNumber('journal_entries', 'entry_number', $prefix);
        return $prefix . '-' . $nextNumber;
    }

    /**
     * Generate contra entry number with prefix (CT-1, CT-2, etc.)
     */
    public static function generateContraEntryNumber()
    {
        $prefix = 'CT'; // Contra Transfer
        $nextNumber = self::getNextNumber('contra_entries', 'entry_number', $prefix);
        return $prefix . '-' . $nextNumber;
    }

    /**
     * Get next sequential number from table
     */
    private static function getNextNumber($table, $column, $prefix)
    {
        $records = DB::table($table)
            ->select($column)
            ->get();
        
        $maxNumber = 0;
        
        foreach ($records as $record) {
            $value = $record->$column;
            
            // Extract number from format like "DV-1", "DV-2", etc.
            if (preg_match('/^' . preg_quote($prefix) . '-(\d+)$/', $value, $matches)) {
                $number = (int) $matches[1];
            } elseif (is_numeric($value)) {
                // Handle plain numbers (backward compatibility)
                $number = (int) $value;
            } else {
                // Extract any number from string (fallback)
                preg_match('/(\d+)$/', $value, $matches);
                $number = isset($matches[1]) ? (int) $matches[1] : 0;
            }
            
            if ($number > $maxNumber) {
                $maxNumber = $number;
            }
        }
        
        return $maxNumber + 1;
    }

    /**
     * Format amount from paise to currency display
     */
    public static function formatAmount($amountInPaise)
    {
        return number_format($amountInPaise / 100, 2, '.', ',');
    }

    /**
     * Convert currency amount to paise
     */
    public static function convertToPaise($amount)
    {
        return (int) round($amount * 100);
    }

    /**
     * Validate debit and credit totals match (for journal entries)
     */
    public static function validateJournalEntry($totalDebit, $totalCredit)
    {
        return $totalDebit === $totalCredit;
    }

    /**
     * Validate contra entry has equal debit and credit amounts
     */
    public static function validateContraEntry($totalDebit, $totalCredit)
    {
        return $totalDebit === $totalCredit && $totalDebit > 0;
    }
}

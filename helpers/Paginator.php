<?php
/**
 * PAGINATOR HELPER
 * Helper untuk pagination
 */

class Paginator
{
    /**
     * Generate pagination metadata
     * 
     * @param int $total Total records
     * @param int $page Current page
     * @param int $limit Items per page
     * @return array
     */
    public static function make($total, $page, $limit)
    {
        $totalPages = ceil($total / $limit);
        
        return [
            'current_page' => $page,
            'per_page' => $limit,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ];
    }
}
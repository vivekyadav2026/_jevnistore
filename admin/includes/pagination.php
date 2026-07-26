<?php
/**
 * Renders pagination UI controls for admin panel tables.
 *
 * @param int $currentPage Current page number (1-indexed)
 * @param int $totalPages Total number of pages
 * @param int $totalItems Total number of items
 * @param int $perPage Items per page
 * @param string $pageUrl Base URL or page filename (e.g., 'products.php')
 * @param array $extraParams Additional GET query params to retain
 */
function renderPagination($currentPage, $totalPages, $totalItems, $perPage, $pageUrl = '', $extraParams = []) {
    if ($totalItems <= 0) return;
    
    $startItem = max(1, ($currentPage - 1) * $perPage + 1);
    $endItem = min($totalItems, $currentPage * $perPage);
    
    // Build query string helper
    $buildUrl = function($page) use ($pageUrl, $extraParams) {
        $params = array_merge($extraParams, ['page' => $page]);
        return ($pageUrl ?: $_SERVER['PHP_SELF']) . '?' . http_build_query($params);
    };
    
    echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 15px; background: #1e293b; padding: 12px 20px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">';
    
    // Counter summary
    echo '<div style="color: #94a3b8; font-size: 0.85rem;">';
    echo 'Showing <strong style="color: #f8fafc;">' . $startItem . '</strong> to <strong style="color: #f8fafc;">' . $endItem . '</strong> of <strong style="color: #f8fafc;">' . $totalItems . '</strong> entries';
    echo '</div>';
    
    if ($totalPages > 1) {
        echo '<div style="display: flex; align-items: center; gap: 6px;">';
        
        // Previous Button
        if ($currentPage > 1) {
            echo '<a href="' . htmlspecialchars($buildUrl($currentPage - 1)) . '" style="display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; background: #334155; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: all 0.2s;" title="Previous Page">&laquo; Prev</a>';
        } else {
            echo '<span style="display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; background: #0f172a; color: #475569; border: 1px solid rgba(255,255,255,0.05); border-radius: 6px; font-size: 0.85rem; cursor: not-allowed;">&laquo; Prev</span>';
        }
        
        // Page numbers window (show max 5 page links around current page)
        $range = 2;
        $startPage = max(1, $currentPage - $range);
        $endPage = min($totalPages, $currentPage + $range);
        
        if ($startPage > 1) {
            echo '<a href="' . htmlspecialchars($buildUrl(1)) . '" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #334155; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; text-decoration: none; font-size: 0.85rem;">1</a>';
            if ($startPage > 2) {
                echo '<span style="color: #64748b; padding: 0 4px;">...</span>';
            }
        }
        
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $currentPage) {
                echo '<span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: var(--accent, #38bdf8); color: #0f172a; font-weight: 700; border-radius: 6px; font-size: 0.85rem;">' . $i . '</span>';
            } else {
                echo '<a href="' . htmlspecialchars($buildUrl($i)) . '" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #334155; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; text-decoration: none; font-size: 0.85rem;">' . $i . '</a>';
            }
        }
        
        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                echo '<span style="color: #64748b; padding: 0 4px;">...</span>';
            }
            echo '<a href="' . htmlspecialchars($buildUrl($totalPages)) . '" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #334155; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; text-decoration: none; font-size: 0.85rem;">' . $totalPages . '</a>';
        }
        
        // Next Button
        if ($currentPage < $totalPages) {
            echo '<a href="' . htmlspecialchars($buildUrl($currentPage + 1)) . '" style="display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; background: #334155; color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: all 0.2s;" title="Next Page">Next &raquo;</a>';
        } else {
            echo '<span style="display: inline-flex; align-items: center; justify-content: center; padding: 6px 12px; background: #0f172a; color: #475569; border: 1px solid rgba(255,255,255,0.05); border-radius: 6px; font-size: 0.85rem; cursor: not-allowed;">Next &raquo;</span>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
}

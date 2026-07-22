<?php
/**
 * Tiny server-rendered chart helpers for the dashboard. HTML/CSS marks
 * (no JS, no external libs) styled from the palette variables defined in
 * layout_top.php. Forms follow the dataviz method: single-hue bars for
 * magnitude, status palette for state, direct labels, a baseline.
 */

/**
 * Vertical bar chart for a magnitude-over-time series (single hue).
 * @param array $points list of ['label'=>string, 'value'=>number, 'title'=>string]
 */
function chart_bars(array $points): void
{
    $max = 0;
    foreach ($points as $p) {
        $max = max($max, (float) $p['value']);
    }
    if ($max <= 0) {
        echo '<div class="empty">No activity in this window yet.</div>';
        return;
    }
    echo '<div class="barchart">';
    foreach ($points as $p) {
        $h = max(2, round(((float) $p['value'] / $max) * 128));
        $title = htmlspecialchars($p['title'] ?? ($p['label'] . ': ' . $p['value']));
        echo '<div class="col" title="' . $title . '">'
            . '<div class="bar" style="height:' . $h . 'px"></div>'
            . '<div class="xlab">' . htmlspecialchars($p['label']) . '</div>'
            . '</div>';
    }
    echo '</div><div class="chart-baseline"></div>';
}

/**
 * Horizontal breakdown bars.
 * @param array $rows list of ['name'=>string, 'value'=>number, 'display'=>string, 'color'=>cssvar]
 */
function chart_hbars(array $rows): void
{
    $max = 0;
    foreach ($rows as $r) {
        $max = max($max, (float) $r['value']);
    }
    if ($max <= 0) {
        echo '<div class="empty">Nothing to show yet.</div>';
        return;
    }
    echo '<div class="hbars">';
    foreach ($rows as $r) {
        $w = max(3, round(((float) $r['value'] / $max) * 100));
        $color = $r['color'] ?? 'var(--series-1)';
        echo '<div class="hbar-row">'
            . '<div class="name"><span class="swatch" style="background:' . $color . '"></span>' . htmlspecialchars($r['name']) . '</div>'
            . '<div class="hbar-track"><div class="hbar-fill" style="width:' . $w . '%;background:' . $color . '"></div></div>'
            . '<div class="val">' . htmlspecialchars($r['display'] ?? (string) $r['value']) . '</div>'
            . '</div>';
    }
    echo '</div>';
}

/** Map a transaction status to a status-palette CSS colour. */
function status_color(string $status): string
{
    switch ($status) {
        case 'VALID': return 'var(--good)';
        case 'INITIATED': return 'var(--warning)';
        case 'CANCELLED': return 'var(--baseline)';
        default: return 'var(--critical)'; // FAILED / VALIDATION_FAILED / INIT_FAILED
    }
}

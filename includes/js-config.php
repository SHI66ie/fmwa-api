<?php
/**
 * JS Config Bridge
 * Injects database settings into JavaScript for components like include-components.js
 */
?>
<script>
    window.fmwaConfig = {
        siteName: "<?php echo addslashes(get_setting('site_name', 'FEDERAL MINISTRY OF WOMEN AFFAIRS')); ?>",
        siteLogo: "<?php echo addslashes(get_setting('site_logo', 'images/2025_07_14_13_42_IMG_2808.PNG')); ?>",
        maintenanceMode: <?php echo get_setting('maintenance_mode') === 'true' ? 'true' : 'false'; ?>,
        siteUrl: "<?php echo addslashes(SITE_URL); ?>"
    };
</script>

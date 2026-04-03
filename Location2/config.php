<?php $env = parse_ini_file('.env'); ?>
<script>
    const CONFIG = { GOOGLE_MAPS_API_KEY: '<?= $env['GOOGLE_MAPS_API_KEY'] ?? '' ?>' };
</script>
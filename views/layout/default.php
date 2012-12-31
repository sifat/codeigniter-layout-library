<?php echo doctype('html5'); ?>
<html>
    <head>
        <?php echo meta($meta); ?>
        <title><?php echo $title_for_layout; ?></title>
        <?php echo $style_for_layout; ?>
        <?php echo $script_for_layout; ?>
    </head>

    <body>
        <?php echo $Layout->element('demo'); ?>
        <?php echo $contents_for_layout; ?>
    </body>

</html>
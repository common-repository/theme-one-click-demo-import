<div class="theme404-ocdi--plugins__list">
    <h4><?php esc_html_e('Required plugins for this demo.', 'theme404-one-click-demo-import'); ?></h4>
    <span class="sub-header">
        <?php esc_html_e('The following plugins are required to be installed for this demo:', 'theme404-one-click-demo-import'); ?>
    </span>

    <?php if ($data) : ?>

        <ul class="plugins--list">

            <?php
            $ocdiPlugin = theme404_ocdi()->plugins();

            foreach ($data['free'] as $plugin) : ?>
                <?php
                $coreFile = $plugin['coreFile'];
                $action   = '';
                $slug     = $plugin['plugin'];
                $label    = esc_html__('Ready to Install', 'theme404-one-click-demo-import');
                $class    = 'has-action';

                $iconClass  = 'dashicons-admin-plugins';

                if (!$ocdiPlugin->isInstalled($coreFile)) {
                    $action = 'install';
                } else {
                    if (!$ocdiPlugin->isActive($coreFile)) {
                        $action = 'activate';
                        $label  = esc_html__('Activate', 'theme404-one-click-demo-import');
                    } else {
                        $action = 'activated';
                        $label  = esc_html__('Active', 'theme404-one-click-demo-import');
                        $iconClass = 'dashicons-plugins-checked';
                        $class     = 'active';
                    }
                }

                $noncekey = -1;

                if ($action) {
                    $noncekey = "{$action}-{$slug}";
                }
                ?>
                <li class="d-flex justify-between flex-row align-center <?php echo esc_attr($class); ?>" data-slug="<?php echo esc_attr($slug); ?>" data-core-file="<?php echo esc_attr($coreFile); ?>" data-nonce="<?php echo wp_create_nonce($noncekey); ?>" data-action="<?php echo esc_attr($action); ?>" data-type="free">
                    <span class="plugin-name">
                        <span class="dashicons <?php echo esc_attr($iconClass); ?>"></span>
                        <?php echo esc_html($plugin['name']); ?>
                    </span>
                    <span class="plugin-action"><?php echo esc_html($label); ?></span>
                </li>
            <?php endforeach; ?>

            <?php if (isset($data['pro'])) : ?>

                <?php foreach ($data['pro'] as $plugin) : ?>
                    <?php
                    $coreFile = $plugin['coreFile'];
                    $action   = '';
                    $slug     = $plugin['plugin'];
                    $label    = esc_html__('Ready to Install', 'theme404-one-click-demo-import');
                    $class    = 'has-action';

                    $iconClass  = 'dashicons-admin-plugins';

                    if (!$ocdiPlugin->isInstalled($coreFile)) {
                        $action = 'install';
                    } else {
                        if (!$ocdiPlugin->isActive($coreFile)) {
                            $action = 'activate';
                            $label  = esc_html__('Activate', 'theme404-one-click-demo-import');
                        } else {
                            $action = 'activated';
                            $label  = esc_html__('Active', 'theme404-one-click-demo-import');
                            $iconClass = 'dashicons-plugins-checked';
                            $class     = 'active';
                        }
                    }

                    $noncekey = -1;

                    if ($action) {
                        $noncekey = "{$action}-{$slug}";
                    }
                    ?>
                    <li class="d-flex justify-between flex-row align-center <?php echo esc_attr($class); ?>" data-slug="<?php echo esc_attr($slug); ?>" data-core-file="<?php echo esc_attr($coreFile); ?>" data-nonce="<?php echo wp_create_nonce($noncekey); ?>" data-action="<?php echo esc_attr($action); ?>" data-type="pro" data-file="<?php echo esc_attr($plugin['plugin_file']); ?>">
                        <span class="plugin-name">
                            <span class="dashicons <?php echo esc_attr($iconClass); ?>"></span>

                            <?php echo esc_html($plugin['name']); ?>
                        </span>
                        <span class="plugin-action"><?php echo esc_html($label); ?></span>
                    </li>
                <?php endforeach; ?>

            <?php endif; ?>

        </ul>

    <?php endif; ?>
</div>
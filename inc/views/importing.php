<script type="text/template" id="tmpl-theme404-ocdi--step__importing">
    <div class="theme404-ocdi--import__action">

        {{{ data.loading }}}

        <h4><?php esc_html_e(
                'Please wait while the content is being imported!',
                'theme404-one-click-demo-import'
            );
            ?></h4>

        <span class="sub-header"><?php esc_html_e('This process may take upto 10 minutes to complete. Please do not refresh or close this page.', 'theme404-one-click-demo-import'); ?></span>

    </div>
</script>

<script type="text/template" id="tmpl-theme404-ocdi--step__button">
    <div class="theme404-ocdi--import__event">
        <div class="import-progress--bar">
           {{ data.prepare }}
        </div>
    </div>
</script>
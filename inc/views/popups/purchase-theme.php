<script id="tmpl-theme404-ocdi--purchase__theme" type="text/template">
    <div class="purchase--theme">

        <div class="swal2-icon swal2-{{data.icon}} swal2-icon-show" style="display: flex;">
            <div class="swal2-icon-content">i</div>
        </div>

        <div class="purhcase-theme--body">
            <h5><?php esc_html_e( 'Thank you for using our Free Theme.', 'theme404-one-click-demo-import');?></h5>
            <h4><?php esc_html_e( 'IMPORTANT NOTE: To access our Premium Demos, upgrade to Pro', 'theme404-one-click-demo-import');?></h4>

            <# if (data.purchase.link) { #>
                <a href="{{data.purchase.link}}" class="button-main button-large button-rounded" target="_blank">
                    {{ data.purchase.label }}
                </a>
            <# } #>
        </div>
    </div>
</script>
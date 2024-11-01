<script id="tmpl-theme404-ocdi--activate__theme" type="text/template">
    <div class="activate--theme">

        <div class="swal2-icon swal2-{{data.icon}} swal2-icon-show" style="display: flex;">
            <div class="swal2-icon-content">i</div>
        </div>

        <div class="activate-theme--body">
            <h5><?php esc_html_e( 'Thank you for purchasing our Premium Theme.', 'theme404-one-click-demo-import');?></h5>
            <h4>Please activate your copy of {{ data.theme }} to access our unlimited Premium Demos.</h4>

            <# if (data.activate.link) { #>
                <a href="{{data.activate.link}}" class="button-main button-large button-rounded">
                    {{ data.activate.label }}
                </a>
            <# } #>
        </div>
    </div>
</script>
<script type="text/template" id="tmpl-theme404-ocdi--content__choose">
    <div class="theme404-ocdi--demo__choose">
        <h5><?php esc_html_e( 'What would you like to import?', 'theme404-one-click-demo-import');?></h5>

        <form action="#" class="theme404-ocdi--content__form">
            <div class="content--chose">

                <div class="content-block chosen">
                    <div class="inner-block">
                        <label for="complete">
                            <input type="radio" name="contentType" id="complete" value="complete" checked class="import-selector">
                            <?php esc_html_e( 'Complete Demo', 'theme404-one-click-demo-import');?>
                        </label>
                        <sup><?php esc_html_e( 'recommended', 'theme404-one-click-demo-import');?></sup>
                    </div>
                    
                </div>
                <div class="content-block">
                    <div class="inner-block">
                        <label for="partial">
                            <input type="radio" class="import-selector" name="contentType" id="partial" value="partial">
                            <?php esc_html_e( 'Partial Import', 'theme404-one-click-demo-import');?>
                        </label>
                        <sup><?php esc_html_e( 'advanced user', 'theme404-one-click-demo-import');?></sup>
                    </div>
                    
                    <div class="input-group">
                        <label for="importContent">
                            <span><?php esc_html_e( 'Contents & Media', 'theme404-one-click-demo-import');?></span>
                            <input type="checkbox" name="partialConent[]" value="content" id="importContent" disabled>
                        </label>

                        <label for="importOption">
                            <span><?php esc_html_e( 'Options', 'theme404-one-click-demo-import');?>Options</span>
                            <input type="checkbox" name="partialConent[]" value="customizer" id="importOption" disabled>
                        </label>

                        <label for="importWidget">
                            <span><?php esc_html_e( 'Widgets', 'theme404-one-click-demo-import');?></span>
                            <input type="checkbox" name="partialConent[]" value="widgets" id="importWidget" disabled>
                        </label>

                        <label for="importSlider">
                            <span><?php esc_html_e( 'Sliders (If available)', 'theme404-one-click-demo-import');?></span>
                            <input type="checkbox" name="partialConent[]" value="slider" id="importSlider" disabled>
                        </label>
                    </div>

                </div>
            </div>
        </form>

        <div class="content-selector--note hidden">
            <h6><?php esc_html_e('We suggest you to import Complete Demo.', 'theme404-one-click-demo-import'); ?></h6>
        </div>

    </div>
</script>
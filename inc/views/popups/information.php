<script id="tmpl-theme404-ocdi--demo__information" type="text/template">
    <div class="install--demo step--info">
        <div class="install-demo--body">

            <div class="install-demo--body__content d-flex justify-between flex-row">

                <div class="install-demo--body__screenshot" style="<# if(data.image) { #> background-image: url( {{ data.image }} ); <# } #>" >

                    <div class="theme404-ocdi--item--name">
                        <h3>{{ data.name }} </h3>
                    </div>
                    
                </div>

                <div class="install-demo--body__steps">

                    <div class="install-demo--body__progress">

                        <div class="swal2-icon swal2-info swal2-icon-show" style="display: flex;">
                            <div class="swal2-icon-content">!</div>
                        </div>

                        <span>
                            We suggest you to import demo on <strong>clean installation</strong> of WordPress. We perform additional cleanup which may result in loss of your existing content. <strong>Proceed at your own risk.</strong>
                        </span>

                    </div>

                    <div class="install-demo--body__footer">
                        <a 
                            href="{{ data.preview }}" 
                            class="button-outline button-medium button-rounded"
                            target="_blank">
                            Preview
                        </a> 
                        <a 
                            href="#" 
                            class="button-main button-large button-rounded theme404-ocdi--action__list" 
                            data-action="list-plugins" 
                            data-nonce="<?php echo wp_create_nonce('list-plugins'); ?>"
                            data-target="install-demo--body__progress"
                            data-slug="{{ data.slug }}">
                            Continue
                        </a>    
                    </div>
                    
                </div>

                
            </div>

        </div>
    </div>
</script>
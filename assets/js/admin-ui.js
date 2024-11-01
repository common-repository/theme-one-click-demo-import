(function ($) {
    'use strict'

    let theme404ocdi = window.theme404ocdi || {}
    let theme404ocdiAjax = window.theme404ocdiAjax || {}
    let theme404ocdiHelpers = {}

    var pluginIndex = 0
    var stepsIndex = 0

    /**
     * All the AJAX related functions are defined here.
     */
    theme404ocdiAjax = {

        /**
         * Call the AJAX 
         * 
         * @return AJAX 
         */
        call: function (data) {
            return $.ajax({
                url: ajaxurl,
                method: 'POST',
                data
            })
        },

        /**
         * Retrieve the demo from the server.
         * 
         * @param object data 
         * 
         * @return bool|object $.ajax
         */
        getDemo: function (data) {
            return this.call(data)
        },

        /**
         * Retrieve the demo from the server.
         * 
         * @param object data 
         * 
         * @return bool|object $.ajax
         */
        listPlugins: function (data) {
            return this.call(data)
        },

        /**
         * Install the plugin.
         * 
         * @param object data 
         * 
         * @return $.ajax
         */
        installActivatePlugin: function (data) {
            return this.call(data)
        },

        /**
         * Prepare the demo import.
         * 
         * @param object data 
         * 
         * @return bool|object $.ajax
         */
        prepareImport: function (data) {
            return this.call(data)
        },

        /**
         * Imports the content.
         * 
         * @param object data 
         * 
         * @return bool|object $.ajax
         */
        import: function (data) {
            return this.call(data)
        },
    }

    /**
     * One click demo import helpers.
     */
    theme404ocdiHelpers = {

        showFailedPopup: function (data) {
            var template = wp.template('theme404-ocdi--failed')
            var html = template(data)

            Swal.fire({
                html
            })
        }
    }

    theme404ocdi = {

        init: function () {
            this.registerVars()
            this.bind()
        },

        /**
         * Register the properties
         */
        registerVars: function () {
            this.doc = $(document)
            this.win = $(window)
            this.themeWrapper = $('.theme404-ocdi--demo__browser')
            this.allThemes = document.querySelectorAll('.theme')
            this.ajaxlocked = false
            this.pluginCounter = 0
            this.loading = `<div class="swal2-actions swal2-loading"><button class="swal2-styled swal2-confirm"></button></div>`
        },

        // Bind all the functions together.
        bind: function () {
            this.attachClick()
            this.searchDemos()
        },

        /**
         * Attach the click events to the DOM.
         */
        attachClick: function () {
            var doc = this.doc

            this.doc.on('click', '.theme404-ocdi--filter a', this.filterDemos)
            this.doc.on('click', '.more-details--demo', this.retrieveDemo)
            this.doc.on('click', '.theme404-ocdi--action__list', this.listPlugins)
            this.doc.on('click', '.theme404-ocdi--action__install', this.installPlugins)
            this.doc.on('click', '.theme404-ocdi--action__import', this.prepareImport)

            this.doc.on('click', '.import-selector', (event) => {
                var target = event.currentTarget || event.target,
                    parent = target.parentNode.parentNode.parentNode,
                    value = target.value

                if (parent.classList.contains('chosen')) {
                    return
                }

                doc.find('.content-block').removeClass('chosen');

                if ('partial' === value) {
                    $('.content-selector--note').removeClass('hidden')
                } else {
                    $('.content-selector--note').addClass('hidden')
                }

                parent.classList.add('chosen')

                if ('partial' === value) {
                    $(parent).find('input[type=checkbox]').removeAttr('disabled')
                } else {
                    $(parent).parent().find('input[type=checkbox]').attr('disabled', 'disabled')
                }
            })
        },

        /**
         * Filter the demos.
         * 
         * @param object event  Click event 
         */
        filterDemos: function (event) {
            event.preventDefault()
            let el = event.currentTarget
            let val = el.getAttribute('data-filter')

            if (el.classList.contains('current')) {
                return;
            }

            var parentUl = el.closest('ul')
            parentUl.querySelector('.current').classList.remove('current')

            el.classList.add('current')

            let allThemes = theme404ocdi.themeWrapper[0].getElementsByClassName('theme')

            var count = 0;
            for (let theme of allThemes) {

                if ('all' !== val) {
                    if (!theme.classList.contains(val)) {
                        theme.classList.remove('demo--visible')
                        theme.style.display = "none";
                    } else {
                        theme.classList.add('demo--visible')
                        theme.style.display = "block"
                        count++
                    }
                } else {
                    theme.classList.add('demo--visible')
                    theme.style.display = 'block'
                }
            }

            var countEl = document.querySelector('.theme-count')

            if (count <= 0) {
                countEl.innerHTML = allThemes.length
            } else {
                countEl.innerHTML = count
            }

        },

        /**
         * Searches the demo from the search input.
         */
        searchDemos: function () {
            var searchInput = document.querySelector('.theme404-ocdi--search__input');
            let allThemes = document.querySelectorAll('.theme')

            searchInput.addEventListener('keyup', (e) => {
                var searchText = e.target.value

                if (0 > searchText.length) {
                    return
                }

                searchText = searchText.toLowerCase()

                var count = 0;
                for (var theme of allThemes) {
                    var slug = theme.dataset.slug

                    if (slug.toLowerCase().indexOf(searchText) > -1) {
                        theme.style.display = "block";
                        theme.classList.add('demo--visible')
                        count++;
                    } else {
                        theme.style.display = "none";
                        theme.classList.remove('demo--visible')
                    }
                }

                var countEl = document.querySelector('.theme-count')
                countEl.innerHTML = count
            })
        },

        /**
         * Prepares the demo.
         * 
         * @param object event 
         */
        retrieveDemo: function (event) {
            event.preventDefault();

            var targetEl = event.currentTarget

            let demoType = targetEl.dataset.type || 'free'
            let themeType = theme404ocdiData.themeType || 'free'
            let theme = theme404ocdiData.themeName
            let activated = theme404ocdiData.activated || false
            let demo = targetEl.dataset.slug
            let nonce = targetEl.dataset.nonce
            let data = {
                action: 'retrieve-demo',
                demo,
                nonce,
                demoType,
                themeType
            }

            if (theme404ocdi.ajaxlocked) {
                // One click demo import AJAX is already running.
                return
            }

            if ('pro' === demoType) {
                // User wants to import 'Pro' demo.
                // So we need to check additional parameters.

                var proceed = true,
                    tplHtml
                if ('free' === themeType) {

                    // Purchase theme.
                    proceed = false
                    let template = wp.template('theme404-ocdi--purchase__theme')
                    tplHtml = template({
                        icon: 'info',
                        theme,
                        purchase: {
                            label: theme404ocdiData.purchaseLabel,
                            link: targetEl.dataset.purchaseLink
                        }
                    })

                } else if ('pro' === themeType && 'false' == activated) {
                    // Installed pro theme but not activated.

                    proceed = false
                    let template = wp.template('theme404-ocdi--activate__theme')
                    tplHtml = template({
                        icon: 'info',
                        theme,
                        activate: {
                            label: 'Activate',
                            link: theme404ocdiData.activateLink
                        }
                    })

                }

                if (!proceed) {
                    // We don't need to proceed further.
                    Swal.fire({
                        showConfirmButton: false,
                        html: tplHtml
                    })

                    return
                }

                theme404ocdi.ajaxlocked = true

                // Pro theme so set the license key to retrieve demo.
                data.key = theme404ocdiData.license
            }

            $(targetEl).parent('.theme').addClass('loading')


            if (!navigator.onLine) {
                // No internet connection detected so fail.
                let tplData = {
                    title: theme404ocdiData.offlineTitle,
                    message: theme404ocdiData.offlineMsg
                }

                $(targetEl).parent('.theme').removeClass('loading')
                theme404ocdi.ajaxlocked = false

                theme404ocdiHelpers.showFailedPopup(tplData)
                return
            }

            var ajaxResponse = theme404ocdiAjax.getDemo(data)

            ajaxResponse.done((response, status, xhr) => {

                if (response.success) {

                    $(targetEl).parent('.theme').removeClass('loading')
                    theme404ocdi.ajaxlocked = false

                    let template = wp.template('theme404-ocdi--demo__information')

                    let tplData = response.data.demo

                    let html = template(tplData)

                    // Display sweetalert.
                    Swal.fire({
                        showCloseButton: true,
                        focusCancel: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        width: '70%',
                        padding: '1%',
                        html
                    });

                } else {

                    $(targetEl).parent('.theme').removeClass('loading')
                    theme404ocdi.ajaxlocked = false

                    let tplData = {
                        title: response.data.title,
                        message: response.data.message
                    }

                    theme404ocdiHelpers.showFailedPopup(tplData)
                }
            })

            // When AJAX fails.
            ajaxResponse.fail((xhr, status, error) => {

                $(targetEl).parent('.theme').removeClass('loading')
                theme404ocdi.ajaxlocked = false

                let tplData = {
                    title: error,
                    message: theme404ocdiData.failed
                }

                theme404ocdiHelpers.showFailedPopup(tplData)

            })
        },

        /**
         * List the plugins used for selected demo.
         */
        listPlugins: function (event) {
            event.preventDefault();
            var el = event.currentTarget || event.target,
                target = el.dataset.target,
                action = el.dataset.action,
                nonce = el.dataset.nonce,
                slug = el.dataset.slug,
                data = {
                    action,
                    nonce,
                    slug
                }

            if (theme404ocdi.ajaxlocked) {
                return
            }

            theme404ocdi.ajaxlocked = true

            var targetEl = document.querySelector('.' + target)
            targetEl.innerHTML = theme404ocdi.loading

            $(el).fadeOut().siblings().fadeOut()

            var ajaxResponse = theme404ocdiAjax.listPlugins(data)

            setTimeout(() => {

                ajaxResponse.done(function (response, status, xhr) {
                    if (response.success) {

                        if ('import' === response.data.step) {

                            var template = wp.template('theme404-ocdi--content__choose')
                            var html = template()


                            el.dataset.action = 'prepare-import'
                            el.dataset.nonce = theme404ocdiData.nonce
                            el.innerText = theme404ocdiData.importContent
                            el.classList.remove('theme404-ocdi--action__list')
                            el.classList.add('theme404-ocdi--action__import')

                            targetEl.innerHTML = html


                        } else {
                            el.dataset.action = 'install-plugins'
                            el.innerText = theme404ocdiData.installPlugins
                            el.classList.remove('theme404-ocdi--action__list')
                            el.classList.add('theme404-ocdi--action__install')

                            targetEl.innerHTML = response.data.html
                        }

                        $(targetEl).parent('.theme').removeClass('loading')
                        theme404ocdi.ajaxlocked = false

                        $(el).fadeIn()



                    } else {
                        $(targetEl).parent('.theme').removeClass('loading')
                        theme404ocdi.ajaxlocked = false

                        let tplData = {
                            title: error,
                            message: theme404ocdiData.failed
                        }

                        theme404ocdiHelpers.showFailedPopup(tplData)
                    }


                })

                ajaxResponse.fail(function (jqXHR, textStatus, error) {
                    $(targetEl).parent('.theme').removeClass('loading')
                    theme404ocdi.ajaxlocked = false

                    let tplData = {
                        title: error,
                        message: theme404ocdiData.failed
                    }

                    theme404ocdiHelpers.showFailedPopup(tplData)

                })

            }, 500);
        },

        /**
         * Install plugins
         */
        installPlugins: function (event, id) {
            event.stopPropagation()
            event.preventDefault()

            var el = event.currentTarget || event.target,
                parent = el.parentNode,
                nonce = el.dataset.nonce,
                slug = el.dataset.slug,
                target = el.dataset.target,
                $plugins = $('.' + target).find('.has-action'),
                totalPlugins = $plugins.length

            if ('undefined' == id || null == id) {
                var count = pluginIndex
            } else {
                var count = id
            }

            if (theme404ocdi.ajaxlocked) {
                return
            }

            if (count < totalPlugins) {

                var data = {}

                data.demo = slug

                let plugin = $plugins[count]
                let action = plugin.dataset.action
                let type = plugin.dataset.type

                data.nonce = plugin.dataset.nonce
                data.slug = plugin.dataset.slug

                theme404ocdi.ajaxlocked = true

                var actionEl = plugin.querySelector('.plugin-action')

                if ('activate' === action) {

                    data.action = 'ocdi-activate-plugin'
                    data.coreFile = plugin.dataset.coreFile

                    actionEl.innerHTML = theme404ocdiData.activating

                } else if ('install' === action) {

                    data.action = 'ocdi-install-plugin'

                    actionEl.innerHTML = theme404ocdiData.installing

                    if ('pro' === type) {
                        data.type = 'pro'
                        data.file = plugin.dataset.file
                        data.theme = theme404ocdi.themeName
                    }
                }

                $(el).fadeOut('0', () => {
                    if ($(parent).find('.swal2-actions').length < 1) {
                        $(parent).append(theme404ocdi.loading)
                    }
                })

                var ajaxResponse = theme404ocdiAjax.installActivatePlugin(data)

                ajaxResponse.done(function (response, status, xhr) {

                    if (response.success) {

                        theme404ocdi.ajaxlocked = false

                        if ('activated' == response.data.status) {
                            // Plugin is activated, proceed installation of next plugin.
                            count++

                            actionEl.innerHTML = theme404ocdiData.active

                            if (count < totalPlugins) {
                                setTimeout(() => {
                                    theme404ocdi.installPlugins(event, count)
                                }, 300);

                            } else {

                                $(parent).find('.swal2-actions').remove()

                                var template = wp.template('theme404-ocdi--content__choose')
                                var html = template()

                                let targetEl = document.querySelector('.' + target)

                                el.dataset.action = 'prepare-import'
                                el.dataset.nonce = theme404ocdiData.nonce
                                el.innerText = theme404ocdiData.importContent
                                el.classList.remove('theme404-ocdi--action__list')
                                el.classList.add('theme404-ocdi--action__import')

                                targetEl.innerHTML = html


                                $(el).fadeIn()
                            }

                        }

                        if ('activate' == response.data.status) {
                            // Activate the plugin.

                            plugin.dataset.action = 'activate'
                            plugin.dataset.nonce = response.data.nonce

                            actionEl.innerHTML = theme404ocdiData.activating

                            if (count < totalPlugins) {
                                theme404ocdi.installPlugins(event, count)
                            }
                        }

                    } else {
                        theme404ocdi.ajaxlocked = false

                        let tplData = {
                            title: theme404ocdiData.failedTitle,
                            message: theme404ocdiData.failed
                        }

                        theme404ocdiHelpers.showFailedPopup(tplData)
                    }
                })

                ajaxResponse.fail(function (jqXHR, textStatus, errorThrown) {
                    theme404ocdi.ajaxlocked = false

                    let tplData = {
                        title: theme404ocdiData.failedTitle,
                        message: theme404ocdiData.failed
                    }

                    theme404ocdiHelpers.showFailedPopup(tplData)
                })
            }
        },

        /**
         * Prepare the import process.
         * 
         * @param  event 
         */
        prepareImport: function (event) {
            event.preventDefault()

            var el = event.currentTarget || event.target,
                parent = el.parentNode,
                nonce = el.dataset.nonce,
                slug = el.dataset.slug,
                target = el.dataset.target,
                data = {
                    slug,
                    nonce
                }

            var targetEl = document.querySelector('.' + target)

            var $form = $(targetEl).find('form')

            var formData = $form.serializeArray()

            var contentType = formData[0].value

            data.importType = contentType

            var steps = []

            if ('partial' === contentType) {

                for (var i = 1; i <= formData.length; i++) {
                    var obj = formData[i]

                    if ('undefined' !== typeof obj) {
                        steps.push(obj.value);
                    }
                }

                if (steps.length < 1) {
                    $('input[value="' + contentType + '"]')
                        .parents('.content-block')
                        .addClass('error');
                    return;
                } else {
                    $('input[value="' + contentType + '"]')
                        .parents('.content-block')
                        .removeClass('error');
                }

            } else {
                steps.push('menu')
                steps.push('pages')
            }

            steps.push('finalize')

            data.action = 'prepare-import'
            data.steps = steps

            theme404ocdi.ajaxlocked = true

            let template = wp.template('theme404-ocdi--step__importing')

            targetEl.innerHTML = template({
                loading: theme404ocdi.loading
            })

            var progress = wp.template('theme404-ocdi--step__button')
            var progressHtml = progress({
                prepare: theme404ocdiData.prepare
            })


            $(el).hide(0, () => {
                $(parent).append(progressHtml)
            })

            var ajaxResponse = theme404ocdiAjax.prepareImport(data)

            ajaxResponse.done(function (response, status, xhr) {
                theme404ocdi.ajaxlocked = false

                if (response.success) {

                    var data = response.data
                    var files = data.files

                    var filesKey = Object.keys(files);

                    if ('complete' === contentType) {
                        steps = filesKey.concat(steps.filter((item) => filesKey.indexOf(item) < 0))
                        data.steps = steps
                    }

                    var firstStep = steps[0]

                    data.action = `${firstStep}-import`

                    setTimeout(() => {
                        $(parent)
                            .find('.import-progress--bar')
                            .text(theme404ocdiData[firstStep])
                    }, 500);

                    theme404ocdi.import(event, data)
                } else {

                    let tplData = {
                        title: theme404ocdiData.failedTitle,
                        message: theme404ocdiData.offlineMsg
                    }

                    theme404ocdiHelpers.showFailedPopup(tplData)
                }
            })

            ajaxResponse.fail(function (jqXHR, textStatus, errorThrown) {
                theme404ocdi.ajaxlocked = false

                let tplData = {
                    title: theme404ocdiData.failedTitle,
                    message: theme404ocdiData.tryAgain
                }

                theme404ocdiHelpers.showFailedPopup(tplData)

            })

            return;

        },

        /**
         * Start the import process.
         * 
         * @param object event
         * @param object data
         */
        import: function (event, response) {
            var el = event.currentTarget || event.target,
                parent = el.parentNode,
                nonce = response.nonce,
                slug = el.dataset.slug,
                totalSteps = response.steps.length,
                steps = response.steps,
                data = {
                    slug,
                    nonce,
                    action: response.action,
                    steps: response.steps,
                    files: response.files
                }

            if (stepsIndex > totalSteps) {
                theme404ocdi.complete(response)
                return
            }

            if (stepsIndex < totalSteps) {

                data.stepsIndex = stepsIndex

                theme404ocdi.ajaxlocked = true

                var ajaxResponse = theme404ocdiAjax.import(data)

                ajaxResponse.done((res, status, xhr) => {
                    theme404ocdi.ajaxlocked = false

                    if (res.success) {

                        var actionName = res.data.action

                        if ('content-import' == actionName) {
                            // We don't have to set anything.
                            // re-iterate thorough the process.

                            response = res.data
                            theme404ocdi.import(event, response)
                            return

                        } else if ('finalized' == actionName) {
                            theme404ocdi.complete(event, res.data)
                        } else {

                            // Proceed to other steps.
                            stepsIndex++
                            var step = steps[stepsIndex]
                            response.stepsIndex = stepsIndex
                            response.action = `${step}-import`
                            response.nonce = res.data.nonce

                            $(parent)
                                .find('.import-progress--bar')
                                .text(theme404ocdiData[step])

                            setTimeout(() => {
                                theme404ocdi.import(event, response)
                            }, 1000)

                        }

                    } else {
                        // Cannot proceed with the import due to some problems.

                        let tplData = {
                            title: res.data.title,
                            message: res.data.message
                        }

                        theme404ocdiHelpers.showFailedPopup(tplData)
                    }
                })

                ajaxResponse.fail((xhr, status, err) => {
                    // Cannot proceed with the Import.
                    theme404ocdi.ajaxlocked = false

                    let tplData = {
                        title: err,
                        message: theme404ocdiData.failed
                    }

                    theme404ocdiHelpers.showFailedPopup(tplData)
                })

            }
        },

        // Finalize the import process.
        complete: function (event, data) {
            var el = event.currentTarget || event.target,
                parent = el.parentNode,
                target = el.dataset.target

            var template = wp.template('theme404-ocdi--import__complete')
            var html = template()

            let targetEl = document.querySelector('.' + target)
            targetEl.innerHTML = html

            parent.innerHTML = ''
        }

    }

    $(function () {
        // DOM is ready.
        theme404ocdi.init();
    })


})(jQuery)
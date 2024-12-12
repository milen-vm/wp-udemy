import $ from 'jquery'

class Search {
    #timeOut = 1000
    #openButton
    #closeButton
    #searchOverlay
    #searchField
    #previousValue
    #typingTimer
    #resultsDiv
    #isOverlayOpen
    #isSpinnerVisible
    #globalData = universityData

    // Describe and create/initiate our object
    constructor() {
        this.addSearchHtml()

        this.#openButton = $('.js-search-trigger')
        this.#closeButton = $('.search-overlay__close')
        this.#searchOverlay = $('.search-overlay')
        this.#searchField = $('#search-term')
        this.#resultsDiv = $('#search-overlay__results')
        this.#isOverlayOpen = false
        this.#isSpinnerVisible = false

        this.events()
    }

    // Events
    events() {
        this.#openButton.on('click', this.openOverlay.bind(this))
        this.#closeButton.on('click', this.closeOverlay.bind(this))
        $(document).on('keydown', this.keyPressDispatcher.bind(this))
        this.#searchField.on('keyup', this.typingLogic.bind(this))
    }

    // Methods
    openOverlay() {
        this.#searchOverlay.addClass('search-overlay--active')
        this.#isOverlayOpen = true
        $('body').addClass('body-no-scroll')
        // Set timeout unitl overlay is fully showed/loaded
        setTimeout(() => this.#searchField.focus(), 300)
    }

    closeOverlay() {
        this.#searchOverlay.removeClass('search-overlay--active')
        this.#isOverlayOpen = false
        $('body').removeClass('body-no-scroll')
        // Clear search window content.
        this.#searchField.val('')
        this.#resultsDiv.html('')
    }

    keyPressDispatcher(e) {
        let inpusts = $('input, textarea')
        if(
            e.keyCode === 83 && 
            !this.#isOverlayOpen && 
            !(inpusts.length && inpusts.is(':focus'))
        ) {
            this.openOverlay()
        }

        if(e.keyCode === 27 && this.#isOverlayOpen) {
            this.closeOverlay()
        }
    }

    typingLogic() {
        let currentValue = this.#searchField.val()
        if(this.#previousValue === currentValue) {

            return
        }

        clearInterval(this.#typingTimer)

        if(!currentValue.trim()) {
            this.#resultsDiv.html('')
            this.#isSpinnerVisible = false

            return
        }

        if(!this.#isSpinnerVisible) {
            this.#resultsDiv.html('<div class="spinner-loader"></div>')
            this.#isSpinnerVisible = true
        }

        this.#typingTimer = setTimeout(this.getResults.bind(this), this.#timeOut)
        this.#previousValue = currentValue
    }

    getResults() {
        let url = this.#globalData.root_url + '/wp-json/wp/v2/posts?search=' + this.#searchField.val()
        $.getJSON(url, (data) => {
            this.#resultsDiv.html(`
                <h2 class="search-overlay__section-title">General Information</h2>
                ${data.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                    ${data.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
                ${data.length ? '</ul>' : ''}
            `)

            this.#isSpinnerVisible = false
        })
    }

    addSearchHtml() {
        $('body').append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input id="search-term" type="text" class="search-term" placeholder="What are you looking for?" autocomplete="off">
                        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="container">
                    <div id="search-overlay__results"></div>
                </div>
            </div>
        `)
    }
}

export default Search
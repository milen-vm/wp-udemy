import $ from 'jquery'

class Search {
    #openButton
    #closeButton
    #searchOverlay
    #searchField
    #previousValue
    #typingTimer
    #resultsDiv
    #isOverlayOpen
    #isSpinnerVisible

    // Describe and create/initiate our object
    constructor() {
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
    }

    closeOverlay() {
        this.#searchOverlay.removeClass('search-overlay--active')
        this.#isOverlayOpen = false
        $('body').removeClass('body-no-scroll')
    }

    keyPressDispatcher(e) {
        if(e.keyCode === 83 && !this.#isOverlayOpen && $('input, textarea').is(':focus')) {
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

        this.#typingTimer = setTimeout(this.getResults.bind(this), 2000)
        this.#previousValue = currentValue
    }

    getResults() {
        this.#resultsDiv.html('Results DIV...000')
        this.#isSpinnerVisible = false
    }
}

export default Search
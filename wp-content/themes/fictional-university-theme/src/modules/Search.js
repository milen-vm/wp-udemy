import $ from 'jquery'

class Search {
    #openButton
    #closeButton
    #searchOverlay
    #serachField
    #isOverlayOpen

    // Describe and create/initiate our object
    constructor() {
        this.#openButton = $('.js-search-trigger')
        this.#closeButton = $('.search-overlay__close')
        this.#searchOverlay = $('.search-overlay')
        this.#serachField = $('#search-term')
        this.#isOverlayOpen = false

        this.events()
    }

    // Events
    events() {
        this.#openButton.on('click', this.openOverlay.bind(this))
        this.#closeButton.on('click', this.closeOverlay.bind(this))
        $(document).on('keydown', this.keyPressDispatcher.bind(this))
    }

    // Methods
    openOverlay() {
        this.#searchOverlay.addClass('search-overlay--active')
        this.#serachField.focus()
        this.#isOverlayOpen = true
        $('body').addClass('body-no-scroll')
    }

    closeOverlay() {
        this.#searchOverlay.removeClass('search-overlay--active')
        this.#isOverlayOpen = false
        $('body').removeClass('body-no-scroll')
    }

    keyPressDispatcher(e) {
        if(e.keyCode === 83 && !this.#isOverlayOpen) {
            this.openOverlay()
        }

        if(e.keyCode === 27 && this.#isOverlayOpen) {
            this.closeOverlay()
        }
    }
}

export default Search
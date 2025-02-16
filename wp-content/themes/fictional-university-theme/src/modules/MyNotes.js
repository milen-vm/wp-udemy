import $ from 'jquery'

class MyNotes {

    constructor() {
        this.events();
    }

    events() {
        $('.delete-note').on('click', this.deleteNote)
    }

    // Methods
    deleteNote() {
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/293',
            type: 'DELETE',
            success: (response) => {
                console.log('ok')
                console.log(response)
            },
            error: (error) => {
                console.log('error')
                console.log(error)
            }
        })
    }
}

export default MyNotes
import $ from 'jquery'

class MyNotes {

    constructor() {
        this.events();
    }

    events() {
        $('.delete-note').on('click', this.deleteNote)
        $('.edit-note').on('click', this.editNote)
    }

    // Methods
    deleteNote(e) {
        let note = $(e.target).parents('li')

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/' + note.data('id'),
            type: 'DELETE',
            success: (response) => {
                note.slideUp()
            },
            error: (error) => {
                console.log('error')
                console.log(error)
            }
        })
    }

    editNote(e) {
        let note = $(e.target).parents('li')
        note.find('.note-title-field, .note-body-field')
            .removeAttr('readonly')
            .addClass('note-active-field')

        note.find('.update-note').addClass('update-note--visible')
    }
}

export default MyNotes
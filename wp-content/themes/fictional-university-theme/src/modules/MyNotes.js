import $ from 'jquery'

class MyNotes {

    constructor() {
        this.events();
    }

    events() {
        $('#my-notes').on('click', '.delete-note', this.deleteNote.bind(this))
        $('#my-notes').on('click', '.edit-note', this.editNote.bind(this))
        $('#my-notes').on('click', '.update-note', this.updateNote.bind(this))
        $('.submit-note').on('click', this.createNote.bind(this))
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
                if(response.userNoteCount < 5) {
                    $('.note-limit-message').removeClass('active')
                }
            },
            error: (error) => {
                console.log('error')
                console.log(error)
            }
        })
    }

    editNote(e) {
        let note = $(e.target).parents('li')
        if(note.data('state') === 'editable') {
            this.makeNoteReadOnly(note)
        } else {
            this.makeNoteEditable(note)
        }
    }

    makeNoteEditable(note) {
        note.find('.edit-note').html('<i class="fa fa-times" aria-hidden="true"></i> Cancel')

        note.find('.note-title-field, .note-body-field')
            .removeAttr('readonly')
            .addClass('note-active-field')

        note.find('.update-note').addClass('update-note--visible')
        note.data('state', 'editable')
    }

    makeNoteReadOnly(note) {
        note.find('.edit-note').html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit')

        note.find('.note-title-field, .note-body-field')
            .attr('readonly', 'readonly')
            .removeClass('note-active-field')

        note.find('.update-note').removeClass('update-note--visible')
        note.data('state', 'cancel')
    }

    updateNote(e) {
        let note = $(e.target).parents('li'),
            data = {
                title: note.find('.note-title-field').val(),
                content: note.find('.note-body-field').val()
            }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/' + note.data('id'),
            type: 'POST',
            data: data,
            success: (response) => {
                this.makeNoteReadOnly(note)
            },
            error: (error) => {
                console.log('error')
                console.log(error)
            }
        })
    }

    createNote(e) {
        let data = {
                title: $('.new-note-title').val(),
                content: $('.new-note-body').val(),
                status: 'publish'   // or default is 'draft', 'publish' the data will be published immediately, 'private' visible only for the current user
                                    // in this case the status value is overwritten in functions.php to be private
            }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce)
            },
            url: universityData.root_url + '/wp-json/wp/v2/note/',
            type: 'POST',
            data: data,
            success: (response) => {
                $('.new-note-title,.new-note-body').val('')
                $(`
                    <li data-id="${response.id}">
                        <input readonly class="note-title-field" type="text" value="${response.title.raw}">
                        <span class="edit-note">
                            <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                        </span>
                        <span class="delete-note">
                            <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                        </span>
                        <textarea readonly class="note-body-field" name="" id="">${response.content.raw}</textarea>
                        <span class="update-note btn btn--blue btn--small">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i> Save
                        </span>
                    </li>
                `).prependTo('#my-notes').hide().slideDown()
            },
            error: (error) => {
                if(error.responseText === 'No more notes allowed to write.') {
                    $('.note-limit-message').addClass('active')
                }
                console.log('error')
                console.log(error)
            }
        })
    }
}

export default MyNotes
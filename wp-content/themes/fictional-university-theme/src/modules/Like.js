import $ from 'jquery'

class Like {

    constructor() {
        this.events()
    }

    events() {
        $('.like-box').on('click', this.clickDispatcher.bind(this))
    }

    // methods
    clickDispatcher(e) {
        let target = $('.like-box') //$(e.currentTarget)

        // target.data() is not working without page refresh
        if(target.attr('data-exists') == 'yes') {
            this.deleteLike(target)
        } else {
            this.createLike(target)
            console.log(target.data('exists'))
        }
    }

    createLike(target) {
        $.ajax({
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            headers: {
                'X-WP-Nonce': universityData.nonce
            },
            type: 'POST',
            data: {
                professorId: target.data('professor-id')
            },
            success: (response) => {
                if(!response.id) {
                    console.error('Error!')
                    console.log(response)

                    return
                }

                target.attr('data-exists', 'yes')
                target.attr('data-like-id', response.id)
                target.find('.like-count').html(response.count)
                console.log(response)
            },
            error: (error) => {
                console.log(error)
            }
        })
    }

    deleteLike(target) {
        $.ajax({
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            headers: {
                'X-WP-Nonce': universityData.nonce
            },
            type: 'DELETE',
            data: {
                likeId: target.data('like-id')
            },
            success: (response) => {
                target.attr('data-exists', 'no')
                target.attr('data-like-id', '')
                target.find('.like-count').html(response.count)

                console.log(response)
            },
            error: (error) => {
                console.log(error)
            }
        })
    }
}

export default Like

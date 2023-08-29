import shopHelper from '../helper/index.js';

class ShopReview {

    constructor() {
        this.ajax = null;
    }

    deleteAttachedFile(name, reviewId) {
        if (name && reviewId) {
            var ajaxUrl = '/administrator/index.php?option=com_jshopping&controller=reviews&task=ajaxDeleteAttachedFile&fileName=' + name + '&id=' + reviewId;

            fetch(ajaxUrl)
            .then(response => response.json())
            .then(json => {
                if (json.isDeleted) {
                    var file = document.querySelector('[data-file-id="' + name + '"]');

                    if (file) {
                        file.remove();
                    }
                }

                if (json.messages) {
                    var messages = json.messages;
                    var msgType = json.isDeleted ? 'message' : 'error';

                    Joomla.renderMessages({
                        [msgType]: [
                            messages['0']
                        ]
                    });
                }
            });
        }
    }
}

export default new ShopReview();
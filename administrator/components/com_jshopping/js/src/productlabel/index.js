class ShopProductLabel {

    delete(id, lang) {
        let url = `index.php?option=com_jshopping&controller=productlabels&task=delete_foto&id=${id}&lang=${lang}`;

        fetch(url)
        .then(response => response.text())
        .then(() => {
            let imageBlockEl = document.querySelector(`#image_block_${lang}`);

            if (imageBlockEl) {
                imageBlockEl.style.display = 'none';
            }
        });
    }

}

export default new ShopProductLabel();
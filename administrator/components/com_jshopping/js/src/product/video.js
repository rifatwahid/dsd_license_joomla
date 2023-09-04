class ShopProductVideo {

    updateAll() {
        let inputInsertCodeElms = document.querySelectorAll(`table.admintable input[name^='product_insert_code_']`);

        if (inputInsertCodeElms) {
            inputInsertCodeElms.forEach(item => {
                this.changeVideo(item);
            });
        }
    }

    changeVideo(obj) {
        let isChecked = obj.checked;
        let td = obj.closest('td:first');
        let productVideoEl = td.querySelector("input[name^='product_video_']");
        let productVideoCodeEl = td.querySelector("textarea[name^='product_video_code_']");
    
        if (isChecked) {
            productVideoEl.value = '';
            productVideoEl.style.display = 'none';

            productVideoCodeEl.style.display = 'block';
        } else {
            productVideoCodeEl.value = '';
            productVideoCodeEl.style.display = 'none';

            productVideoEl.style.display = 'block';
        }
    }

    delete(id) {
        let url = 'index.php?option=com_jshopping&controller=products&task=delete_video&id=' + id;

        fetch(url)
        .then(response => response.text())
        .then(() => {
            document.querySelector(`#video_product_${id}`).style.display = 'none';
        });
    }

}

export default new ShopProductVideo();
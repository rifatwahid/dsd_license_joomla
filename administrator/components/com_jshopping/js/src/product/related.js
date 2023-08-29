class ShopProductRelated {

    constructor() {
        this.lang = '';
    }

    add(id) {
        let relatedListEl = document.querySelector('#list_related');
        let name = document.querySelector(`#serched_product_${id} .name`).innerHTML;
        let img = document.querySelector(`#serched_product_${id} .image`).innerHTML;

        let html = `
            <div class="block_related" id="related_product_${id}">
                <div class="block_related_inner">
                    <div class="name">${name}</div>
                    <div class="image">${img}</div>
                    <div style="padding-top:5px;">
                        <input type="button" class="btn btn-danger btn-small" value="${this.lang}" onclick="shopProductRelated.delete(${id})">
                    </div>
                    <input type="hidden" name="related_products[]" value="${id}"/>
                </div>
            </div>
        `;

        if (relatedListEl) {
            relatedListEl.insertAdjacentHTML('beforeend', html);
        }
    }

    delete(id) {
        let relatedProductEl = document.querySelector(`#related_product_${id}`);

        if (relatedProductEl) {
            relatedProductEl.remove();
        }
    }

    search(start, no_id) {
        let text = document.querySelector('#related_search').value;
        let url = `index.php?option=com_jshopping&controller=products&task=search_related&start=${start}&no_id=${no_id}&text=${encodeURIComponent(text)}&ajax=1`;

        fetch(url)
        .then(response => response.text())
        .then(html => {
            document.querySelector('#list_for_select_related').innerHTML = html;
        });
    }

    setLang(lang) {
        this.lang = lang;
    }

}

export default new ShopProductRelated();
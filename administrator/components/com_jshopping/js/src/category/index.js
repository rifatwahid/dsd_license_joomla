class ShopCategory {

    changeOrder() {
        let parentCategoryEl = document.querySelector('#category_parent_id');

        if (parentCategoryEl) {
            let id = parentCategoryEl.value;
            let url = `index.php?option=com_jshopping&controller=categories&task=sorting_cats_html&catid=${id}&ajax=1`;

            fetch(url, {
                method: 'GET'
            }).then((response) => {
                return response.text();
            }).then((data) => {
                let orderingEl = document.querySelector('#ordering');

                if (orderingEl) {
                    orderingEl.innerHTML = data;
                }
            });
        }
    }

    toggle() {
        let categoryGroupEl = document.querySelector('input[name=allcats]:checked');

        if (categoryGroupEl) {
            let listOfCategoriesEl = document.querySelector('#tr_categorys');

            if (listOfCategoriesEl) {
                let display = (+categoryGroupEl.value) ? 'none': 'inherit';
                listOfCategoriesEl.style.display = display;
            }
        }
    }

}

export default new ShopCategory();
class ShopProductImage {

    constructor() {
        this.ajax = null;
    }

    productRequest(position, url, filter) {
        let data = {
            position,
            filter
        };


        let productImageEl = document.querySelector('#product_images');
        productImageEl.innerHTML = '';
        document.querySelector('.sbox-content-string').insertAdjacentHTML('beforeend', '<div id="product_images-overlay"></div>');

        fetch(url, {
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector('#product_images-overlay').remove();
            productImageEl.innerHTML = html;
            productImageEl.style.display = 'fade';
        });
    }

    setImageFromFolder(position, filename) {
        document.querySelector(`input[name='product_folder_image_${position}']`).value = filename;
        document.querySelector('#sbox-overlay').click();
    }

    squeezeBoxInit(widht, height) {
        if (!widht) widht = 640;
        if (!height) height = 480;

        SqueezeBox.initialize();
        SqueezeBox.setOptions({
            size: {
                x: widht,
                y: height
            }
        }).setContent('string', '');
        SqueezeBox.applyContent(`<div id="product_images" style="display: none; height: ${height} px; overflow: scroll;"></div>`);
        document.querySelector('.sbox-content-string').insertAdjacentHTML('beforeend', '<div id="product_images-overlay"></div>');
    }

}

export default new ShopProductImage();
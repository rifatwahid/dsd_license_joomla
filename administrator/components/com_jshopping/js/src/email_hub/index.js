class ShopEmailHub {

    constructor() {
        this.id = 0;
    }

    changeWidth(width) {
        document.querySelector('#preview').style.width = parseInt(width.value) + 'px';
    }

    widthPlus(width) {
        let template_width = document.querySelector('#template_width').value;
        document.querySelector('#template_width').value = parseInt(template_width) + parseInt(width);
        document.querySelector('#preview').style.width = template_width + 'px';
    }

    changePadding(padding) {
        document.querySelector('#preview').style.padding = parseInt(padding.value) + 'px';
    }

    paddingPlus(padding) {
        let template_padding = document.querySelector('#template_padding').value;

        document.querySelector('#template_padding').value = parseInt(template_padding) + parseInt(padding);
        document.querySelector('#preview').style.padding = template_padding + 'px';
    }

    plusRow(width) {
        document.querySelector('#blocks').value = parseInt(document.querySelector('#blocks').value) + parseInt(width);
    }

    addBlock(blocks, block_height) {
        this.id++;

        let block = `<div class='row_view' style='height: ${block_height} px' id='block_id_${this.id}' onClick='select_row(this)'>`;

        for (let i = 1; i <= blocks; i++) {
            block = block + `<div  class='block_item block_view' style='width: ${(100/blocks)} %; height: ${block_height} px' id='block_id_${this.id}X${i}' onClick='shopEmailHub.selectBlock(this)'>#block</div>`;
        }

        block += "</div>";
        block = block + "</div>";
        
        document.querySelector('#preview_padding').innerHTML += block;
    }

    selectBlock(block) {
        let id = block.id;
        let selected_row = document.querySelector('#selected_row').value;
        let selected_block = document.querySelector('#selected_block').value;

        id = id.substr(0, id.indexOf('X'));

        if (selected_row != "") document.querySelector(selected_row).classList.remove('selected_row');
        if (selected_block != "") document.querySelector(selected_block).classList.remove('selected_block');

        document.querySelector('#selected_row').value = id;
        document.querySelector('#selected_block').value = block.id;

        document.querySelector(id).classList.add('selected_row');
        document.querySelector(block.id).classList.add('selected_block');
    }

}

export default new ShopEmailHub();
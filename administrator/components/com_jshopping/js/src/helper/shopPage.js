class ShopPage {

    constructor() {
        this.executeAfterDomReadyStack = [];
    }

    executeAfterDomReady() {
        var stack = this.executeAfterDomReadyStack;

        if (stack) {
            document.addEventListener('DOMContentLoaded', function(event) {
                stack.forEach(function(element) {
                    if (element instanceof Function) {
                        element();
                    }
                })
            });
        }
    }

    addToStackExecuteAfterDomReady(callBack) {
        if (callBack) {
            this.executeAfterDomReadyStack.push(callBack);
        }
    }
}

export default new ShopPage();
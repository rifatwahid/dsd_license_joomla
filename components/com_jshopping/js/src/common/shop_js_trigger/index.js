class ShopJsTrigger {
    constructor() {
		this.list = []
	}

	addTrigger(class_name, method_name, callback) {
		if(typeof this.list[class_name] == "undefined") {
			this.list[class_name] = [];
		}

		this.list[class_name].push(callback);
	}

	trigger(class_name, method_name, params) {
		if(this.list.length > 0){
			for(var i=0; i<this.list[class_name].length; i++) {
				this.list[class_name][i](params);
			}
		}
	}
}

export default new ShopJsTrigger();
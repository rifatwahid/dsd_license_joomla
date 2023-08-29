var app = new Vue({
    el: '#app',
    data() {
        return {
            rules: [
            ],

            query: {
			},
			
			conditions_price: '',
			conditions_package_price: '',
			conditions_order: '',
			conditions_edit: ''
        };
    },
    components: { VueQueryBuilder: window.VueQueryBuilder },
    methods: {
        greet: function (event) {           
            var data = this.$refs.query.query;
			this.conditions_edit = JSON.stringify(data, null, 2);
        },
		strdecode: function (text) {
			var entities = [
				['amp', '&'],
				['apos', '\''],
				['#x27', '\''],
				['#x2F', '/'],
				['#39', '\''],
				['#47', '/'],
				['lt', '<'],
				['gt', '>'],
				['nbsp', ' '],
				['quot', '"']
			];

			for (var i = 0, max = entities.length; i < max; ++i) 
				text = text.replace(new RegExp('&'+entities[i][0]+';', 'g'), entities[i][1]);

			return text;
		}
    },
	watch: {
		query: function (val) {
		    let c_edit = JSON.stringify(this.$refs.query.query, null, 2);
		    this.conditions_edit = c_edit;
			
		},
	},
	beforeMount: function () {
		var tmp_this = this;
		var data = {};
		let conditionIdEl = document.querySelector('#condition_id');
		
		if (conditionIdEl) {
			data["condition_id"] = conditionIdEl.value;

			if (data["condition_id"]) {
				let url = 'index.php?option=com_jshopping&controller=conditions&task=getConditionData&condition_id='+conditionIdEl.value;

				fetch(url, {
					method: 'POST',
					body: JSON.stringify(data)
				})
				.then(response => response.text())
				.then(res => {
					let r = JSON.parse(tmp_this.strdecode(res));
					tmp_this.$refs.query.query = r;
					tmp_this.conditions_edit = tmp_this.strdecode(res);
				})
			}
		}
	}
	
});
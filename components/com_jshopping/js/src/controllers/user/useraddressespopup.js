class ShopUserAddressesPopup {

    constructor() {
        this.userAddresses = null;
        this.addressTypeTohandler = null;

        this.selectedBillingAddressId = null;
        this.selectedShippingAddressId = null;
        this.afterUpdateAddressTriggers = [];
    }

    setUserAddresses(addresses) {
        this.userAddresses = addresses;

        if (addresses && addresses[0] && addresses[0]['address_id']) {
            if (!this.selectedBillingAddressId) {
                this.selectedBillingAddressId = addresses[0]['address_id'];
            }

            if (!this.selectedShippingAddressId) {
                this.selectedShippingAddressId = addresses[0]['address_id'];
            }
        }
    }

    setAddressTypeToHandler(addressType) {
        if (addressType) {
            this.addressTypeTohandler = addressType;
        }
    }

    runAddressHandler(event) {
		var additionalData = {};
        var fullMethodNameOfClickedEl = '_' + this.addressTypeTohandler;
        var fullMethodNameOfNonClickedEl = (fullMethodNameOfClickedEl == '_billing') ? '_shipping' : '_billing';
        var idOfNonClickedEl = (fullMethodNameOfNonClickedEl == '_billing') ? this.selectedBillingAddressId : this.selectedShippingAddressId;


        if (fullMethodNameOfClickedEl in this) {
            var isUpdateInfoAboutClickedEl = this[fullMethodNameOfClickedEl](event);
        }

        if (fullMethodNameOfNonClickedEl in this) {
            this[fullMethodNameOfNonClickedEl]({
                dataset: {
                    addressId: idOfNonClickedEl
                }
            });
        }

        if (isUpdateInfoAboutClickedEl) {
            if (this.afterUpdateAddressTriggers.length >= 1) {
                this.afterUpdateAddressTriggers.forEach(function (funcElem) {
                    funcElem(event, additionalData);
                });
            }

            shopQuickCheckout._refreshData(null, additionalData);
            this.close();
        }
    }

    close() {
        if(document.querySelector('#userAddressesPopup .close')) document.querySelector('#userAddressesPopup .close') .click();
		if(document.querySelector('#userAddressesPopup')){
			document.querySelector('#userAddressesPopup').classList.remove('show');
			document.querySelector('#userAddressesPopup').style.display='none';
		}
		if(document.querySelector('.modal-backdrop')){
			document.querySelector('.modal-backdrop').classList.remove('show');
			document.querySelector('.modal-backdrop').style.display='none';
		}
		document.querySelector('body').classList.remove('modal-open');
		document.querySelector('body').style.overflow='';
	}

    _billing(event) {
        return this._updatePageAddress(event, 'billingAddress');
    }

    _shipping(event) {
        return this._updatePageAddress(event, 'shippingAddress');
    }

    _updatePageAddress(event, addressSelector) {
        if (event.dataset.addressId) {
            var selectedAddressId = event.dataset.addressId;
            var addressBlock = document.querySelector(`form[name="quickCheckout"] .${addressSelector}`);
            var addressData = this._getAddressInfo(selectedAddressId);

            if (addressData) {
				let firmaEl = addressBlock.querySelector(`.${addressSelector}__firma`);
                let nameEl = addressBlock.querySelector(`.${addressSelector}__name`);
                let streetEl = addressBlock.querySelector(`.${addressSelector}__street`);
                let streetNrEl = addressBlock.querySelector(`.${addressSelector}__street_nr`);
                let zipEl = addressBlock.querySelector(`.${addressSelector}__zip`);
                let cityEl = addressBlock.querySelector(`.${addressSelector}__city`);
                let countryEl = addressBlock.querySelector(`.${addressSelector}__country`);

				if (firmaEl) {
                    firmaEl.innerHTML = addressData.firma_name;
                }
				
                if (nameEl) {
                    nameEl.innerHTML = addressData.f_name + ' ' + addressData.l_name;
                }

                if (streetEl) {
                    streetEl.innerHTML = addressData.street;
                }

                if (streetNrEl) {
                    streetNrEl.innerHTML = addressData.street_nr;
                }

                if (zipEl) {
                    zipEl.innerHTML = addressData.zip;
                }

                if (cityEl) {
                    cityEl.innerHTML = addressData.city;
                }

                if (countryEl) {
                    countryEl.innerHTML = addressData.country;
                }

                addressBlock.querySelector(`input[name=${addressSelector}_id]`).value = addressData.address_id;
                var variableNameWithAddressId = 'selected' + shopHelper.capitalizeFirstLetter(addressSelector) + 'Id';
                this[variableNameWithAddressId] = event.dataset.addressId;
            }
        }

        return true;
    } 

    _getAddressInfo(addressId) {
        if (this.userAddresses && addressId) {
            for (var i = 0; i < this.userAddresses.length; i++) {
                var loopElement = this.userAddresses[i];

                if (loopElement.address_id == addressId) {
                    return loopElement;
                }
            }
        }
    }
	
	setShippingAddresses(id) {
		if (!this.selectedShippingAddressId && id) {
			this.selectedShippingAddressId = id;
		}
	}
}

export default new ShopUserAddressesPopup();
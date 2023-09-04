function showJ4submenu(vName='other'){
var wrapper2 = document.getElementById('wrapper');
if (wrapper2 !=  null) {
var elems = document.querySelectorAll(".mm-active");
[].forEach.call(elems, function(el) {
    el.classList.remove("mm-active");
});
var allLinks2 = wrapper2.querySelectorAll('a.no-dropdown, a.collapse-arrow, .menu-dashboard > a');
var currentUrl2 = 'index.php?option=com_jshopping&controller='+vName;
allLinks2.forEach(link => {		
	if (link.href.indexOf(currentUrl2) != -1) {
	  link.setAttribute('aria-current', 'page');
	  link.classList.add('mm-active');
	  if (!link.parentNode.classList.contains('parent')) {
		const firstLevel = link.closest('.collapse-level-1');
		const secondLevel = link.closest('.collapse-level-2');
		if (firstLevel) firstLevel.parentNode.classList.add('mm-active');
		if (firstLevel) firstLevel.classList.add('mm-show');
		if (secondLevel) secondLevel.parentNode.classList.add('mm-active');
		if (secondLevel) secondLevel.classList.add('mm-show');
	  }
	}
  })
};

}
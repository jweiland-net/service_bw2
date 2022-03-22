[...document.querySelectorAll('[data-service-bw-filter]')].forEach(element => {
  element.addEventListener('change', event => {
    event.preventDefault();
    [...document.querySelectorAll(`${element.dataset['serviceBwList']} li`)].forEach(listItem => {
      if (element.dataset['serviceBwFilter'] in listItem.dataset || element.checked === false) {
        if (!('searched' in listItem.dataset)) {
          listItem.style.display = '';
        }
        delete listItem.dataset.filtered;
      } else {
        listItem.style.display = 'none';
        listItem.dataset.filtered = '1';
      }
      hideEmptyCategories(listItem);
    });
  });
});

[...document.querySelectorAll('[data-service-bw-search]')].forEach(element => {
  element.addEventListener('keyup', event => {
    [...document.querySelectorAll(`${element.dataset['serviceBwList']} li`)].forEach(listItem => {
      const regExp = new RegExp(`^(.*?)(${element.value})(.*)$`, 'img');
      if (listItem.textContent.match(regExp) || element.value === '') {
        if (!('filtered' in listItem.dataset)) {
          listItem.style.display = '';
        }
        delete listItem.dataset.searched;
      } else {
        listItem.style.display = 'none';
        listItem.dataset.searched = '1';
      }
      hideEmptyCategories(listItem);
    });
  });
});

const hideEmptyCategories = listItem => {
  for (const otherListItem of listItem.parentElement.childNodes) {
    if (otherListItem.style.display !== 'none') {
      listItem.parentElement.style.display = '';
      listItem.parentElement.previousElementSibling.style.display = '';
      return;
    }
  }
  listItem.parentElement.style.display = 'none';
  listItem.parentElement.previousElementSibling.style.display = 'none';
};

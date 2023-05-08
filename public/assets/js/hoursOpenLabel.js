const checkboxOpen = document.querySelectorAll('.form-input>input[type=checkbox]');

setLabelBg();

checkboxOpen.forEach(element => {
  element.onchange = () => {
    if(element.checked) {
      element.parentElement.children[0].classList.remove('notchecked');
      element.parentElement.children[0].classList.add('checked');
    } else {
      element.parentElement.children[0].classList.remove('checked');
      element.parentElement.children[0].classList.add('notchecked');
    }
    console.log(element.parentElement.children[0]);
  }
});

function setLabelBg() {
  checkboxOpen.forEach(element => {
    if(element.checked) {
      element.parentElement.children[0].classList.add('checked');
      element.parentElement.children[0].classList.add('qa-button');
      element.parentElement.children[0].classList.add('qa-button-small');
    } else {
      element.parentElement.children[0].classList.add('notchecked');
      element.parentElement.children[0].classList.add('qa-button');
      element.parentElement.children[0].classList.add('qa-button-small');
    }
  });
}
const form = document.getElementsByName('menu_form');
console.log(form);
form.onsubmit = (e) => {
  e.preventDefault();

  console.log(form);
}
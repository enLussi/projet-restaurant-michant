const collapsers = document.querySelectorAll('.collapser');
// const categoryCollapser = document.querySelectorAll('.category-content');


console.log(collapsers);
collapsers.forEach(collapser => {
  collapser.onclick = () => {
    let input = collapser.children[1];
    if(input.checked) {
      input.checked = false;
      document.getElementById('courses_'+input.value).classList.add('hidden');
      document.getElementById('courses_'+input.value).classList.remove('shown');
    } else {
      input.checked = true;
      document.getElementById('courses_'+input.value).classList.add('shown');
      document.getElementById('courses_'+input.value).classList.remove('hidden');
    }
  }
})
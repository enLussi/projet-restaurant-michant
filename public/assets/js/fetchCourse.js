const setmenuField = document.getElementById('menu_form_setmenu');
const courseSelect = document.getElementById('courseSelect');
// const coursesField = document.getElementById('menu_form_courses');


setmenuField.onchange = () => {

  // On récupère la valeur (id) de l'élément 
  // sélectionné dans le formulaire
  const setmenuId = setmenuField.value;


  // On crée une url avec la valeur 
  const url = `/api/fetch/courses?setmenu=${setmenuId}`;

  // On fait un requête en Get pour obtenir les plats
  // correspondant à la Formule
  fetch(url)
    .then(response => response.json())
    .then(coursesCategories => {
      // On mets à jour les options du champ de 
      // sélection des plats
      courseSelect.innerHTML = "";

      Object.entries(coursesCategories).forEach(category => {

        let count = 0;

        const container = document.createElement('div');

        const coursesField = document.createElement('select');

        coursesField.setAttribute('multiple', true);
        coursesField.setAttribute('required', true);
        coursesField.id = 'menu_form_courses'+count;

        // console.log(category);
  
        category[1].forEach(course => {
          const option = document.createElement('option');

          console.log(course);
          option.value = course.id;
          option.textContent = course.title;
          coursesField.appendChild(option);
        });

        container.appendChild(coursesField);

        count += 1;

        courseSelect.appendChild(container);
      });


    })  

}

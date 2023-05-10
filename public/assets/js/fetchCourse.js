const setmenuField = document.getElementById('menu_form_setmenu');
const courseSelect = document.getElementById('courseSelect');

fetchCourses();

setmenuField.onchange = () => {

 fetchCourses();

}

async function fetchCourses () {
  // On récupère la valeur (id) de l'élément 
  // sélectionné dans le formulaire
  const setmenuId = setmenuField.value;

  const path = window.location.pathname;
  let url = `/api/fetch/courses?setmenu=${setmenuId}`;
  console.log(path);
  if(path.split('/')[3] == 'edition') {
    url += `&id=${path.split('/')[4]}`
  }

  // On crée une url avec la valeur 
  

  // On fait un requête en Get pour obtenir les plats
  // correspondant à la Formule
  fetch(url)
    .then(response => response.json())
    .then(coursesCategories => {
      // On mets à jour les options du champ de 
      // sélection des plats

      // On vide la div qui va accueillir les
      // nouveaux select 
      courseSelect.innerHTML = "";
      console.log(coursesCategories);
      let count = 0;
      // On boucle sur les données envoyé par le serveur,
      // Object.entries permet de boucler comme sur un tableau
      // mais pour des objets
      Object.entries(coursesCategories).forEach(category => {
        // Une div pour accueillir chaque select
        // et création d'un select avec les attributs
        // multiple, required et un id unique
        const container = document.createElement('div');
        const coursesField = document.createElement('select');

        coursesField.setAttribute('multiple', true);
        coursesField.setAttribute('required', true);
        coursesField.id = 'menu_form_courses'+count;
        coursesField.name = 'menu_form_courses'+count;

        placeholder = document.createElement('option');

        
        placeholder.value = "";
        placeholder.textContent = Object.keys(category[1])[0];
        coursesField.appendChild(placeholder);

        // On boucle sur l'élément d'indice 1 qui correspond à 
        // l'objet Plat.
        Object.entries(category[1]).forEach(courses => {
          // On crée l'option du select pour chaque Plat
          // dans la Catégorie
          
          courses[1].forEach(course => {

            
            const option = document.createElement('option');

            option.value = course.id;
            option.textContent = course.title;
            option.selected = course.selected;

            console.log(course.selected);
  
            // On ajoute les éléments dans les divs respectives
            coursesField.appendChild(option);
          })

        });

        count += 1;

        container.appendChild(coursesField);

        courseSelect.appendChild(container);

        new TomSelect("#"+coursesField.id,{
          maxItems: 10
        });
      });


    })  
}
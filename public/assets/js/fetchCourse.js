const setmenuField = document.getElementById('menu_form_setmenu');
const courseSelect = document.getElementById('courseSelect');


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

      // On vide la div qui va accueillir les
      // nouveaux select 
      courseSelect.innerHTML = "";

      // On boucle sur les données envoyé par le serveur,
      // Object.entries permet de boucler comme sur un tableau
      // mais pour des objets
      Object.entries(coursesCategories).forEach(category => {

        let count = 0;

        // Une div pour accueillir chaque select
        // et création d'un select avec les attributs
        // multiple, required et un id unique
        const container = document.createElement('div');
        const coursesField = document.createElement('select');

        coursesField.setAttribute('multiple', true);
        coursesField.setAttribute('required', true);
        coursesField.id = 'menu_form_courses'+count;

        // On boucle sur l'élément d'indice 1 qui correspond à 
        // l'objet Plat.
        category[1].forEach(course => {

          // On crée l'option du select pour chaque Plat
          // dans la Catégorie
          const option = document.createElement('option');

          option.value = course.id;
          option.textContent = course.title;

          // On ajoute les éléments dans les divs respectives
          coursesField.appendChild(option);
        });

        container.appendChild(coursesField);

        count += 1;

        courseSelect.appendChild(container);
      });


    })  

}

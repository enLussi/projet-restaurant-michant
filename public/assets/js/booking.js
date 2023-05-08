const calendar = document.getElementById('booking_form_booking_date');
const book_list = document.getElementById('booking_hours');

calendar.onchange = () => {

  // On récupère la valeur du champs de sélection
  // de date en le convertissant en timestamp
  const timestamp = Date.parse(calendar.value) / 1000;

  // On défini l'url 
  const url = `/api/fetch/booking?timestamp=${timestamp}`;

  // On fait la requête pour récupérer les horaires
  // de réservation.
  fetch(url)
    .then(response => response.json())
    .then(books => {
      
      // On vide la div qui va accueillir les
      // choix de créneau de réservation 
      book_list.innerHTML = "";

      Object.entries(books).forEach((periods, index) => {

        // On crée la div pour les créneaux de réservation de midi ou soir
        const fieldset = document.createElement('fieldset');
        fieldset.classList.add('hours-choice')
        fieldset.innerHTML += "<div class='legend-wrapper'><legend>"+ (periods[0] == 'lunch' ? "Midi" : "Dîner") +"</legend><div>";

        // On boucle sur tous les choix de créneaux possible pour
        // créer les champs radios
        periods[1].forEach((book_slot, index) => {
          
          const radio_container = document.createElement('div');
          radio_container.classList.add('radio-container');

          const radio_book = document.createElement('input');
          radio_book.id = "booking_form_book_radio_"+periods[0]+index;
          radio_book.type = "radio";
          radio_book.name = "radio_books";
          radio_book.value = book_slot.book;
          radio_book.required = "required";
          radio_book.classList.add('qa-choice')

          const label_book = document.createElement('label');
          label_book.textContent = book_slot.book;
          label_book.htmlFor = radio_book.id;

          if(book_slot.took) {
            console.log(book_slot);
            radio_book.disabled = true;
            label_book.classList.add('disabled');
          }


          // On ajoute le couple label radio au fieldset
          radio_container.appendChild(radio_book);
          radio_container.appendChild(label_book);

          fieldset.appendChild(radio_container);

        });

        // On ajoute le fieldset à la div correspondant au midi ou dinner
        // et on ajoute cette div à la vue
        book_list.appendChild(fieldset);
      });

    })

}
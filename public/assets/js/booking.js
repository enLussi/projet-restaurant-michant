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
        const period = document.createElement('div');
        const fieldset = document.createElement('fieldset');
        fieldset.innerHTML += "<legend>"+ periods[0] +"</legend>";

        // On boucle sur tous les choix de créneaux possible pour
        // créer les champs radios
        periods[1].forEach((book_slot, index) => {

          const radio_book = document.createElement('input');
          radio_book.id = "booking_form_book_radio"+index;
          radio_book.type = "radio";
          radio_book.name = "radio_books";
          radio_book.value = book_slot.book;
          radio_book.required = "required";

          const label_book = document.createElement('label');
          label_book.textContent = book_slot.book;
          label_book.htmlFor = radio_book.id;


          // On ajoute le couple label radio au fieldset
          fieldset.appendChild(label_book);
          fieldset.appendChild(radio_book);
        });

        // On ajoute le fieldset à la div correspondant au midi ou dinner
        // et on ajoute cette div à la vue
        period.appendChild(fieldset);
        book_list.appendChild(period);
      });

    })

}
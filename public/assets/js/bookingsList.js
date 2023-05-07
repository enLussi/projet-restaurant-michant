const booksDate = document.getElementById('books_date');
const resultsContainer = document.getElementById('books-results');

const now = new Date();
const minDate = now.toISOString().split("T")[0];

booksDate.onchange = () => {

  const url = `/api/fetch/bookings?date=${booksDate.value}`;

  fetch(url)
  .then(response => response.json())
  .then(bookings => {

    resultsContainer.innerHTML = "";

    bookings.forEach(book => {
      result = document.createElement('div');

      list_allergen = "";
      book.allergens.forEach(allergen => { 
        list_allergen += `${allergen}, `
      });

      console.log(book.message);

      result.innerHTML = 
      `
      <div class="header-booking">
        <div class="customer-coords">
          <p>${book.customerFirstname}  ${book.customerLastname}</p>
          <p>${book.customerPhone}</p>
          <p>${book.customerEmail}</p>
        </div>
        <div class="customer-booking">
          <p class="font-large">Réservation pour ${book.covers} personne${book.covers > 1 ? 's' : ''}</p>
        </div>
        <div class="customer-date">
          <p>${formatDate(new Date(Date.parse(book.date.date)))}</p>
        </div>
      </div>
      <div class="booking-allergens">
        ${ list_allergen.length > 0 ? '<p>Allergènes précisés :'+list_allergen+'</p>' : '<p>Aucun Allergènes mentionnés</p>' }
      </div>
      <div class="booking-message">
        ${ book.message === null ? '<p>Aucun Message</p>' : '<p>Message :'+ book.message +'</p>' }
      </div>
      `;

      resultsContainer.appendChild(result);
    });

    
  });

  // if(booksDate.value < minDate) {
  //   booksDate.value = minDate;
  // }

}

function formatDate(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');

  return `${day}/${month}/${year} ${hours}:${minutes}`;
}
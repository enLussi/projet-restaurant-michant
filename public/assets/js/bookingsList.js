const booksDate = document.getElementById('books_date');
const resultsContainer = document.getElementById('results');

const now = new Date();
const minDate = now.toISOString().split("T")[0];

booksDate.onchange = () => {
  console.log(booksDate.value, minDate);

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

      result.innerHTML = 
      `<p>
        ${formatDate(new Date(Date.parse(book.date.date)))}  /  ${book.customerFirstname}  ${book.customerLastname}
      </p>
      <p>
        ${book.customerPhone} : ${book.customerEmail}
      </p>
      <p>
        Réservation pour ${book.covers} personne${book.covers > 1 ? 's' : ''}
      </p>
      <p>
        Allegènes précisés : ${list_allergen}
      </p>`;

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
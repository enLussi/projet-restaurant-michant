const booksDate = document.getElementById('books_date');

const now = new Date();
const minDate = now.toISOString().split("T")[0];

booksDate.onchange = () => {
  console.log(booksDate.value, minDate);

  const url = `/api/fetch/bookings?date=${booksDate.value}`;

  fetch(url)
  .then(response => response.json())
  .then(bookings => {
    console.log(bookings);
  });

  // if(booksDate.value < minDate) {
  //   booksDate.value = minDate;
  // }

}
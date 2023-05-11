const buttonNav = document.getElementById('navbar-collapser');

const navigation = document.getElementById('navigation');

if(buttonNav !== null){
  buttonNav.onclick = () => {

    if(navigation.classList.contains('shown')) {
      navigation.classList.remove('shown');
      navigation.classList.add('hidden');
    } else {
      navigation.classList.remove('hidden');
      navigation.classList.add('shown');
    }
    
  }
}

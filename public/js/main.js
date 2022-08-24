const mobileExpandMenuIcon = document.getElementById('mobile-expand-menu-icon');
const mobileExpandMenu = document.getElementById('mobile-expand-menu');

let expanded = false;
mobileExpandMenuIcon.addEventListener('click', (meme)=>{
  meme.preventDefault();
  if(mobileExpandMenu.classList.contains('d-block') && expanded === true){
    mobileExpandMenu.classList.remove('d-block');
    mobileExpandMenu.classList.add('d-none');
    mobileExpandMenuIcon.querySelector('.menu-open-icon').classList.remove('d-none');
    mobileExpandMenuIcon.querySelector('.menu-open-icon').classList.add('d-block');
    mobileExpandMenuIcon.querySelector('.menu-close-icon').classList.remove('d-block');
    mobileExpandMenuIcon.querySelector('.menu-close-icon').classList.add('d-none');
  }
  if(mobileExpandMenu.classList.contains('d-none') && expanded === false){
    mobileExpandMenu.classList.remove('d-none');
    mobileExpandMenu.classList.add('d-block');
    mobileExpandMenuIcon.querySelector('.menu-open-icon').classList.remove('d-block');
    mobileExpandMenuIcon.querySelector('.menu-open-icon').classList.add('d-none');
    mobileExpandMenuIcon.querySelector('.menu-close-icon').classList.remove('d-none');
    mobileExpandMenuIcon.querySelector('.menu-close-icon').classList.add('d-block');
  }

  expanded = !expanded;
});

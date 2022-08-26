const mobileExpandMenuIcon = document.getElementById("mobile-expand-menu-icon");
const mobileExpandMenu = document.getElementById("mobile-expand-menu");


/**
 * @mobile menu for all pages
*/
let expanded = false;
mobileExpandMenuIcon.addEventListener("click", (meme) => {
  meme.preventDefault();
  if (mobileExpandMenu.classList.contains("d-block") && expanded === true) {
    mobileExpandMenu.classList.remove("d-block");
    mobileExpandMenu.classList.add("d-none");
    mobileExpandMenuIcon
      .querySelector(".menu-open-icon")
      .classList.remove("d-none");
    mobileExpandMenuIcon
      .querySelector(".menu-open-icon")
      .classList.add("d-block");
    mobileExpandMenuIcon
      .querySelector(".menu-close-icon")
      .classList.remove("d-block");
    mobileExpandMenuIcon
      .querySelector(".menu-close-icon")
      .classList.add("d-none");
  }
  if (mobileExpandMenu.classList.contains("d-none") && expanded === false) {
    mobileExpandMenu.classList.remove("d-none");
    mobileExpandMenu.classList.add("d-block");
    mobileExpandMenuIcon
      .querySelector(".menu-open-icon")
      .classList.remove("d-block");
    mobileExpandMenuIcon
      .querySelector(".menu-open-icon")
      .classList.add("d-none");
    mobileExpandMenuIcon
      .querySelector(".menu-close-icon")
      .classList.remove("d-none");
    mobileExpandMenuIcon
      .querySelector(".menu-close-icon")
      .classList.add("d-block");
  }

  expanded = !expanded;
});

/**
 * @page dashboard
 * Toggle content for dashboard sidebar menu item
 */
if (window.location.pathname === "/dashboard.php") {
  const allMenuItems = document.querySelectorAll(".menu-item");
  const profileIcon = document.querySelector('[data-item="profile"]');
  const myPackIcon = document.querySelector('[data-item="my-pack"]');
  const pendingPackIcon = document.querySelector('[data-item="pending-pack"]');
  const addPackIcon = document.querySelector('[data-item="add-pack"]');

  const allContent = document.querySelectorAll(".content");
  const profileContent = document.querySelector('.profile-content');
  const myPackContent = document.querySelector('.my-pack-content');
  const pendingPackContent = document.querySelector('.pending-pack-content');
  const addPackContent = document.querySelector('.add-pack-content');

  profileIcon.addEventListener("click", (e) => elToggle(e, profileIcon, profileContent));
  myPackIcon.addEventListener("click", (e) => elToggle(e, myPackIcon, myPackContent));
  pendingPackIcon.addEventListener("click", (e) =>elToggle(e, pendingPackIcon, pendingPackContent));
  addPackIcon.addEventListener("click", (e) => elToggle(e, addPackIcon, addPackContent));

  function elToggle(e, selectedIcon, selectedContent) {
    e.preventDefault();

    allMenuItems.forEach((amie) => {
      if (amie.classList.contains("active")) {
        amie.classList.remove("active");
      }
    });
    selectedIcon.classList.add("active");


    allContent.forEach((amie) => {
      if (amie.classList.contains("d-block")) {
        amie.classList.remove("d-block");
        amie.classList.add("d-none");
      }
    });
    selectedContent.classList.remove('d-none');
    selectedContent.classList.add('d-block');
  }
}

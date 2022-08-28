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
    mobileExpandMenuIcon.querySelector(".menu-open-icon").classList.remove("d-none");
    mobileExpandMenuIcon.querySelector(".menu-open-icon").classList.add("d-block");
    mobileExpandMenuIcon.querySelector(".menu-close-icon").classList.remove("d-block");
    mobileExpandMenuIcon.querySelector(".menu-close-icon").classList.add("d-none");
  }
  if (mobileExpandMenu.classList.contains("d-none") && expanded === false) {
    mobileExpandMenu.classList.remove("d-none");
    mobileExpandMenu.classList.add("d-block");
    mobileExpandMenuIcon.querySelector(".menu-open-icon").classList.remove("d-block");
    mobileExpandMenuIcon.querySelector(".menu-open-icon").classList.add("d-none");
    mobileExpandMenuIcon.querySelector(".menu-close-icon").classList.remove("d-none");
    mobileExpandMenuIcon.querySelector(".menu-close-icon").classList.add("d-block");
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
  const profileContent = document.querySelector(".profile-content");
  const myPackContent = document.querySelector(".my-pack-content");
  const pendingPackContent = document.querySelector(".pending-pack-content");
  const addPackContent = document.querySelector(".add-pack-content");

  profileIcon.addEventListener("click", (e) => elToggle(e, profileIcon, profileContent));
  myPackIcon.addEventListener("click", (e) => elToggle(e, myPackIcon, myPackContent));
  pendingPackIcon.addEventListener("click", (e) => elToggle(e, pendingPackIcon, pendingPackContent));
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
    selectedContent.classList.remove("d-none");
    selectedContent.classList.add("d-block");
  }
}

/**
 * @page dashboard
 * Toggle content for dashboard sidebar menu item
 */
if (window.location.pathname === "/admin.php") {
  const logedinContent = document.querySelector(".section-2");
  if (logedinContent) {
    const allMenuItems = document.querySelectorAll(".menu-item");
    const firstContent = document.querySelectorAll(".content")[0];
    const firstMenuItem = allMenuItems[0];
    let pse = localStorage.getItem("pse"); //pse = previous selected element. first elemement, this will vary - set to localstorage
    let prevSelectedElement = null,
      prevSelectedContent = null;
    if (pse !== null) {
      prevSelectedContent = JSON.parse(pse).selectedContent;
      prevSelectedElement = JSON.parse(pse).selectedElement;
      const pseItem = document.querySelector(`[data-item="${prevSelectedElement}"]`);
      const pseContent = document.querySelector(`.${prevSelectedContent}`);

      // select item from local storage
      if (firstMenuItem.classList.contains("active")) {
        firstMenuItem.classList.remove("active");
      }
      if (!pseItem.classList.contains("active")) {
        pseItem.classList.add("active");
      }

      // Display content from local storage
      if (firstContent.classList.contains("d-block")) {
        firstContent.classList.remove("d-block");
        firstContent.classList.add("d-none");
      }
      if (pseContent.classList.contains("d-none")) {
        pseContent.classList.remove("d-none");
        pseContent.classList.add("d-block");
      }
    } else {
      prevSelectedElement = firstMenuItem.dataset.item;
      prevSelectedContent = `${firstMenuItem.dataset.item}-content`;
    }

    allMenuItems.forEach((ami, i) => {
      ami.addEventListener("click", (amie) => {
        amie.preventDefault();
        const clickedItem = amie.currentTarget.dataset.item;
        if (pse !== clickedItem) elToggle(clickedItem, `${clickedItem}-content`);
      });
    });

    function elToggle(selectedElement, selectedContent) {
      pse = localStorage.getItem("pse");
      if (pse) {
        prevSelectedContent = JSON.parse(pse).selectedContent;
        prevSelectedElement = JSON.parse(pse).selectedElement;
      }
      const prevItemElement = document.querySelector(`[data-item="${prevSelectedElement}"]`);
      const prevContentElement = document.querySelector(`.${prevSelectedContent}`);
      if (prevItemElement.classList.contains("active")) {
        prevItemElement.classList.remove("active");
      }
      if (prevContentElement.classList.contains("d-block")) {
        prevContentElement.classList.remove("d-block");
        prevContentElement.classList.add("d-none");
      }
      const currentItemElement = document.querySelector(`[data-item="${selectedElement}"]`);

      const pseCurrentContentElement = document.querySelector(`.${selectedContent}`);

      // Select proper menu item
      currentItemElement.classList.add("active");
      // Select proper content
      if (pseCurrentContentElement.classList.contains("d-none")) {
        pseCurrentContentElement.classList.remove("d-none");
        pseCurrentContentElement.classList.add("d-block");
      }

      localStorage.removeItem("pse");
      localStorage.setItem("pse", JSON.stringify({ selectedElement, selectedContent }));
    }
  }
}

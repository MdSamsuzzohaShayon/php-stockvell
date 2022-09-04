const mobileExpandMenuIcon = document.getElementById("mobile-expand-menu-icon");
const mobileExpandMenu = document.getElementById("mobile-expand-menu");

/**
 * @mobile menu for all pages for all pages
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
 * @click event for changing sidebar element on click
 */
function sidebarElementToggle(allMenuItems, allContent) {
  const firstContent = allContent[0];
  const firstMenuItem = allMenuItems[0];
  let pse = localStorage.getItem("pse"); //pse = previous selected element. first elemement, this will vary - set to localstorage
  let prevSelectedElement = null,
    prevSelectedContent = null;
  if (pse !== null) {
    prevSelectedContent = JSON.parse(pse).selectedContent;
    prevSelectedElement = JSON.parse(pse).selectedElement;
    const pseItem = document.querySelector(`[data-item="${prevSelectedElement}"]`);
    const pseContent = document.querySelector(`.${prevSelectedContent}`);
    if (pseItem === null || pseContent === null) {
      return localStorage.clear();
    }

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

/**
 * @page signup
 * Select mobile number
 */
if (window.location.pathname === "/signup.php" || window.location.pathname === "/signup/") {
  countryCodePrefixForPhone();
}

/**
 * @page dashboard
 * Toggle content for dashboard sidebar menu item
 */
if (window.location.pathname === "/dashboard.php" || window.location.pathname === "/dashboard/") {
  const allMenuItems = document.querySelectorAll(".menu-item");
  const allContent = document.querySelectorAll(".content");
  if (allMenuItems && allContent) sidebarElementToggle(allMenuItems, allContent);

  countryCodePrefixForPhone();

  // Work with ck editor
  ClassicEditor.create(document.querySelector("#agreement")).catch((error) => {
    console.error(error);
  });
}

/**
 * @page admin
 * Toggle content for dashboard sidebar menu item
 */
if (window.location.pathname === "/admin.php" || window.location.pathname === "/admin/") {
  const logedinContent = document.querySelector(".section-2");
  if (logedinContent) {
    const allMenuItems = document.querySelectorAll(".menu-item");
    const allContent = document.querySelectorAll(".content");
    if (allMenuItems && allContent) sidebarElementToggle(allMenuItems, allContent);
  }
}

// recover-via-email
/**
 * @page admin
 * Toggle content for email and phone recover form
 */
if (window.location.pathname === "/forget_password.php" || window.location.pathname === "/forget_password/") {
  const recoverBtnEmail = document.getElementById("recover-via-email");
  const recoverBtnPhone = document.getElementById("recover-via-phone");
  const recoverPhoneForm = document.querySelector(".phone-form");
  const recoverEmailForm = document.querySelector(".email-form");
  const allFormContent = document.querySelectorAll(".form-content");

  recoverBtnEmail.addEventListener("click", (rbee) => toggleForm(rbee, "email"));
  recoverBtnPhone.addEventListener("click", (rbee) => toggleForm(rbee, "phone"));

  function toggleForm(tfe, content) {
    tfe.preventDefault();
    allFormContent.forEach((afc) => {
      if (afc.classList.contains("d-block")) {
        afc.classList.remove("d-block");
        afc.classList.add("d-none");
      }
    });
    if (content === "email") {
      if (recoverEmailForm.classList.contains("d-none")) {
        recoverEmailForm.classList.remove("d-none");
        recoverEmailForm.classList.add("d-block");
      }
    } else if (content === "phone") {
      if (recoverPhoneForm.classList.contains("d-none")) {
        recoverPhoneForm.classList.remove("d-none");
        recoverPhoneForm.classList.add("d-block");
      }
    }
  }
}

/**
 * @extra function 1
 */
function countryCodePrefixForPhone() {
  const phonePrefix = document.getElementById("phone-prefix");
  const countryCodeInput = document.getElementById("phone-code-input");
  const phoneInput = document.getElementById("phone-hidden-input");
  const countryInput = document.getElementById("country");

  const selectedCountry = countryInput.querySelector("[selected]");

  const pattern = /\W\d+/;
  let country_code = selectedCountry.value.toString().match(pattern)[0];
  phonePrefix.textContent = country_code;
  let formattedPhone = null;
  let phoneBase = countryCodeInput.value;

  // Changing code
  countryInput.addEventListener("change", (cie) => {
    country_code = cie.currentTarget.value.toString().match(pattern)[0];
    phonePrefix.textContent = country_code;
    if (phoneBase) {
      formattedPhone = country_code.slice(-1);
      phoneInput.value = formattedPhone + phoneBase;
    }
  });

  countryCodeInput.addEventListener("change", (ccie) => {
    formattedPhone = country_code.slice(-1);
    phoneBase = ccie.currentTarget.value;
    phoneInput.value = formattedPhone + phoneBase;
  });
}

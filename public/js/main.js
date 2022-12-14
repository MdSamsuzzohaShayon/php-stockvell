const mobileExpandMenuIcon = document.getElementById("mobile-expand-menu-icon");
const mobileExpandMenu = document.getElementById("mobile-expand-menu");

const languageItems = document.querySelectorAll(".dropdown-item");

/**
 * Bootstrap coding
 */
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
if (tooltipTriggerList) {
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
}

/**
 * Change language and stay on the same page
 * ==========================================================
 */
languageItems.forEach((lie) => {
  lie.addEventListener("click", (liie) => {
    liie.preventDefault();
    let newUrl = window.location.href;
    const selectedLanguage = liie.currentTarget.dataset.lang;
    if (newUrl.includes("?")) {
      const params = new URLSearchParams(window.location.search);
      // console.log(params.get('lang'));
      if (params.get("lang")) {
        newUrl = newUrl.replace(`lang=${params.get("lang")}`, `lang=${selectedLanguage}`);
        // console.log(`lang=${params.get('lang')}`);
      } else {
        newUrl += `&lang=${selectedLanguage}`;
      }
    } else {
      newUrl += `?lang=${selectedLanguage}`;
    }
    window.location.replace(newUrl);
  });
});

/**
 * @public code
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
 * @extra function 1
 */
function countryCodePrefixForPhone(hasCountry) {
  const phoneMainHidden = document.getElementById("phone");
  const phoneRawInput = document.getElementById("phone-raw-input");
  const phonePrefixSelect = document.getElementById("phone-select");

  let country_code = "+229"; // default

  if (phonePrefixSelect) {
    if (phonePrefixSelect.value !== null || phonePrefixSelect.value !== "") country_code = phonePrefixSelect.value;
  }

  if (hasCountry) {
    const countryInput = document.getElementById("country");
    // Changing code
    if (countryInput) {
      countryInput.addEventListener("change", (cie) => {
        // cie.preventDefault();
        // country_code = cie.currentTarget.value.toString().match(pattern)[0];
        // phonePrefix.textContent = country_code;
        const targetedStr = cie.currentTarget.value.toString();
        if (targetedStr.includes(")")) {
          const pattern = new RegExp(/\+\d+/g);
          phonePrefixSelect.value = pattern.exec(targetedStr)[0];
          // console.log(pattern);
        }
        // else {
        //   const pattern = /\W\d+/;
        //   country_code = targetedStr.match(pattern)[0];
        //   phonePrefixSelect.value = country_code;
        // }

        let formatted_code = `${country_code}_${phoneRawInput.value}`;
        if (phoneRawInput.value === "") {
          formatted_code = country_code;
        }
        phoneMainHidden.value = formatted_code;
      });
    }
  }

  if (phoneRawInput) {
    phoneRawInput.addEventListener("change", (ccie) => {
      // ccie.preventDefault();
      phoneMainHidden.value = `${country_code}_${ccie.currentTarget.value}`;
      phonePrefixSelect.value = country_code;
    });
  }

  if (phonePrefixSelect) {
    phonePrefixSelect.addEventListener("change", (ppse) => {
      // ppse.preventDefault();
      const targetedStr = ppse.currentTarget.value.toString();
      if (targetedStr.includes("(")) {
        const pattern = new RegExp(/\+\d+/g);
        country_code = pattern.exec(targetedStr)[0];
        if (phoneRawInput.value !== "") {
          phoneMainHidden.value = `${country_code}_${phoneRawInput.value}`;
        } else {
          phoneMainHidden.value = `${country_code}`;
        }
      }
    });

    const targetedStr = phonePrefixSelect.value.toString();
    if (targetedStr.includes("(")) {
      const pattern = new RegExp(/\+\d+/g);
      country_code = pattern.exec(targetedStr)[0];
      phoneMainHidden.value = `${country_code}_${phoneRawInput.value}`;
      // console.log(pattern);
    }
  }
}

/**
 * @extra function 2
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
      // console.log(location);
      // console.log(location.href);
      // console.log(location.host);
      // console.log(location.hostname);
      const clickedItem = amie.currentTarget.dataset.item;
      if (pse !== clickedItem) elToggle(clickedItem, `${clickedItem}-content`);
      // const queryParams = new URLSearchParams(window.location.search);
      // if (queryParams.get("error")) {
      // }
      location.replace(`${location.origin + location.pathname}`); // if anything went wrong remove this line
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
 * @extra function 3
 * @click event for validating uploaded file
 */
function validateUploadedFile(event, fileFormants) {
  // /(\.jpg|\.jpeg|\.png|\.gif)$/i
  // "/(png|.jpg|.jpeg|.pdf)$/i"
  // console.log(event);
  // console.log(fileFormants);
  // const allowFormats = `/(\\.${fileFormants.join("|\\.")})$/i`;
  const ext = event.target.files[0].type.split("/")[1];
  const matchFormat = fileFormants.find((f) => f === ext);
  if (!matchFormat) {
    const af = fileFormants.join(", ");
    event.target.value = "";
    alert(`Invalid file formats, please use any of ${af} file type`);
  }
}

function errorMessageElement(errMsg) {
  const errString = `
                    <div class='alert alert-danger'>
                      <div class='err-msg d-flex align-items-center'>
                          <img src='/public/icons/error.svg' width='25' alt='error-message' class='error-message mx-3'>
                          <p class='m-0'>${errMsg}</p>
                      </div>
                    </div>
                    `;
  const domParser = new DOMParser();
  const errMsgElement = domParser.parseFromString(errString, "text/html");
  return errMsgElement.activeElement.childNodes[0];
}

/**
 * @extra function 4
 * ==========================================================
 */
function textInputValidate(textVal, minLen = 1) {
  if (textVal === null || textVal === "" || textVal?.length < minLen) {
    return false;
  }
  return true;
}

document.addEventListener("DOMContentLoaded", (dcle) => {
  /**
   * @page 1
   * @page login
   * ==========================================================
   */
  if (
    window.location.pathname === "/login.php" ||
    window.location.pathname === "/login/" ||
    window.location.pathname === "/login"
  ) {
    const phoneLoginBtn = document.querySelector(".use-phone-btn");
    const emailLoginBtn = document.querySelector(".use-email-btn");

    const phoneLoginForm = document.querySelector(".phone-login-form");
    const emailLoginForm = document.querySelector(".email-login-form");

    phoneLoginBtn.addEventListener("click", (plbe) => {
      if (emailLoginForm.classList.contains("d-block")) {
        emailLoginForm.classList.remove("d-block");
        emailLoginForm.classList.add("d-none");

        phoneLoginForm.classList.remove("d-none");
        phoneLoginForm.classList.add("d-block");
      }
    });

    emailLoginBtn.addEventListener("click", (elbe) => {
      if (phoneLoginForm.classList.contains("d-block")) {
        phoneLoginForm.classList.remove("d-block");
        phoneLoginForm.classList.add("d-none");

        emailLoginForm.classList.remove("d-none");
        emailLoginForm.classList.add("d-block");
      }
    });

    countryCodePrefixForPhone(false);
  }

  /**
   * @page 2
   * @page signup
   * Select mobile number
   * ==========================================================
   */
  if (
    window.location.pathname === "/signup.php" ||
    window.location.pathname === "/signup/" ||
    window.location.pathname === "/signup"
  ) {
    // const memberSignupSubmit = document.querySelector('input[ name="member_signup_submit"]');
    // memberSignupSubmit.addEventListener("click", (mse) => {
    //   mse.preventDefault();
    // });

    const signupForm = document.getElementById("signup-form");
    signupForm.addEventListener("submit", (event) => {
      try {
        let valudationSucceed = true;
        /*
        const allTextInput = signupForm.querySelectorAll('input[type="text"]');
        allTextInput.forEach((ati) => {
          // console.log({ name: ati?.name, value: ati.value });
          valudationSucceed = textInputValidate(ati.value);
        });
        if (!valudationSucceed) return event.preventDefault();
        */
        const privacyPolicyInput = signupForm.querySelector("input[name='pp']");
        // console.log(privacyPolicyInput.checked);
        valudationSucceed = privacyPolicyInput.checked;
        // console.log({ valudationSucceed });
        if (valudationSucceed === false) {
          // Show error message
          const msgElement = errorMessageElement("You must agree with our privacy policy.");
          signupForm.parentElement.insertBefore(msgElement, signupForm);
          signupForm.parentElement.scrollIntoView();
          return event.preventDefault();
        }
        // const allInputs = signupForm.querySelectorAll('input');
        // allInputs.forEach((ipt)=>{
        //   console.log(ipt.value);
        // });
        // return event.preventDefault();
      } catch (subErr) {
        console.log(subErr);
        event.preventDefault();
      }
    });

    countryCodePrefixForPhone(true);

    // Validate file types
    const govtIdInput = document.querySelector("[name='govt_id']");
    if (govtIdInput) {
      // /(\.jpg|\.jpeg|\.png|\.gif)$/i
      govtIdInput.addEventListener("change", (giie) => validateUploadedFile(giie, ["png", "jpg", "jpeg", "pdf"]));
    }
  }

  /**
   * @page 3
   * @page dashboard
   * Toggle content for dashboard sidebar menu item
   */
  if (
    window.location.pathname === "/dashboard.php" ||
    window.location.pathname === "/dashboard/" ||
    window.location.pathname === "/dashboard"
  ) {
    const generateLink = document.getElementById("generate-link");
    const generateLinkDisplay = document.getElementById("generated-link-display");
    const allMenuItems = document.querySelectorAll(".menu-item");
    const allContent = document.querySelectorAll(".content");
    if (allMenuItems && allContent) sidebarElementToggle(allMenuItems, allContent);

    countryCodePrefixForPhone(true);

    const govtIdInput = document.querySelector("[name='govt_id']");
    const govtIdInputProof = document.querySelector("[name='govt_id_proof']");
    const addressInputProof = document.querySelector("[name='address_proof']");
    if (govtIdInput) {
      // /(\.jpg|\.jpeg|\.png|\.gif)$/i
      govtIdInput.addEventListener("change", (giie) => validateUploadedFile(giie, ["png", "jpg", "jpeg", "pdf"]));
    }
    if (govtIdInputProof) {
      govtIdInputProof.addEventListener("change", (gipe) => validateUploadedFile(gipe, ["png", "jpg", "jpeg", "pdf"]));
    }
    if (addressInputProof) {
      addressInputProof.addEventListener("change", (aipe) => validateUploadedFile(aipe, ["png", "jpg", "jpeg", "pdf"]));
    }

    const agreement = document.getElementById("agreement");
    if (agreement) {
      // Work with ck editor
      ClassicEditor.create(agreement).catch((error) => {
        console.error(error);
      });
    }

    // generateLink.addEventListener('click', (gle)=>{
    //   gle.preventDefault();
    //   // console.log(gle.target.parentElement);
    const params = new URLSearchParams(window.location.search);
    const stockvelId = params.get("stockvel_id");

    //   // 1 = view, 2 = edit
    //   const view = 1;
    //   const code = Math.floor(1000 + Math.random() * 9000); // random 4 digit code
    //   const newLink = `${window.location.origin}/pack_single/?stockvell_id=${stockvelId}&sharing=${code}${view}${stockvelId}`;

    //   generateLinkDisplay.textContent = newLink;

    //   if(generateLinkDisplay.classList.contains('d-none')){
    //     generateLinkDisplay.classList.remove('d-none');
    //     generateLinkDisplay.classList.add('d-block');
    //   }
    // });
    // console.log({text: generateLinkDisplay.textContent.toString().trim()});
    
    /*
    if (generateLinkDisplay) {
      if (generateLinkDisplay.textContent && generateLinkDisplay.textContent.toString().trim() !== "" && generateLinkDisplay.textContent.toString().trim() !== "null") {
        const newLink = `${
          window.location.origin
        }/pack_single/?stockvel_id=${stockvelId}&sharing=${generateLinkDisplay.textContent.toString().trim()}`;
        generateLinkDisplay.textContent = newLink;
      } else {
        const newLink = `${window.location.origin}/pack_single/?stockvel_id=${stockvelId}`;
        generateLinkDisplay.textContent = newLink;
      }
    }
    */
    // console.log(generateLinkDisplay);
  }

  /**
   * @page 4
   * @page admin
   * Toggle content for dashboard sidebar menu item
   */
  // console.log(window.location.pathname);
  if (
    window.location.pathname === "/admin.php" ||
    window.location.pathname === "/admin/" ||
    window.location.pathname === "/admin"
  ) {
    countryCodePrefixForPhone(true);
    const url = new URLSearchParams(window.location.search);
    if (url.get("stockvell_id")) {
      const singleStockvellPack = document.getElementById("single-stockvell-pack");
      const approvedStockvellList = document.getElementById("approved-stockvell-list");
    }
    const logedinContent = document.querySelector(".section-2");
    if (logedinContent) {
      const allMenuItems = document.querySelectorAll(".menu-item");
      const allContent = document.querySelectorAll(".content");
      if (allMenuItems && allContent) sidebarElementToggle(allMenuItems, allContent);
    }

    const agreement = document.getElementById("agreement");
    if (agreement) {
      // Work with ck editor
      ClassicEditor.create(agreement).catch((error) => {
        console.error(error);
      });
    }
  }

  // recover-via-email
  /**
   * @page 5
   * @page forget_password
   * Toggle content for email and phone recover form
   */
  if (
    window.location.pathname === "/forget_password.php" ||
    window.location.pathname === "/forget_password/" ||
    window.location.pathname === "/forget_password"
  ) {
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

    countryCodePrefixForPhone(false);
  }

  /**
   * @page 6
   * @page edit stockvell
   */
  if (
    window.location.pathname === "/edit_stockvell.php" ||
    window.location.pathname === "/edit_stockvell/" ||
    window.location.pathname === "/edit_stockvell"
  ) {
    const agreement = document.getElementById("agreement");
    if (agreement) {
      // Work with ck editor
      ClassicEditor.create(agreement).catch((error) => {
        console.error(error);
      });
    }
  }

  /**
   * @page 7
   * @page edit member
   */
  if (
    window.location.pathname === "/edit_member.php" ||
    window.location.pathname === "/edit_member/" ||
    window.location.pathname === "/edit_member"
  ) {
    countryCodePrefixForPhone(true);
    const govtIdInput = document.querySelector("[name='govt_id']");
    if (govtIdInput) {
      // /(\.jpg|\.jpeg|\.png|\.gif)$/i
      govtIdInput.addEventListener("change", (giie) => validateUploadedFile(giie, ["png", "jpg", "jpeg", "pdf"]));
    }
  }

  /**
   * @page 8
   * @page pack_single
   * Toggle content for btn and form for uploading 2 document
   */
  if (
    window.location.pathname === "/pack_single.php" ||
    window.location.pathname === "/pack_single/" ||
    window.location.pathname === "/pack_single"
  ) {
    // Toggle content
    const leaderRequestBtn = document.getElementById("leader-request-btn");
    const leaderRequestForm = document.getElementById("leader-request-form");
    const cancelLeaderRequest = document.getElementById("cancel-leader-request");

    // Toggle form
    if (leaderRequestBtn) {
      leaderRequestBtn.addEventListener("click", (lrbe) => {
        lrbe.preventDefault();
        if (leaderRequestForm.classList.contains("d-none")) {
          leaderRequestForm.classList.remove("d-none");
          leaderRequestForm.classList.add("d-block");
        }
        if (leaderRequestBtn.classList.contains("d-block")) {
          leaderRequestBtn.classList.remove("d-block");
          leaderRequestBtn.classList.add("d-none");
        }
      });
      cancelLeaderRequest.addEventListener("click", (clre) => {
        clre.preventDefault();
        if (leaderRequestForm.classList.contains("d-block")) {
          leaderRequestForm.classList.remove("d-block");
          leaderRequestForm.classList.add("d-none");
        }
        if (leaderRequestBtn.classList.contains("d-none")) {
          leaderRequestBtn.classList.remove("d-none");
          leaderRequestBtn.classList.add("d-block");
        }
      });
    }

    // upload file validation
    const govtIdInput = document.querySelector("[name='govt_id_proof']");
    const addressIdInput = document.querySelector("[name='address_proof']");
    if (govtIdInput) {
      // /(\.jpg|\.jpeg|\.png|\.gif)$/i
      govtIdInput.addEventListener("change", (giie) => validateUploadedFile(giie, ["png", "jpg", "jpeg", "pdf"]));
    }
    if (addressIdInput) {
      addressIdInput.addEventListener("change", (aiie) => validateUploadedFile(aiie, ["png", "jpg", "jpeg", "pdf"]));
    }
  }

  // custom-header stickey-top
  /**
   * @page 9
   * @page home
   * Make the header stickey
   */
  if (
    window.location.pathname === "/home.php" ||
    window.location.pathname === "/home/" ||
    window.location.pathname === "/home" ||
    window.location.pathname === "/" ||
    window.location.pathname === "/index.php"
  ) {
    // Stickey header
    const customHeader = document.querySelector(".custom-header");
    // console.log({customHeader, loc: window.location.pathname});
    customHeader.classList.add("sticky-top");
  }
});

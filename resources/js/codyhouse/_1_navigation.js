function navigation() {
    let accordions = document.querySelectorAll(".accordion");

    accordions.forEach(function (el) {
        let headings = el.querySelectorAll(".accordion__title");

        headings.forEach(function (el) {
            el.addEventListener("click", function (e) {
                let accordionBody = e.target.parentNode.querySelector(".accordion__content");

                if (Boolean(accordionBody.style.maxHeight)) {
                    e.target.parentNode.classList.remove("is-open");
                    accordionBody.style.paddingBottom = "0";
                    accordionBody.style.maxHeight = null;
                } else {
                    headings.forEach(function (el) {
                        el.parentNode.classList.remove("is-open");
                        el.parentNode.querySelector(".accordion__content").style.paddingBottom = "0";
                        el.parentNode.querySelector(".accordion__content").style.maxHeight = null;
                    });
                    e.target.parentNode.classList.add("is-open");
                    accordionBody.style.paddingBottom = "2rem";
                    accordionBody.style.maxHeight = accordionBody.scrollHeight + "px";
                }
            });
        });
    });
}

navigation();
// File#: _1_accordion

function accordions() {
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

accordions();

// function initAcc(elem, option){
//     //addEventListener on mouse click
//     document.addEventListener('click', function (e) {
//         //check is the right element clicked
//         if (!e.target.matches(elem+' .accordion-section-title')) return;
//         else{
//             //check if element contains active class
//             if(!e.target.parentElement.classList.contains('active')){
//                 if(option==true){
//                      //if option true remove active class from all other accordions 
//                     var elementList = document.querySelectorAll(elem +' .accordion-container');
//                     Array.prototype.forEach.call(elementList, function (e) {
//                         e.classList.remove('active');
//                     });
//                 }    
//                 //add active class on cliked accordion     
//                 e.target.parentElement.classList.add('active');
//                 // e.target.parentElement.style.setProperty('--max-height');
//             }else{
//                 //remove active class on cliked accordion     
//                 e.target.parentElement.classList.remove('active');
//             }
//         }
//     });
// }

// //activate accordion function
// initAcc('.accordion', true);
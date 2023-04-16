import KeenSlider from 'keen-slider';
var sliderElement = document.getElementById("start-slider")
var sliderdefault = document.getElementById("slider-default")
var interval = 0
var intervaldefault = 0


if(typeof(sliderdefault) != 'undefined' && sliderdefault != null){

  function updateClasses(instancedefault) {
    var slide = instancedefault.details().relativeSlide
    var arrowLeft = document.getElementById("arrow-left")
    var arrowRight = document.getElementById("arrow-right")
    slide === 0
      ? arrowLeft.classList.add("arrow--disabled")
      : arrowLeft.classList.remove("arrow--disabled")
    slide === instancedefault.details().size - 1
      ? arrowRight.classList.add("arrow--disabled")
      : arrowRight.classList.remove("arrow--disabled")
  
    var dots = document.querySelectorAll(".dot")
    dots.forEach(function (dot, idx) {
      idx === slide
        ? dot.classList.add("dot--active")
        : dot.classList.remove("dot--active")
    })
  }
  
 function autoplay(run) {
  clearInterval(interval)
  interval = setInterval(() => {
    if (run && slider) {
      slider.next()
    }
  }, 4500)
}
  
  var slider = new KeenSlider(sliderdefault, {
    loop: true,
    duration: 1000,
    // slidesPerView: 2,
    // mode: "free-snap",
    widthOrHeight: 100,
    // spacing: 15,
    centered: true,
    dragStart: () => {
      autoplay(false)
    },
    dragEnd: () => {
      autoplay(true)
    },

  })

  sliderdefault.addEventListener("mouseover", () => {
    autoplay(false)
  })
  sliderdefault.addEventListener("mouseout", () => {
    autoplay(true)
  })
  autoplay(true)
  
  }


if(typeof(sliderElement) != 'undefined' && sliderElement != null){

function updateClasses(instance) {
  var slide = instance.details().relativeSlide
  var arrowLeft = document.getElementById("arrow-left")
  var arrowRight = document.getElementById("arrow-right")
  slide === 0
    ? arrowLeft.classList.add("arrow--disabled")
    : arrowLeft.classList.remove("arrow--disabled")
  slide === instance.details().size - 1
    ? arrowRight.classList.add("arrow--disabled")
    : arrowRight.classList.remove("arrow--disabled")

  var dots = document.querySelectorAll(".dot")
  dots.forEach(function (dot, idx) {
    idx === slide
      ? dot.classList.add("dot--active")
      : dot.classList.remove("dot--active")
  })
}

function autoplay(run) {
  clearInterval(interval)
  interval = setInterval(() => {
    if (run && slider) {
      slider.next()
    }
  }, 4500)
}

var slider = new KeenSlider(sliderElement, {
  loop: true,
  duration: 1000,
  // slidesPerView: 2,
  // mode: "free-snap",
  widthOrHeight: 100,
  // spacing: 15,
  centered: true,
  dragStart: () => {
    autoplay(false)
  },
  dragEnd: () => {
    autoplay(true)
  },
    created: function (instance) {
    document
      .getElementById("arrow-left")
      .addEventListener("click", function () {
        instance.prev()
      })

    document
      .getElementById("arrow-right")
      .addEventListener("click", function () {
        instance.next()
      })
    var dots_wrapper = document.getElementById("dots")
    var slides = document.querySelectorAll(".keen-slider__slide")
    slides.forEach(function (t, idx) {
      var dot = document.createElement("button")
      dot.classList.add("dot")
      dots_wrapper.appendChild(dot)
      dot.addEventListener("click", function () {
        instance.moveToSlide(idx)
      })
    })
    updateClasses(instance)
  },
  slideChanged(instance) {
    updateClasses(instance)
  },
  
})

sliderElement.addEventListener("mouseover", () => {
  autoplay(false)
})
sliderElement.addEventListener("mouseout", () => {
  autoplay(true)
})
autoplay(true)

}
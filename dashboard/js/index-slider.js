let userRole = userOrAdminDetails.role;
let swiper;
let sliderMessage = document.querySelector(".slider-message");

function initializeSwiperWithParam(className, swiperWrapperClass) {
  if (swiper) {
    swiper.destroy(true, true);
  }

  swiper = new Swiper(`.${className}`, {
    slidesPerView: 4,
    spaceBetween: 20,
    sliderPerGroup: 4,
    loop: shouldLoop(swiperWrapperClass),
    centerSlide: "true",
    fade: "true",
    grabCursor: "true",
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
      dynamicBullets: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },

    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      520: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 3,
      },
      1000: {
        slidesPerView: 4,
      },
    },
  });

  let SwiperButtonLock = document.querySelectorAll('.swiper-button-lock');

  if(shouldLoop(swiperWrapperClass)) {
    SwiperButtonLock.forEach(btn => {
        btn.classList.remove('swiper-button-lock');
    });
  }
}

function initializeSwiper() {
  if (swiper) {
    swiper.destroy(true, true);
  }

  swiper = new Swiper(".slide-container", {
    slidesPerView: 4,
    spaceBetween: 20,
    sliderPerGroup: 4,
    loop: shouldLoop(),
    centerSlide: "true",
    fade: "true",
    grabCursor: "true",
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
      dynamicBullets: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },

    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      520: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 3,
      },
      1000: {
        slidesPerView: 4,
      },
    },
  });
}

function shouldLoop() {
  // Get the number of slides within the swiper wrapper
  var swiperWrapper = document.querySelector('.swiper-wrapper');
  var numSlides = swiperWrapper.querySelectorAll('.card').length;
  // Return true if there are more than 4 slides, otherwise return false
  return numSlides >= 4;
}

// Ensure the function is called as soon as the HTML document is fully loaded
document.addEventListener('DOMContentLoaded', function() {
  displaySliderRooms(userRole)
});

// Rooms Ui validator
function displaySliderRooms(userRole) {
  if(userRole === 'admin'){

      const url = "controller/roomsController.php";
      const data = {
          queryAllRooms: true,
      };
  
      handlePostRequest(url,data)
      .then((response) => {
          // console.log('response: ', response)
          var jsonResponse = JSON.parse(response);
          if(jsonResponse.rooms.length > 0) {
              jsonResponse.rooms.forEach(room => {
                  displayRoomsInSlider(room);
              });
              initializeSwiper();
              if(sliderMessage){
                sliderMessage.classList.add("display-none")
              }
             
          }
          else {
            if(sliderMessage) {
              sliderMessage.classList.remove("display-none")
            }
              console.log(response)
          }
      })
      .catch((error) => {
          alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
          console.log("Error:", error);
      });
  }
}

function displayRoomsInSlider(room) {

  // console.log(`Room ID: ${room.roomId}`);
  // console.log(`Room Name: ${room.roomName}`);
  // console.log(`Room Max Capacity: ${room.roomMaxCap}`);
  // console.log(`Room Description: ${room.roomDescription}`);
  // console.log(`Room Published Rate: ${room.roomPublishedRate}`);
  // console.log(`Image Link: ${room.imageLink}`);
  // console.log(`Other Rate: ${room.otherRate}`);
  // console.log(`Other Rate Amount: ${room.otherRateAmount}`);
  // console.log('---------------------');
  let publishedAmount = room.roomPublishedRate;

  if (!isNaN(parseFloat(publishedAmount))) {
      let numericAmount = parseFloat(publishedAmount);
      let formattedCurrency = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(numericAmount);
      publishedAmount = formattedCurrency;
  }

  let roomMainImage = JSON.parse(room.imageLink)[0].Link;
 
  const swiperCards = `
      <div class="card swiper-slide" id="swiperCard-${room.roomId}">
      <div class="image-box">
          <img src="${roomMainImage}" alt="" />
      </div>
      <div class="profile-details">
          <img src="${roomMainImage}" alt="" />
          <div class="name-job">
          <h3 class="name">${room.roomName}</h3>
          </div>
      </div>
      </div>
  `;

  const tempDiv = document.createElement('div');
  tempDiv.innerHTML = swiperCards;

  const cardSwiperContainer = tempDiv.firstElementChild;

  const cardWrapper = document.querySelector('.card-wrapper');
  if (cardWrapper) {
      cardWrapper.appendChild(cardSwiperContainer);
  }
}

function displayRoomsInSliderWithWrapper(room, wrapper) {

  // console.log(`Room ID: ${room.roomId}`);
  // console.log(`Room Name: ${room.roomName}`);
  // console.log(`Room Max Capacity: ${room.roomMaxCap}`);
  // console.log(`Room Description: ${room.roomDescription}`);
  // console.log(`Room Published Rate: ${room.roomPublishedRate}`);
  // console.log(`Image Link: ${room.imageLink}`);
  // console.log(`Other Rate: ${room.otherRate}`);
  // console.log(`Other Rate Amount: ${room.otherRateAmount}`);
  // console.log('---------------------');
 
  const swiperCards = `
      <div class="card swiper-slide" id="swiperCard-${room.Id}">
      <div class="image-box" style="border: solid white 2px; border-radius: 10px">
          <img src="${room.Link}" class="clickable imageList" id="image--${room.Id}" alt="" />
      </div>
      </div>
  `;

  const tempDiv = document.createElement('div');
  tempDiv.innerHTML = swiperCards;

  const cardSwiperContainer = tempDiv.firstElementChild;

  if (wrapper) {
      wrapper.appendChild(cardSwiperContainer);
  }
}


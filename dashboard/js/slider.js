let userRole = userOrAdminDetails.role;
let swiper;
let sliderMessage = document.querySelector(".slider-message");

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
                  displayRooms(room);
              });
              initializeSwiper();
              sliderMessage.classList.add("display-none")
          }
          else {
              sliderMessage.classList.remove("display-none")
              console.log(response)
          }
      })
      .catch((error) => {
          alertMessage('Something went wrong, Error: ' + error, 'error', 3000);
          console.log("Error:", error);
      });
  }
}

function displayRooms(room) {

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
 
  const swiperCards = `
      <div class="card swiper-slide" id="swiperCard-${room.roomId}">
      <div class="image-box">
          <img src="${room.imageLink}" alt="" />
      </div>
      <div class="profile-details">
          <img src="${room.imageLink}" alt="" />
          <div class="name-job">
          <h3 class="name">${room.roomName}</h3>
          <h4 class="job">${room.roomDescription}</h4>
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

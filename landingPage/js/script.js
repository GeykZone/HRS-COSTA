const reserve_now_btn =  document.querySelectorAll(".reserve_now_btn");

reserve_now_btn.forEach(function(element) {
   
    element.addEventListener("click", function(){
        window.location.href = '../main/rooms.php'
    });

});
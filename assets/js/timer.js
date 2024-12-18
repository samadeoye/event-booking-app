
// === Timer === //
(function () {
  const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

  //I'm adding this section so I don't have to keep updating this pen every year :-)
  //remove this if you don't need it
  let today = new Date(),
      dd = String(today.getDate()).padStart(2, "0"),
      mm = String(today.getMonth() + 1).padStart(2, "0"),
      yyyy = today.getFullYear(),
      nextYear = yyyy + 1,
      dayMonth = "12/19/",
      //event = dayMonth + yyyy;
      event = eventDate;
  
  today = mm + "/" + dd + "/" + yyyy;
  if (today > event) {
    event = dayMonth + nextYear;
  }
  //end

  const countDown = new Date(event).getTime(),
      x = setInterval(function() {

        const now = new Date().getTime(),
          distance = countDown - now;

        
        document.getElementById("day").innerText = Math.floor(distance / (day)),
        document.getElementById("hour").innerText = Math.floor((distance % (day)) / (hour)),
        document.getElementById("minute").innerText = Math.floor((distance % (hour)) / (minute)),
        document.getElementById("second").innerText = Math.floor((distance % (minute)) / second);
        /*
        $("#day").append(Math.floor(distance / (day)));
        $("#hour").append(Math.floor((distance % (day)) / (hour)));
        $("#minute").append(Math.floor((distance % (hour)) / (minute)));
        $("#second").append(Math.floor((distance % (minute)) / second));
        */

        //do something later when date is reached
        if (distance < 0) {
          /*
          document.getElementById("headline").innerText = "Booking Ends!";
          document.getElementById("countdown").style.display = "none";
          document.getElementById("content").style.display = "block";
          */
          $("#time-left").append('<p class="p-3">Booking Ended!</p>');
          $("#countdownDiv").css("display", "none");
          clearInterval(x);
        }
        //seconds
      }, 0)
  }());

var x, i, j, selElmnt, a, b, c;
/* Look for any elements with the class "custom-select": */
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    /* For each element, create a new DIV that will act as the selected item: */
    a = document.createElement("DIV");
    a.setAttribute("class", "select-selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;

    x[i].appendChild(a);
    /* For each element, create a new DIV that will contain the option list: */
    b = document.createElement("DIV");
    b.setAttribute("class", "select-items select-hide");
    for (j = 1; j < selElmnt.length; j++) {

        /* For each option in the original select element,
        create a new DIV that will act as an option item: */
        c = document.createElement("DIV");

        c.innerHTML = selElmnt.options[j].innerHTML;

        c.setAttribute("data-id-cite",selElmnt.options[j].value);
        c.setAttribute("class","select-city");
        c.addEventListener("click", function (e) {
            /* When an item is clicked, update the original select box,
            and the selected item: */
            var y, i, k, s, h;
            s = this.parentNode.parentNode.getElementsByTagName("select")[0];
            h = this.parentNode.previousSibling;
            
            for (i = 0; i < s.length; i++) {
                if (s.options[i].innerHTML == this.innerHTML) {
                    s.selectedIndex = i;
                    h.innerHTML = this.innerHTML;
                    
                    if (h.clientWidth < 310) {
                        const str = h.textContent.slice(0, 15) + "...";
                        h.innerHTML = str;
                    } else {
                        return;
                    }

                    y = this.parentNode.getElementsByClassName("same-as-selected");
                    
                    for (k = 0; k < y.length; k++) {
                        y[k].removeAttribute("class");
                    }
                    this.setAttribute("class", "same-as-selected");
                    break;
                }
            }
            h.click();
        });
        b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener("click", function (e) {
        /* When the select box is clicked, close any other select boxes,
        and open/close the current select box: */
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
    });
}

function closeAllSelect(elmnt) {
    /* A function that will close all select boxes in the document,
    except the current select box: */
    var x, y, i, arrNo = [];
    x = document.getElementsByClassName("select-items");
    y = document.getElementsByClassName("select-selected");
    for (i = 0; i < y.length; i++) {
        if (elmnt == y[i]) {
            arrNo.push(i)
        } else {
            y[i].classList.remove("select-arrow-active");
        }
    }
    for (i = 0; i < x.length; i++) {
        if (arrNo.indexOf(i)) {
            x[i].classList.add("select-hide");
        }
    }
}

/* If the user clicks anywhere outside the select box,
then close all select boxes: */
document.addEventListener("click", closeAllSelect);

var data = {
  "id_city":"0",
  "id_compani":"0",
  "head":"0",
  "text":"0",
  "star":"0",
  "letter":"0",
};
$(".star").click(function() {
  data.star = $(this).attr("data-value");
})

$(document).on("click",".custom-select-js-cite > .select-items > div",function() {

  data.id_city = $(this).attr('data-id-cite');

  $.ajax({
    url: "/ajax/form_city.php",
    type: 'POST',
    data: data,
    dataType: "html",
    beforeSend: function() {
      $(".item_select").each(function() {
        $(this).remove();
      })
    }
  }).done(function(msg) {
    let list_compani = JSON.parse(msg);

    list_compani.forEach(function(item) {
        $(".select-items").append('<div class="item_select" data-id-company="'+item.ID+'" >'+item.NAME+'</div>');
     })
        $(".custom-select-js").removeClass("no_click");
  });


})
$(document).on("click",".item_select",function() {
    let name_company_div = $(this).text();
    $(".select-arrow-active").text(name_company_div);

  data.id_compani = $(this).attr('data-id-company');


})
$(document).ready(function() {


$("#form-comments").validator().on('submit', function(e) {
  e.preventDefault();

  var data_form = $('#form-comments').serializeArray();
  let empty = [];
  data.head =data_form[0]["value"];
  data.text =data_form[1]["value"];
  if(data.id_city == 0){
     $("[data-select=city]").next().css({"display":"block"});
    empty.push("city");
  }
  if(data.id_compani == 0){
    $("[data-select=company]").next().css({"display":"block"});
    empty.push("company");
  }


  function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
  }
  if(getCookie("letter") != undefined ){
    data.letter = "1";
  }
console.log(data);

  if(empty.length == 0){

    $.ajax({
      url: "/ajax/add_reviews.php",
      type: 'POST',
      data: data,
      dataType: "html",
      beforeSend: function() {

      },

    }).done(function(msg) {
            if(msg == 1){
              $.magnificPopup.open({
                items: {
                  src: '<div class="white-popup custom_styles_popup" >Ваш отзыв успешно отправлен</div>',
                  type: 'inline'
                },
                callbacks: {
                  afterClose: function() {
                    document.location.reload(true);
                  },
                },
              });
            }
    });
  }

});
})
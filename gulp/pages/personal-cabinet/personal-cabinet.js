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
var fd = new FormData();
var data = {
  "id_city":"0",
};
$(document).on("click",".custom-select-js-cite > .select-items > div",function() { // по клику подгружаем нужные компании из города
  data.id_city = $(this).attr('data-id-cite');
  fd.append("town", $(this).attr('data-id-cite'));

  $.ajax({
    url: "/ajax/form_city.php",
    type: 'POST',
    data: data,
    dataType: "html",
    beforeSend: function() {
      $(".item_select").each(function() {
        $(this).remove();
         $(".select-selected").last().html("Выберите регион:");
        fd.delete("id_company");
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

$(document).on("click",".select-city",function() {// получаем акйди регоина
  let name_rigion = $(this).text();
  $(".select-arrow-active").text(name_rigion);
  fd.append("town",$(this).attr("data-id-cite") );
});

$(document).on("click",".item_select",function() {// получаем акйди компании
  let name_company_div = $(this).text();
  $(".select-arrow-active").text(name_company_div);
   fd.append("id_company",$(this).attr("data-id-company") );
});


$(document).ready(function() {
  $("#change-data").click(function() {
    if($(this).hasClass("click") == false) {
      $(this).addClass("click");
      $("#true_data_person").css({"display": "none"});
      $("#for_change_person").css({"display": "block"});
      $("#change-data").text("Отменить редактирование");
    }else {
      $(this).removeClass("click");
      $("#true_data_person").css({"display": "block"});
      $("#for_change_person").css({"display": "none"});
      $("#change-data").text("Редактировать данные");
    }
  })
  $('[name=personal_phone]').mask('+7(000)000-00-00');
})
$("#save_data").click(function(e) {
  e.preventDefault();
  var error = [];
  var data_FORM =  $("#form_change_data").serializeArray();
  var input_file = $('.input_file');

  if (input_file.prop('files')[0] != undefined) {
    if (input_file.prop('files')[0]['size'] > 10485760) {
      error.push('error');
      $('.block-error-label_size').css({"display":"block"});
      error.push("error");
    }
    var format_file = input_file.prop('files')[0]['type'];
    format_file = format_file.split('/')[1];
    if ((format_file.search('jpeg') == -1) &&
        (format_file.search('pjpeg') == -1) && (format_file.search('png') == -1)) {
      $('.block-error-label_format').css({"display":"block"});
      error.push("error");
    }
  }

    if(data_FORM[5]['value'].length >16 ){
      $('[name=uf_insurance_policy]').after(
          ' <span class="label danger  error-policy-max "  >Введен длинный номер полиса</span>');
      error.push("error");

    }else if( data_FORM[5]['value'].length  <16){
      $('[name=uf_insurance_policy]').after(
          '<span class="label danger  error-policy-min "  >Введен короткий номер полиса</span>');
      error.push("error");
    }
    console.log(data_FORM[3]['value'].length );

    if(data_FORM[3]['value'].length <16){
      $('[name=personal_phone]').after(
          '<span class="label danger  error-personal_phone "  >Введен короткий номер телефона</span>');
      error.push("error");
    }
    if(data_FORM[0]['value'].length <2){
      $('[name=name]').after(
          '<span class="label danger  error-name "  >Введите имя</span>');
      error.push("error");
    }
    if(data_FORM[1]['value'].length <2){
      $('[name=last_name]').after(
          '<span class="label danger  error-last_name "  >Введите фамилию</span>');
      error.push("error");
    }
    if(data_FORM[2]['value'].length <2){
      $('[name=second_name]').after(
          '<span class="label danger  error-second_name "  >Введите отчество</span>');
      error.push("error");
    }
  if(data_FORM[4]['value'].length <2){
    $('[name=email]').after(
        '<span class="label danger  error-email "  >Введите эмеил</span>');
    error.push("error");
  }
  fd.append('import_file', input_file.prop('files')[0]);
  fd.append('name', data_FORM[0]['value']);
  fd.append('last_name', data_FORM[1]['value']);
  fd.append('second_name', data_FORM[2]['value']);
  fd.append('personal_phone', data_FORM[3]['value']);
  fd.append('email', data_FORM[4]['value']);
  fd.append('uf_insurance_policy', data_FORM[5]['value']);

  if(error.length == 0) {
    $.ajax({
      url: '/ajax/change_data_user.php',
      type: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      beforeSend: function() {

      },
      success: function(msg) {
        if (msg == "1") {
          window.location.reload();
        }
        if (msg == "Пользователь с таким эмейлом уже есть") {

          $(".input_email").
              after(
                  '<p class="danger">Пользователь с таким эмейлом уже есть</p>')
        }
      },
    }).done(function(msg) {

    });
  }
});
/*добавление файла*/
$(document).ready(function() {
  var inputs = document.querySelectorAll('.file-input')

  for (var i = 0, len = inputs.length; i < len; i++) {
    customInput(inputs[i])
  }

  function customInput (el) {
    const fileInput = el.querySelector('[type="file"]')
    const label = el.querySelector('[data-js-label]')

    fileInput.onchange =
        fileInput.onmouseout = function () {
          if (!fileInput.value) return

          var value = fileInput.value.replace(/^.*[\\\/]/, '')
          el.className += ' -chosen'
          label.innerText = value
        }
  };
});
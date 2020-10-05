window.addEventListener('DOMContentLoaded', () => {
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
        b.setAttribute("onchange", "select-items select-hide");

        for (j = 1; j < selElmnt.length; j++) {
            /* For each option in the original select element,
            create a new DIV that will act as an option item: */
            c = document.createElement("DIV");

            c.innerHTML = selElmnt.options[j].innerHTML;
            c.innerVALUE= selElmnt.options[j].value;
            if(selElmnt.options[j].attributes.class !== undefined){
             var attr_class =  selElmnt.options[j].attributes.class.value;
              c.setAttribute("class",attr_class);
            }
            c.setAttribute("data-value",c.innerVALUE);

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


                        if (h.clientWidth < 350) {
                            const str = h.textContent.slice(0, 20) + "...";
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

    function showComment() {
        const items = document.querySelectorAll('.white_block');

        items.forEach(function(item) {
            const btn = item.querySelector('.feedback__bottom_link');
            const showBlock = item.querySelector('.hidenComments');
            const count = item.querySelector('.comment-count');

            if (count && count.innerText > 0) {
                if (btn) {
                    btn.addEventListener('click', function(e) {

                        this.classList.toggle('active');

                        if (btn.classList.contains('active')) {
                            showBlock.classList.add('active');
                        } else {
                            showBlock.classList.remove('active');
                        }
                    });
                }
            } else {
                return
            }
        });
    }

    showComment();


$(document).on("click",".select-items div",function() {
   let url = $(this).attr("data-value");
  window.open(url,"_self")
})
    $(".button-comments").click(function(e) {
    let error = [];
    e.preventDefault();
    var  ID = $(this).attr("data-id-comment");
    let id_review  = $(this).attr("data-id-comment");
    let text_comment = $("[data-id-comment="+ID+"]").val();
    if(text_comment == ""){
      $(this).siblings(".danger").css({"display":"block"});
      error.push("text");
    }
    let data = {
      "id_review":id_review,
      "message":text_comment,
    };

      if(error.length == 0) {
        $.ajax({
          url: "/ajax/add-comment.php",
          type: "POST",
          data: data,
          beforeSend: function() {
          }

        }).done(function(msg) {
          if (msg == 1) {
             location.reload();
          }
        })
      }
  })


  $(".button-cited").click(function(e) {
    let error = [];
    e.preventDefault();
    var  ID = $(this).attr("data-id-cited");
    let id_comment  = $(this).attr("data-id-cited");

    let text_cited = $("[data-id-cited="+ID+"]").val();
    if(text_cited == ""){
      $(this).siblings(".danger").css({"display":"block"});
      error.push("text");
    }
    let data = {
      "id_comment":id_comment,
      "message":text_cited,
    };

 if(error.length == 0) {
   $.ajax({
     url: "/ajax/add_cited.php",
     type: "POST",
     data: data,
     beforeSend: function() {
     }

   }).done(function(msg) {
     if (msg == 1) {
       location.reload();
     }
   })
 }
  })
});


$(document).ready(function() {
  $(".delet_cation").click(function() {
    var id = $(this).attr("data-id");
    var data = {
      "case":"cation",
      "id":id,
    };
        $.ajax({
              dataType: 'html',
              url: '/ajax/delet_feedback.php',
              type: 'POST',
              data: data,
              success: function(msg){
                if(msg == 1) {
                  window.location.reload();
                }
              },
            })
  });
  $(".delet_comment").click(function() {
      var id = $(this).attr("data-id");
      var data = {
          "case": "comment",
          "id": id,
      };
      $.ajax({
          dataType: 'html',
          url: "/reviews/remove-comment",
          type: 'POST',
          data: data,
          success: function (msg) {
              if (msg == 1) {
                  window.location.reload();
              }
          },
      })
  });
  $(".button-cite").click(function (e) {
        let error = [];
        e.preventDefault();
        var ID = $(this).attr("data-id-cited");
        let id_comment = $(this).attr("data-id-cited");

        let text_cited = $("[data-id-cited=" + ID + "]").val();
        if (text_cited == "") {
            $(this).siblings(".danger").css({"display": "block"});
            error.push("text");
        }
        let data = {
            "id_comment": id_comment,
            "message": text_cited,
        };
        if (error.length == 0) {
            $.ajax({
                url: "/reviews/add-citation",
                type: "POST",
                data: data,
                beforeSend: function () {
                }
            }).done(function (msg) {
                if (msg == 1) {
                    location.reload();
                }
            })
        }
  });
  $(".delete_cation").click(function() {
        var id = $(this).attr("data-id");
        var data = {
            "case":"cation",
            "id":id,
        };
        $.ajax({
            dataType: 'html',
            url: "/reviews/remove-citation",
            type: 'POST',
            data: data,
            success: function(msg){
                if(msg == 1) {
                    window.location.reload();
                }
            },
        })
  });
  $(".dalete_review").click(function() {
    var id = $(this).attr("data-id");
    var data = {
      "case":"review",
      "id":id,
    };
    $.ajax({
      dataType: 'html',
      url: '/ajax/delet_feedback.php',
      type: 'POST',
      data: data,
      success: function(msg){
        if(msg == 1) {
          window.location.reload();
        }
      },
    })
  });

  $('.toggle_comment_dropdown').on('click', function (e) {
    $(this).parent().toggleClass('openedBlock');
  });
});

function  check_review(el) {
 var _= $(el);
 var id= _.attr("data-check-id");
 var arr_check = $("#checkbox-box_"+id+" input:checked");
 var data = {};
 var accepted ="";
 var reject = "";



if(arr_check.length >0 ) {

  if(arr_check[0]["value"] == "accepted") {
    accepted = "accepted";
  }
  if(arr_check[0]["value"] == "reject") {
    reject = "reject";
  }

  if(arr_check.length >1 ) {
    if (arr_check[1]["value"] == "accepted") {
      accepted = "accepted";
    }
    if (arr_check[1]["value"] == "reject") {
      reject = "reject";
    }
  }
  data= {
    'id':id,
    "accepted":accepted,
    "reject":reject,
  };

      $.ajax({
            dataType: 'html',
            url: '/reviews/admin-check',
            type: 'POST',
            data: data,
            beforeSend: function() {

            },
            success: function(msg){
              if(msg == "1"){
              $(".white_block_riview_"+id).html("");
              $(".white_block_riview_"+id).html("Отзыв промодерирован и произведен посчтет рейтинга");

              }
            },
          }).done(function(msg) {

          });

}
}
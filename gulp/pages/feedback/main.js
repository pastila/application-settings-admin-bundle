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
   let url = "/feedback/"+ $(this).attr("data-value");
  window.open(url,"_self")
})
    $(".button-comments").click(function(e) {
    let error = [];
    e.preventDefault();
    let id_review  = $(this).attr("data-id-comment");

    let text_comment = $(this).siblings("[name]").val();
    if(text_comment == ""){
      $(this).siblings(".danger").css({"display":"block"});
      error.push("text");
    }
    let data = {
      "id_review":id_review,
      "message":text_comment,
    };

    $.ajax({
      url : "/ajax/add-comment.php",
      type: "POST",
      data :data,
      beforeSend: function() {
      }

    }).done(function(msg) {
      if(msg == 1){
        location.reload("_self")
      }
    })

  })


  $(".button-cited").click(function(e) {
    let error = [];
    e.preventDefault();
    let id_comment  = $(this).attr("data-id-cited");

    let text_cited = $(this).siblings("[name]").val();
    if(text_cited == ""){
      $(this).siblings(".danger").css({"display":"block"});
      error.push("text");
    }
    let data = {
      "id_comment":id_comment,
      "message":text_cited,
    };

    $.ajax({
      url : "/ajax/add_cited.php",
      type: "POST",
      data :data,
      beforeSend: function() {
      }

    }).done(function(msg) {
      if(msg == 1){
        location.reload("_self")
      }
    })

  })
})

<?php ?>
<div class="modal">
    <div class="close-modal close-js">
        <img src="/local/templates/kdteam/images/svg/close_modal.svg" alt="">
    </div>

    <h3 class="title">
        Обращение находится в вашем личном кабинете
    </h3>

    <p>Напоминаем вам, что перед отправкой к обращению необходимо присоединить сканкопию или фотографию имеющихся
        документов об оплате медицинской помощи. Также перед отправкой мы рекомендуем поверить правильность данных в
        обращении. Обращение будет направлено в адрес страховой компании, которую вы указали при регистрации на сайте.
    </p>
    <div class="link_block">
        <a href="/obrashcheniya/">Перейти в мои обращения</a>
    </div>
</div>
<script>

  $(document).on("click",".close-js",function() {
    window.location.href = `${window.location.origin}${window.location.pathname}`
  })
</script>
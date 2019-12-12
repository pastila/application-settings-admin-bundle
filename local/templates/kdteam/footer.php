<?php
if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}
?>
</main>

<?php if ($APPLICATION->GetCurDir() != "/") { ?>
    <!-- Footer -->
    <footer class="footer">
        <div class="footer__container">
            <div class="footer__logo">
                <img src="/local/templates/kdteam/images/svg/header/logo/logo_header.svg" alt="OMS">

                <div class="footer__logo_name">
                    Company Name
                </div>
            </div>

            <ul class="footer__menu">
                <li>
                    <a href="#">Страховым организациям</a>
                </li>
                <li>
                    <a href="#">О нас</a>
                </li>
                <li>
                    <a href="#">связаться</a>
                </li>
                <li>
                    <a href="/terms-of-use/">Пользовательское соглашение</a>
                </li>
                <li>
                    <a href="/personal-data-processing/">Политика по обработке персональных данных</a>
                </li>
            </ul>

            <!-- Custom Form -->

            <form class="footer__form" action="">
                <div class="custom-serach">
                    <input type="text" name="search" placeholder="Поиск">

                    <button class="search-btn">
                        <img src="/local/templates/kdteam/images/svg/search.svg" alt="">
                    </button>
                </div>
            </form>

        </div>
    </footer>
<?php } ?>


</div><!-- END Wrap -->
</body>
<script src="/local/templates/kdteam/js/validator.min.js"></script>
</html>

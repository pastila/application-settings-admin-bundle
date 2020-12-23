"use strict";
import initRegionSelectionModal from '../../frontend/js/regionSelectionModal/regionSelectionModal';
import { initContactUsBtn } from '../../frontend/js/feedback-popup/feedback-popup';
import headerInit from '../../frontend/js/header-init/header-init';
import initQuestionAnswer from '../../frontend/js/initQuestionAnswer/initQuestionAnswer';


function _extends() {
  _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];
      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }
    return target;
  };
  return _extends.apply(this, arguments);
}

/* ^^^
 * Глобальные-вспомогательные функции
 * ========================================================================== */

/**
  * Возвращает HTML-код иконки из SVG-спрайта
  *
  * @param {String} name Название иконки из спрайта
  * @param {Object} opts Объект настроек для SVG-иконки
  *
  * @example SVG-иконка
  * getSVGSpriteIcon('some-icon', {
  *   tag: 'div',
  *   type: 'icons', // colored для подключения иконки из цветного спрайта
  *   class: '', // дополнительные классы для иконки
  *   mode: 'inline', // external для подключаемых спрайтов
  *   url: '', // путь до файла спрайта, необходим только для подключаемых спрайтов
  * });
  */
function getSVGSpriteIcon(name, opts) {
  opts = _extends({
    tag: 'div',
    type: 'icons',
    "class": '',
    mode: 'inline',
    url: ''
  }, opts);
  var external = '';
  var typeClass = '';

  if (opts.mode === 'external') {
    external = "".concat(opts.url, "/sprite.").concat(opts.type, ".svg");
  }

  if (opts.type !== 'icons') {
    typeClass = " svg-icon--".concat(opts.type);
  }

  opts["class"] = opts["class"] ? " ".concat(opts["class"]) : '';
  return "\n    <".concat(opts.tag, " class=\"svg-icon svg-icon--").concat(name).concat(typeClass).concat(opts["class"], "\" aria-hidden=\"true\" focusable=\"false\">\n      <svg class=\"svg-icon__link\">\n        <use xlink:href=\"").concat(external, "#").concat(name, "\"></use>\n      </svg>\n    </").concat(opts.tag, ">\n  ");
}
/* ^^^
 * JQUERY Actions
 * ========================================================================== */


$(function () {
  'use strict';
  /**
   * определение существования элемента на странице
   */

  $.exists = function (selector) {
    return $(selector).length > 0;
  };


  $(window).on('scroll', function (event) {
    if ($(window).scrollTop() >= $('.b-return').offset().top - $('.b-return').outerHeight()) {
      $('.b-return').addClass('is-visible');
    } // animation


    if ($(window).scrollTop() >= $('.b-question').offset().top - $('.b-question').outerHeight()) {
      $('.b-question').addClass('is-visible');
    } // animation


    if ($(window).scrollTop() >= $('.b-first').offset().top - $('.b-first').outerHeight()) {
      $('.b-first, .app-header').addClass('is-visible');
    } // animation


    if ($(window).scrollTop() >= $('.b-work').offset().top - $('.b-work').outerHeight()) {
      $('.b-work').addClass('is-visible');
    } // animation


    if ($(window).scrollTop() >= $('.b-rating').offset().top - $('.b-rating').outerHeight()) {
      $('.b-rating').addClass('is-visible');
    } // animation


    if ($(window).scrollTop() >= $('.b-reviews').offset().top - $('.b-reviews').outerHeight()) {
      $('.b-reviews').addClass('is-visible');
    } // animation


    if ($(window).scrollTop() >= $('.b-insider').offset().top - $('.b-insider').outerHeight()) {
      $('.b-insider').addClass('is-visible');
    } // animation


    if ($(window).scrollTop() >= $('.b-info').offset().top - $('.b-info').outerHeight()) {
      $('.b-info').addClass('is-visible');
    }
  }).trigger('scroll');

  initRegionSelectionModal();

  $('.js-scroll').on('click', function (event) {
    event.preventDefault();
    $('.nav-active, .app-header__menu-button').removeClass('nav-active is-opened');
    $('html, body').animate({
      'scrollTop': $($(this).attr('href')).offset().top - 100
    }, 700);
  });
  $('.b-first__button').on('click', function (event) {
    event.preventDefault();
    $('html, body').animate({
      'scrollTop': $('.b-first').outerHeight()
    }, 700);
  });
  $('select').styler();

  const Scrollbar = window.Scrollbar;
  const itemScrollbar = document.querySelectorAll('.b-form__item--full .jq-selectbox__dropdown ul');

  if (itemScrollbar.length) {
    itemScrollbar.forEach((item) => {
      Scrollbar.init(item, {
        alwaysShowTracks: true
      });
    })
  }

  if ($('.b-insider__slider').length) {
    var sliderInsider = tns({
      container: '.b-insider__slider',
      items: 3.3,
      loop: true,
      mouseDrag: true,
      autoWidth: true,
      speed: 400,
      nav: false,
      controls: false
    });
  }

  initQuestionAnswer();

  if ($('.b-reviews__slider').length) {
    var sliderReviews = tns({
      container: '.b-reviews__slider',
      items: 3.3,
      loop: true,
      mouseDrag: true,
      autoWidth: true,
      speed: 400,
      nav: false,
      controls: false
    });
  }

  var PAGE = $('html, body');
  var pageScroller = $('.page-scroller');
  var inMemoryClass = 'page-scroller--memorized';
  var isVisibleClass = 'page-scroller--visible';
  var enabledOffset = 60;
  var pageYOffset = 0;
  var inMemory = false;

  function resetPageScroller() {
    setTimeout(function () {
      if (window.pageYOffset > enabledOffset) {
        pageScroller.addClass(isVisibleClass);
      } else if (!pageScroller.hasClass(inMemoryClass)) {
        pageScroller.removeClass(isVisibleClass);
      }
    }, 150);

    if (!inMemory) {
      pageYOffset = 0;
      pageScroller.removeClass(inMemoryClass);
    }

    inMemory = false;
  }

  if (pageScroller.length > 0) {
    window.addEventListener('scroll', resetPageScroller, window.supportsPassive ? {
      passive: true
    } : false);
    pageScroller.on('click', function (event) {
      event.preventDefault();
      window.removeEventListener('scroll', resetPageScroller);

      if (window.pageYOffset > 0 && pageYOffset === 0) {
        inMemory = true;
        pageYOffset = window.pageYOffset;
        pageScroller.addClass(inMemoryClass);
        PAGE.stop().animate({
          scrollTop: 0
        }, 500, 'swing', function () {
          window.addEventListener('scroll', resetPageScroller, window.supportsPassive ? {
            passive: true
          } : false);
        });
      } else {
        pageScroller.removeClass(inMemoryClass);
        PAGE.stop().animate({
          scrollTop: pageYOffset
        }, 500, 'swing', function () {
          pageYOffset = 0;
          window.addEventListener('scroll', resetPageScroller, window.supportsPassive ? {
            passive: true
          } : false);
        });
      }
    });
  }

  let xhr = null;
  let timerId = null;

  $('.input-regions').on('input', (e) => {
    const value = e.target.value;
    const listRegions = document.querySelector('.l-list');

    if (xhr) {
      xhr.abort();
    }

    if (timerId) {
      clearTimeout(timerId);
    }

    timerId = setTimeout(() => {
      xhr = $.ajax({
        dataType: 'html',
        url: value.length ? `${urlPrefix}/api/v1/regions?name=${value}` : `${urlPrefix}/api/v1/regions`,
        type: 'GET',
      })
          .done((result) => {
            if (result && listRegions) {
              const data = JSON.parse(result);
              if (data && data.regions && data.regions.length) {
                $(listRegions).empty();
                data.regions.forEach((item) => {
                  let listItem = document.createElement('div')
                  listItem.className = 'l-list__item';
                  listItem.innerHTML = `<a href="#" data-region="${item.id}">${item.name}</a>`
                  listRegions.appendChild(listItem);
                })
              } else {
                listRegions.innerHTML = '<p class="l-list__message">Ничего не найдено</p>'
              }
            }
          })
          .fail((err) => {
            if (listRegions && err.statusText !== 'abort') {
              listRegions.innerHTML = '<p class="l-list__message">Произошла ошибка. Попробуйте повторить позже.</p>'
            }
          })
    }, 150);
  })

  initContactUsBtn();
  headerInit();
});
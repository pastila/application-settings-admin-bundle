<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
  die();
}
/**
 * Очередь для передачи принадлежности файлов
 */
const queue_obrashcheniya_files = 'obrashcheniya_files';
/**
 * Очередь для передачи email писем
 */
const queue_obrashcheniya_emails = 'obrashcheniya_emails';

const obrashcheniya_report_path = '/var/www/var/uploads/pdf/';
const obrashcheniya_report_url_download = '/app_dev.php/obrashcheniya/report-download?file=';
const obrashcheniya_file_type_report = 'report';
const obrashcheniya_file_type_attach = 'attach';
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

const obrashcheniya_report_attached_path = '/var/www/var/uploads/attached/';
const obrashcheniya_report_path = '/var/www/var/uploads/pdf/';
const obrashcheniya_report_url_download = '/appeals/%s/download';
const obrashcheniya_file_type_report = 'report';
const obrashcheniya_file_type_attach = 'attach';

const obrashcheniya_appeal_files_api = 'http://nginx/api/v1/appeal-files?api_token=%s&data=%s';
const obrashcheniya_appeal_emails_api = 'http://nginx/api/v1/appeal-email?api_token=%s&data=%s';
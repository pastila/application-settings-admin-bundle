# Bezbahil
Bezbahil

## Version: 1.0.0

### /regions

#### GET
##### Summary

Список регионов

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| name | query | Название региона | No | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Ok |

### /diseases

#### GET
##### Summary

Список болезней

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| name | query | Название болезни | No | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Ok |

### /medical-organizations

#### GET
##### Summary

Список медицинских организаций

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| query | query | Название органиазации | No | string |
| region | query | Идентификатор региона | No | integer |
| year | query | Год | No | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Ok |

### /phone-verify

#### POST
##### Summary

Отправить проверочный СМС код

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Ok |
| 400 | Bad phone format |
| 500 | Server error |

### /patients

#### GET
##### Summary

Поиск пациентов

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| lastName | query |  | No | string |
| firstName | query |  | No | string |
| middleName | query |  | No | string |
| insurancePolicyNumber | query |  | No | string |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Ok |

### /lk/my-appeals

#### GET
##### Summary

Обращения пользователя

##### Parameters

| Name | Located in | Description | Required | Schema |
| ---- | ---------- | ----------- | -------- | ---- |
| page | query |  | No | integer |

##### Responses

| Code | Description |
| ---- | ----------- |
| 200 | Ok |

### Models

#### Pager

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| page | integer | _Example:_ `2` | No |
| maxPerPage | integer | _Example:_ `10` | No |
| firstPage | integer | _Example:_ `1` | No |
| lastPage | integer | _Example:_ `3` | No |
| nbResults | integer | _Example:_ `25` | No |
| more | integer | _Example:_ `5` | No |

#### Region

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| id | integer | _Example:_ `8` | No |
| name | string | _Example:_ `"11  Республика Коми"` | No |

#### Disease

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| id | integer |  | No |
| code | string |  | No |
| name | string |  | No |

#### MedicalOrganization

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| code | string |  | No |
| name | string |  | No |
| fullName | string |  | No |
| address | string |  | No |

#### InsuranceCompany

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| id | integer |  | No |
| name | string |  | No |

#### Patient

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| id | integer |  | No |
| lastName | string |  | No |
| firstName | string |  | No |
| middleName | string |  | No |
| birthDate | string |  | No |
| phone | string |  | No |
| insurancePolicyNumber | string |  | No |
| region | object |  | No |
| insuranceCompany | object |  | No |

#### Appeal

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| id | integer |  | No |
| createdAt | string | _Example:_ `"May 26, 2021 12:01"` | No |
| sentAt | string | _Example:_ `"May 26, 2021 12:01"` | No |
| status | integer | 0 - В процессе создания, 1 - Создан, но не отправлен, 2 - Отправлен<br>_Enum:_ `0`, `1`, `2`<br>_Example:_ `1` | No |
| region | object |  | No |
| disease | object |  | No |
| medicalOrganization | object |  | No |
| year | integer | _Example:_ `2020` | No |
| draftStep | integer | Шаг создания обращения<br>_Example:_ `6` | No |
| patient | object |  | No |
| documents | [ object ] |  | No |

#### AppealDocument

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| originalFilename | string |  | No |
| fileSize | string | _Example:_ `"16 Mb"` | No |
| url | string | _Example:_ `"/uploads/file.zip"` | No |
| extension | string | _Example:_ `"zip"` | No |

#### inline_response_200

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| regions | [ object ] |  | No |

#### body

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| phone | string |  | No |

#### inline_response_200_1

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| pager | object |  | No |
| items | [ object ] |  | No |

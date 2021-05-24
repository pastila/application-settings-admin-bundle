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

### Models

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

#### inline_response_200

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| regions | [ object ] |  | No |

#### body

| Name | Type | Description | Required |
| ---- | ---- | ----------- | -------- |
| phone | string |  | No |

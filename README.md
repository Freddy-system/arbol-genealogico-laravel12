# Backend AGA API

API para gestión de personas y árbol genealógico (ancestros/descendientes/parentescos).

## Rutas principales

- **Persons**
  - `GET /api/persons?per_page=15`
  - `POST /api/persons`
  - `GET /api/persons/{id}`
  - `PUT /api/persons/{id}`
  - `DELETE /api/persons/{id}`

- **Relationships**
  - `POST /api/relationships/parentage`
  - `DELETE /api/relationships/parentage`
  - `POST /api/relationships/marriage`
  - `PATCH /api/relationships/marriage/end/{id}`

- **Genealogy**
  - `GET /api/genealogy/ancestors/{id}`
  - `GET /api/genealogy/descendants/{id}`
  - `GET /api/genealogy/tree/{id}`

- **Kinship**
  - `GET /api/kinship`

## Parámetros útiles (query)

- **Genealogía**
  - `include=full` retorna datos completos de la persona en cada nodo.
  - `maxDepth` (ancestors/descendants) controla profundidad.
  - `depth` y `direction=both|asc|desc` (tree) controlan profundidad y sentido.

## Ejemplos (URLs para Postman)

- **Listado paginado de personas**
  - GET `http://127.0.0.1:8000/api/persons?per_page=15`

- **Crear persona**
  - POST `http://127.0.0.1:8000/api/persons`
  - Body JSON de ejemplo: `{ "first_name": "Ana", "last_name": "Lopez" }`

- **Crear parentesco (padre/madre-hijo)**
  - POST `http://127.0.0.1:8000/api/relationships/parentage`
  - Body JSON: `{ "parent_id": 13, "child_id": 15, "type": "father" }`

- **Matrimonio y fin de matrimonio**
  - POST `http://127.0.0.1:8000/api/relationships/marriage`
    - Body: `{ "spouse_a_id": 13, "spouse_b_id": 14, "start_date": "2020-01-01" }`
  - PATCH `http://127.0.0.1:8000/api/relationships/marriage/end/1`
    - Body: `{ "end_date": "2024-01-01", "status": "divorced" }`

- **Genealogía: ancestros y descendientes**
  - GET `http://127.0.0.1:8000/api/genealogy/ancestors/15?maxDepth=4&include=full`
  - GET `http://127.0.0.1:8000/api/genealogy/descendants/9?maxDepth=4&include=full`

- **Genealogía: árbol mixto**
  - GET `http://127.0.0.1:8000/api/genealogy/tree/15?depth=4&direction=both&include=full`

- **Parentesco entre dos personas**
  - GET `http://127.0.0.1:8000/api/kinship?personA=13&personB=15`


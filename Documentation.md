# Table of contents

-   [Departments](#departments)
    1. [Create](#create-departments)
    2. [Update](#update-departments)
    3. [GET](#get-departments)
        - [Index](#index-departments)
        - [Show](#show-departments)
    4. [Delete](#delete-departments)
-   [Levels](#levels)
    1. [Create](#create-levels)
    2. [Update](#update-levels)
    3. [GET](#get-levels)
        - [Index](#index)
        - [Show](#show-levels)
    4. [Delete](#delete-levels)
-   Positions
    1. Create
    2. Update
    3. Read
    4. Delete
-   Employees
    1. Create
    2. Update
    3. Read
    4. Delete
-   Salary
    1. Create
    2. Update
    3. Read
    4. Delete
-   Salary Type
    1. Create
    2. Update
    3. Read
    4. Delete

# [Departments](#departments)

### [Index Departments](#index-departments)

this api show every departments

```
GET "/api/v1/departments"
```

Request description:

> | Name       | Type                  | Description            | nullable |
> | ---------- | --------------------- | ---------------------- | -------- |
> | company_id | Primary Key (integer) | primary key of company | yes      |

Request Example :

```
{
    "company_id" : "kada" or null,
}
```

Response Example:

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :[
        {
            "id" : "dept-1",
            "name" : "Human Resource",
            "head" : {
                "id" : "employee_id",
                "name" : "Victor Yansen",
            },
            "total" : 8
        },
        {
            "id" : "dept-2",
            "name" : "Human Resource",
            "head" : "Information Technology",
            "total" : 8,
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
        }
    ]
}
```

### [Show Departments](#show-departments)

this api show department by requested id

```
GET "/api/v1/departments/{id}"
```

Request description:

> | Name | Type                  | Description                            |
> | ---- | --------------------- | -------------------------------------- |
> | Id   | Primary Key (integer) | primary key of departments on Resource |

Response Example :

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :{
        "id" : "dept-1",
        "name" : "Human Resource",
        "head" : {
            "id" : "employee_id",
            "name" : "Victor Yansen",
        },
        "total" : 8
    }
}

```

## [Create Departments](#create-departments)

Create new Departments

##### Request Url :

```
POST "/api/v1/departments"
```

Request Example :

> | Name       | Type   | Description            | nullable |
> | ---------- | ------ | ---------------------- | -------- |
> | Name       | String | Name of Department     | no       |
> | company_id | String | primary key of company | no       |

Request Example :

```
{
    "name" : "dept-1",
    "company_id" : "comp-1",
}
```

Response Example :

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :{
        "id" : "dept-2",
        "name" : "Human Resource",
        "head" : "Information Technology",
        "total" : 8,
        "company" : {
            "id" : "comp-1",
            "name" : "KADA",
        },
    }
}
```

## [Update Departments](#update-departments)

Update Department By Id

```
PUT "/api/v1/departments/dept-2"
```

Request description :

> | Name       | Type                  | Description                  |
> | ---------- | --------------------- | ---------------------------- |
> | Id         | Primary Key (integer) | primary key of departments   |
> | company_id | integer               | primary key or id of company |

Request Example :

```
{
    "name" : "Resource Human",
    "company_id" : "comp-1",
}
```

Response Example :

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :{
        "id" : "dept-2",
        "name" : "Resource Human",
        "head" : "Information Technology",
        "total" : 8,
        "company" : {
            "id" : "comp-1",
            "name" : "KADA",
        },
    }
}
```

## [Delete-departments](#delete-departments)

Delete Department by Id all Position has connected with department will change to null or moved base on your request params

```
DELETE "api/v1/departments/dept-1
```

Request Description :

> | Name | Type                  | Description                                                | Nullable |
> | ---- | --------------------- | ---------------------------------------------------------- | -------- |
> | Id   | Primary Key (integer) | primary key of departments                                 | no       |
> | Id   | Primary Key (integer) | primary key of departments for replace deleted departments | yes      |

Request Example:

```
{
    "department_id" : "dept-1",
}
```

Response Example:

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
        },
    "data" : null
}
```

# [Levels](#levels)

## [Index](#index-levels)

#### Show every levels

```
GET /api/v1/levels
```

Request description:

> | Name       | Type        | Description |
> | ---------- | ----------- | ----------- |
> | company_id | primary key | company_id  |

Request Example:

```
{
    "company_id" : "KADA"
}
```

Response Example:

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :[
        {
            "id" : "Lv-1",
            "name" : "Level - C",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
        },
        {
            "id" : "Lv-2",
            "name" : "Level - Staff",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
        }
    ]
}
```

### [Show Level](#show-levels)

this api show level by requested id

```
GET "/api/v1/levels/lv-1"
```

Response Example:

```

{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :{
        "id" : "Lv-1",
        "name" : "Level - C",
        "company" : {
            "id" : "comp-1",
            "name" : "KADA",
        },
    }
}

```

## [Create Levels](#create-levels)

Create new Levels

```
POST "/api/v1/levels"
```

Request Description:

> | Name       | Type    | Description                  | nullable |
> | ---------- | ------- | ---------------------------- | -------- |
> | Name       | String  | Name of level                | no       |
> | company_id | integer | primary key or id of company | no       |

Request Example :

```
{
    "name" : "Level - Office",
    "company_id" : "comp-1",
}
```

Response Example :

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :{
        "id" : "lv-3",
        "name" : "Level - Office",
        "company" : {
            "id" : "comp-1",
            "name" : "KADA",
        },
    }
}
```

## [Update Levels](#update-levels)

Update level By Id

```
PUT "/api/v1/levels/lv-3"
```

Request Description :

> | Name       | Type                  | Description            | nullable |
> | ---------- | --------------------- | ---------------------- | -------- |
> | Id         | Primary Key (integer) | primary key of levels  | no       |
> | Name       | String                | Name of levels         | no       |
> | company_id | integer               | primary key of company | no       |

Request Example :

```
{
    "name" : "Level - B",
    "company_id" : "comp-1",
}
```

Response Example:

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :{
        "id" : "lv-3",
        "name" : "Level - B",
        "company" : {
            "id" : "comp-1",
            "name" : "KADA",
        },
    }
}
```

## [Delete Levels](#delete-levels)

Delete level by Id all Position has connected with level will change to null or moved base on your request params

```
DELETE "api/v1/levels/{id}
```

Request description :

> | Name     | Type                  | Description                                     | Nullable |
> | -------- | --------------------- | ----------------------------------------------- | -------- |
> | id       | Primary Key (integer) | primary key of levels                           | no       |
> | level_id | Primary Key (integer) | primary key of levels for replace deleted level | yes      |

Request Example :

```
{
    "level_id" : "lv-2",
}
```

Response Example:

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    "data" : null
}
```

# [Positions](#positions)

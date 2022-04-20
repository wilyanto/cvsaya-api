# Table of contents

-   [Departments](#departments)
    1. [Create](#create-departments)
    2. [Update](#update-departments)
    3. [GET](#index-departments)
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
-   [Positions](#positions)
    1. [Create](#create-positions)
    2. [Update](#update-positions)
    3. [GET](#index-positions)
        - [Index](#index-positions)
        - [Show](#show-positions)
    4. [Delete](#delete-positions)
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
> | id   | Primary Key (integer) | primary key of departments on Resource |

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
> | name       | String | Name of Department     | no       |
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
PUT "/api/v1/departments/{id}"
```

Request description :

> | Name       | Type                  | Description                  |
> | ---------- | --------------------- | ---------------------------- |
> | id         | Primary Key (integer) | primary key of departments   |
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
DELETE "api/v1/departments/{id}
```

Request Description :

> | Name          | Type                  | Description                                                | Nullable |
> | ------------- | --------------------- | ---------------------------------------------------------- | -------- |
> | id            | Primary Key (integer) | primary key of departments                                 | no       |
> | department_id | Primary Key (integer) | primary key of departments for replace deleted departments | yes      |

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

## [Show Level](#show-levels)

this api show level by requested id

```
GET "/api/v1/levels/{id}"
```

Response Description :

> | Name | Type        | Description           | nullable |
> | ---- | ----------- | --------------------- | -------- |
> | id   | primary Key | primary key of levels | no       |

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
> | name       | String  | Name of level                | no       |
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
PUT "/api/v1/levels/{id}"
```

Request Description :

> | Name       | Type                  | Description            | nullable |
> | ---------- | --------------------- | ---------------------- | -------- |
> | id         | Primary Key (integer) | primary key of levels  | no       |
> | name       | String                | Name of levels         | no       |
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

## [Index](#index-positions)

Show every positions

```
GET /api/v1/positions
```

Request description:

> | Name          | Type        | Description                      | nullable |
> | ------------- | ----------- | -------------------------------- | -------- |
> | company_id    | foreign key | company_id                       | yes      |
> | department_id | foreign key | primary key on table departments | yes      |
> | level_id      | foreign key | primary ky on table levels       | yes      |

Request Example:

```
{
    "company_id" : "KADA",
    "department_id" : "",
    "level_id : ""
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
            "id" : "position-1",
            "name" : "HRD",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
            "department" : {
                "id" : "dept-1",
                "name" : "Human Resource",
            },
            "level" : {
                "id" : "lv-1",
                "name" : "Level - C"
            },
            "priorty" : 0,
            "min_salary" : 1000,
            "max_salary" : 100000,
            "remaining_slot" : 100,
            "parent" : [
                {
                    "id" : "positions-2",
                    "name" : "anggota HRD",
                    "department" : {
                        "id" : "dept-1",
                        "name" : "Human Resource",
                    },
                    "level" : {
                        "id" : "lv-2",
                        "name" : "Level - Staff",
                    },
                    "priorty" : 0,
                    "min_salary" : 1000,
                    "max_salary" : 100000,
                    "remaining_slot" : 100,
                    "parent" : null,
                },
                {
                    "id" : "positions-3",
                    "name" : "anggota HRD v2",
                    "department" : {
                        "id" : "dept-1",
                        "name" : "Human Resource",
                    },
                    "level" : {
                        "id" : "lv-2",
                        "name" : "Level - Staff",
                    },
                    "priorty" : 0,
                    "min_salary" : 1000,
                    "max_salary" : 100000,
                    "remaining_slot" : 100,
                    "parent" : null,
                }
            ]
        },
        {
            "id" : "position-4",
            "name" : "IT",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
            "department" : {
                "id" : "dept-2,
                "name" : "IT",
            },
            "level" : {
                "id" : "lv-1",
                "name" : "Level - C"
            },
            "priorty" : 0,
            "min_salary" : 1000,
            "max_salary" : 100000,
            "remaining_slot" : 100,
            "parent" : null
        }
    ]
}
```

### [Show Positions](#show-levels)

this api show positions by requested id

```
GET "/api/v1/positions/{id}"
```

> | Name | Type        | Description                | nullable |
> | ---- | ----------- | -------------------------- | -------- |
> | id   | primary key | primary key of position id | no       |

Response Example:

```

{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :{
            "id" : "position-4",
            "name" : "IT",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
            "department" : {
                "id" : "dept-2,
                "name" : "IT",
            },
            "level" : {
                "id" : "lv-1",
                "name" : "Level - Staff"
            },
            "priorty" : 0,
            "min_salary" : 1000,
            "max_salary" : 100000,
            "remaining_slot" : 100,
            "parent" : null
        }
    }
}

```

## [Create positions](#create-positions)

Create new Levels

```
POST "/api/v1/positions"
```

Request Description:

> | Name          | Type    | Description                      | nullable |
> | ------------- | ------- | -------------------------------- | -------- |
> | name          | String  | Name of level                    | no       |
> | company_id    | integer | primary key or id of company     | no       |
> | parent_id     | integer | primary key of table positions   | no       |
> | department_id | integer | primary key of table departments | no       |
> | level_id      | integer | primary key of table levels      | no       |

Request Example :

```
{
    "name" : "anggota IT",
    "company_id" : "comp-1",
    "parent_id" : "positions-4",
    "department_id" : "dept-2",
    "level" : "lv-2",
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
            "id" : "position-4",
            "name" : "IT",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
            "department" : {
                "id" : "dept-2,
                "name" : "IT",
            },
            "level" : {
                "id" : "lv-1",
                "name" : "Level - C"
            },
            "priorty" : 0,
            "min_salary" : 1000,
            "max_salary" : 100000,
            "remaining_slot" : 100,
            "parent" : [
                {
                    "id" : "positions-5",
                    "name" : "anggota IT",
                    "department" : {
                        "id" : "dept-2",
                        "name" : "IT",
                    },
                    "level" : {
                        "id" : "lv-2",
                        "name" : "Level - Staff",
                    },
                    "priorty" : 0,
                    "min_salary" : 1000,
                    "max_salary" : 100000,
                    "remaining_slot" : 100,
                    "parent" : null,
                },
            ]
        }
    }
}
```

## [Update Positions](#update-Positions)

Update level By Id

```
PUT "/api/v1/positions/{id}"
```

Request Description :

> | Name          | Type                  | Description                      | nullable |
> | ------------- | --------------------- | -------------------------------- | -------- |
> | id            | Primary Key (integer) | primary key of levels            | no       |
> | name          | String                | Name of levels                   | no       |
> | department_id | integer               | primary key of table departments | no       |
> | level_id      | integer               | primary key of table levels      | no       |

Request Example :

```
{
    "name" : "IT Lead",
    "company_id" : "comp-1",
    "parent_id" : "positions-0",
    "department_id" : "dept-2",
    "level" : "lv-1",
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
            "id" : "position-4",
            "name" : "IT Lead",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
            "department" : {
                "id" : "dept-2,
                "name" : "IT",
            },
            "level" : {
                "id" : "lv-1",
                "name" : "Level - C"
            },
            "priorty" : 0,
            "min_salary" : 1000,
            "max_salary" : 100000,
            "remaining_slot" : 100,
            "parent" : [
                {
                    "id" : "positions-5",
                    "name" : "anggota IT",
                    "department" : {
                        "id" : "dept-2",
                        "name" : "IT",
                    },
                    "level" : {
                        "id" : "lv-2",
                        "name" : "Level - Staff",
                    },
                    "priorty" : 0,
                    "min_salary" : 1000,
                    "max_salary" : 100000,
                    "remaining_slot" : 100,
                    "parent" : null,
                },
            ]
        }
    }
}
```

## [Delete positions](#delete-positions)

Delete level by Id all Position has connected with level will change to null or moved base on your request params

```
DELETE "api/v1/positions/{id}
```

Request description :

> | Name | Type                  | Description           | Nullable |
> | ---- | --------------------- | --------------------- | -------- |
> | id   | Primary Key (integer) | primary key of positions | no       |

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

Response Index Positions Example:

```
"meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :[
        {
            "id" : "position-1",
            "name" : "HRD",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
            "department" : {
                "id" : "dept-1",
                "name" : "Human Resource",
            },
            "level" : {
                "id" : "lv-1",
                "name" : "Level - C"
            },
            "priorty" : 0,
            "min_salary" : 1000,
            "max_salary" : 100000,
            "remaining_slot" : 100,
            "parent" : [
                {
                    "id" : "positions-2",
                    "name" : "anggota HRD",
                    "department" : {
                        "id" : "dept-1",
                        "name" : "Human Resource",
                    },
                    "level" : {
                        "id" : "lv-2",
                        "name" : "Level - Staff",
                    },
                    "priorty" : 0,
                    "min_salary" : 1000,
                    "max_salary" : 100000,
                    "remaining_slot" : 100,
                    "parent" : null,
                }
            ]
        },
       {
            "id" : "position-4",
            "name" : "IT Lead",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
            "department" : {
                "id" : "dept-2,
                "name" : "IT",
            },
            "level" : {
                "id" : "lv-1",
                "name" : "Level - C"
            },
            "priorty" : 0,
            "min_salary" : 1000,
            "max_salary" : 100000,
            "remaining_slot" : 100,
            "parent" : [
                {
                    "id" : "positions-5",
                    "name" : "anggota IT",
                    "department" : {
                        "id" : "dept-2",
                        "name" : "IT",
                    },
                    "level" : {
                        "id" : "lv-2",
                        "name" : "Level - Staff",
                    },
                    "parent" : null,
                },
            ]
        }
    ]
```

# [Employees](#employees)

## [Index Employees](#index-employees)

Showing all List of Employee

```
GET /api/v1/employees
```

Request description:

> | Name          | Type        | Description                      |
> | ------------- | ----------- | -------------------------------- |
> | company_id    | primary key | company_id                       |
> | position_id   | primary key | primary key of table positions   |
> | department_id | primary key | primary key of table departments |
> | level_id      | primary key | primary key of table levels      |

Request Example 1:

```
{
    "company_id" : "KADA",
    "position_id" : "position-1",
    "department_id" : "dept-1",
    "level_id" : "lv-1"
}
```

Response Example 1:

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :[
        {
            "id" : "employee-1",
            "name" : "Wilyanto",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
            "positions" : {
                "id" : "position-1",
                "name" : "HRD"
            }
            "department" : {
                "id" : "dept-1",
                "name" : "Human Resource"
            },
            "level" : {
                "id" : "lv-1",
                "name" : "level-c"
            },
            "join-at" : "2022-04-05T00:00:00.000000Z",
            "employment_type" : {
                "id" : "et-1",
                "name" : "Full-Time",
            },
            "salary_type_id" : {
                "id" : "st-1",
                "name" : "full-time"
            }
            "amount" : 1000000,
            "deleted_at" : null,
            "teams" : [
            {
                "leader" : {
                    "id" : "employee-2",
                    "name" : "Victor Yansen",
                },
                "project : CvSaya,
                "pathner-employees": [
                    {
                    "id" : "employee-3",
                    "name" : "Ricky",
                    },
                ],
                "created_at" : "2022-04-05T00:00:00.000000Z",
                "updated_at" : "2022-04-05T00:00:00.000000Z",
            },
        ]
        }
    ]
}
```

## [Show Employees](#show-employees)

this api show Employee by requested id

```
GET "/api/v1/employees/{id}"

```

Response Description:

> | Name | Type        | Description                     |
> | ---- | ----------- | ------------------------------- |
> | id   | primary key | primary key of table employee s |

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
        "id" : "employee-1",
        "first-name" : "Wilyanto",
        "company" : {
            "id" : "comp-1",
            "name" : "KADA",
        },
        "positions" : {
            "id" : "position-1",
            "name" : "HRD"
        }
        "department" : {
            "id" : "dept-1",
            "name" : "Human Resource"
        },
        "level" : {
            "id" : "lv-1",
            "name" : "level-c"
        },
        "join-at" : "2022-04-05T00:00:00.000000Z",
        "employment_type" : {
            "id" : "et-1",
            "name" : "Full-Time",
        },
        "salary_type_id" : {
            "id" : "st-1",
            "name" : "full-time"
        }
        "amount" : 1000000,
        "deleted_at" : null,
        "teams" : [
            {
                "leader" : {
                    "id" : "employee-2",
                    "name" : "Victor Yansen",
                },
                "project : CvSaya,
                "pathner-employees": [
                    {
                    "id" : "employee-3",
                    "name" : "Ricky",
                    },
                ],
                "created_at" : "2022-04-05T00:00:00.000000Z",
                "updated_at" : "2022-04-05T00:00:00.000000Z",
            },
        ]
    }
}

```

## [Create Employees](#create-Employees)

Create new Employees, to create new employees candidate finish all prosedure in interview

```
POST "/api/v1/Employees"
```

Request Description:

> | Name            | Type          | Description                             | nullable |
> | --------------- | ------------- | --------------------------------------- | -------- |
> | candidate_id    | foreign_key   | primary key on table candidates         | no       |
> | position_id     | foreign_key   | primary key on table positions          | no       |
> | employment_type | foreign_key   | primary key on table employment_types   | no       |
> | joined_at       | timestamp iso | time when employee will join at company | no       |
> | salary_type_id  | foreign_key   | primary key on table salary_types       | no       |
> | salary_amount   | integer       | amount salary of employee               | no       |

Request Example :

```
{
    "candidate_id" : "candidate-1",
    "position_id" : "position-1",
    "employment_type" : "et-1",
    "joined_at" : "2022-04-05T00:00:00.000000Z",
    "salary_type_id" : "st_id",
    "amount" : 1000000,
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
        "id" : "employee-2",
        "name" : "Victor Yansen",
        "company" : {
            "id" : "comp-1",
            "name" : "KADA",
        },
        "positions" : {
            "id" : "position-1",
            "name" : "HRD"
        }
        "department" : {
            "id" : "dept-1",
            "name" : "Human Resource"
        },
        "level" : {
            "id" : "lv-1",
            "name" : "level-c"
        },
        "join-at" : "2022-04-05T00:00:00.000000Z",
        "employment_type" : {
            "id" : "et-1",
            "name" : "Full-Time",
        },
        "salary_type_id" : {
            "id" : "st-1",
            "name" : "Harian"
        }
        "amount" : 1000000,
        "deleted_at" : null,
        "teams" : [
            {
                "leader" : {
                    "id" : "employee-2",
                    "name" : "Victor Yansen",
                },
                "project : CvSaya,
                "pathner-employees": [
                    {
                    "id" : "employee-3",
                    "name" : "Ricky",
                    },
                ],
                "created_at" : "2022-04-05T00:00:00.000000Z",
                "updated_at" : "2022-04-05T00:00:00.000000Z",
            },
        ]
    }
}
```

## [Update Employees](#update-Employees)

Update Employees By Id

```
PUT "/api/v1/Employees/{id}"
```

Request Description :

> | Name            | Type          | Description                             | nullable |
> | --------------- | ------------- | --------------------------------------- | -------- |
> | id              | primary key   | primary key of employee                 | no       |
> | candidate_id    | foreign_key   | primary key on table candidates         | no       |
> | position_id     | foreign_key   | primary key on table positions          | no       |
> | employment_type | foreign_key   | primary key on table employment_types   | no       |
> | joined_at       | timestamp iso | time when employee will join at company | no       |
> | salary_type_id  | foreign_key   | primary key on table salary_types       | no       |
> | salary_amount   | integer       | amount salary of employee               | no       |

Request Example :

```
{
    "candidate_id" : "candidate-1",
    "position_id" : "position-1",
    "employment_type" : "et-2",
    "joined_at" : "2022-04-05T00:00:00.000000Z",
    "salary_type_id" : "st_id",
    "amount" : 1000000,
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
        "id" : "employee-2",
        "name" : "Victor Yansen",
        "company" : {
            "id" : "comp-1",
            "name" : "KADA",
        },
        "positions" : {
            "id" : "position-1",
            "name" : "HRD"
        }
        "department" : {
            "id" : "dept-1",
            "name" : "Human Resource"
        },
        "level" : {
            "id" : "lv-1",
            "name" : "level-c"
        },
        "join-at" : "2022-04-05T00:00:00.000000Z",
        "employment_type" : {
            "id" : "et-2",
            "name" : "Full-Time",
        },
        "salary_type_id" : {
            "id" : "st-1",
            "name" : "Bulanan"
        }
        "amount" : 1000000,
        "deleted_at" : null,
        "teams" : [
            {
                "leader" : {
                    "id" : "employee-2",
                    "name" : "Victor Yansen",
                },
                "project : CvSaya,
                "pathner-employees": [
                    {
                    "id" : "employee-3",
                    "name" : "Ricky",
                    },
                ],
                "created_at" : "2022-04-05T00:00:00.000000Z",
                "updated_at" : "2022-04-05T00:00:00.000000Z",
            },
        ]
    }
}
```

## [Delete Employees](#delete-levels)

Employee Resign, by input new employee_id all team has old employees id will be replace with new employees

```
DELETE "api/v1/levels/{id}
```

Request description :

> | Name        | Type                    | Description             | Nullable |
> | ----------- | ----------------------- | ----------------------- | -------- |
> | id          | Primary Key (integer)   | primary key of levels   | no       |
> | employee_id | Foreign Key ( integer ) | primary key of employee | yes      |

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

Response Index Employee after Delete Example:

```

{
     "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :[
        {
        "id" : "employee-1",
        "first-name" : "Wilyanto",
        "company" : {
            "id" : "comp-1",
            "name" : "KADA",
        },
        "positions" : {
            "id" : "position-1",
            "name" : "HRD"
        }
        "department" : {
            "id" : "dept-1",
            "name" : "Human Resource"
        },
        "level" : {
            "id" : "lv-1",
            "name" : "level-c"
        },
        "join-at" : "2022-04-05T00:00:00.000000Z",
        "employment_type" : {
            "id" : "et-1",
            "name" : "Full-Time",
        },
        "salary_type_id" : {
            "id" : "st-1",
            "name" : "full-time"
        }
        "amount" : 1000000,
        "deleted_at" : null,
        "teams" : [
            {
                "leader" : {
                    "id" : "employee-2",
                    "name" : "Victor Yansen",
                },
                "project : CvSaya,
                "pathner-employees": [
                    {
                    "id" : "employee-3",
                    "name" : "Ricky",
                    },
                ],
                "created_at" : "2022-04-05T00:00:00.000000Z",
                "updated_at" : "2022-04-05T00:00:00.000000Z",
            },
        ]
    }
}
```

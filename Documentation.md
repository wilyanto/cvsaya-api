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
    3. [GET](#index)
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
-   [Employees](#employees)
    1. [Get](#index-employees)
        - [index](#index-employees)
        - [Show](#show-employees)
    2. [Create](#create-employees)
    3. [Update](#update-employees)
    4. [Delete](#delete-employees)
-   [Salary Type](#salary-type)
    1. [GET](#get-salary-types)
        - [Index Salary Employee](#index-salary-employee)
        - [Show Salary Employee](#show-salary-employee)
    2. [Create](#create-salary-type)
    3. [Update](#update-salary-type)
-   [Shifts/Attendance](#shiftsattendance)
    1. [GET](#index-shifts-mobile)
        - [Mobile](#index-shifts-mobile)
        - [index](#index-attendance)
    2. [Add](#add-attendance)
-   [Permissions](#permissions)
    1. [Index](#index-permission)
    2. [Show](#show-permissions)
    3. [Create](#create-permissions)

# [Departments](#departments)

### [Index Departments](#index-departments)

this api show every departments

```
GET "/api/v1/departments"
```

Request description:

> | Name      | Type  | Description                   | nullable |
> | --------- | ----- | ----------------------------- | -------- |
> | companies | array | list primary key of companies | yes      |

Request Example :

```
{
    "company_id" : ["kada"]
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

> | Name | Type                  | Description                            | nullable |
> | ---- | --------------------- | -------------------------------------- | -------- |
> | id   | Primary Key (integer) | primary key of departments on Resource | no       |

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

> | Name       | Type                  | Description                  | nullable |
> | ---------- | --------------------- | ---------------------------- | -------- |
> | id         | Primary Key (integer) | primary key of departments   | no       |
> | name       | string                | new name of department       | no       |
> | company_id | integer               | primary key or id of company | no       |

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
DELETE "api/v1/departments/{id}"
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

> | Name     | Type  | Description                   | nullable |
> | -------- | ----- | ----------------------------- | -------- |
> | companys | array | list primary key of companies | yes      |

Request Example:

```
{
    "companys" : ["KADA"]
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
DELETE "api/v1/levels/{id}"
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

> | Name        | Type  | Description                           | nullable |
> | ----------- | ----- | ------------------------------------- | -------- |
> | companies   | array | list primary key of companies         | yes      |
> | departments | array | list primary key on table departments | yes      |
> | levels      | array | list primary ky on table levels       | yes      |

Request Example:

```
{
    "companies" : ["KADA",]
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
GET /api/v1/positions/{id}
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
POST /api/v1/positions
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
PUT /api/v1/positions/{id}
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
DELETE api/v1/positions/{id}
```

Request description :

> | Name | Type                  | Description              | Nullable |
> | ---- | --------------------- | ------------------------ | -------- |
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
            "joined_at" : "2022-04-05T00:00:00.000000Z",
            "employment_type" : {
                "id" : "et-1",
                "name" : "Full-Time",
            },
            "salary_type_id" : {
                "id" : "st-1",
                "name" : "full-time"
            }
            "amount" : 1000000,
            "deleted_at" : null
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
        "joined_at" : "2022-04-05T00:00:00.000000Z",
        "employment_type" : {
            "id" : "et-1",
            "name" : "Full-Time",
        },
        "salary_type_id" : {
            "id" : "st-1",
            "name" : "full-time"
        }
        "amount" : 1000000,
        "deleted_at" : null
    }
}

```

## [Create Employees](#create-employees)

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

## [Update Employees](#update-employees)

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
        "joined_at" : "2022-04-05T00:00:00.000000Z",
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
    }
}
```

## [Delete Employees](#delete-employees)

Employee Resign, by input new employee_id all team has old employees id will be replace with new employees

```
DELETE api/v1/levels/{id}
```

Request description :

> | Name | Type                  | Description           | Nullable |
> | ---- | --------------------- | --------------------- | -------- |
> | id   | Primary Key (integer) | primary key of levels | no       |

Request Example:

```
{
    "employee_id" : "employee-1",
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
        "joined_at" : "2022-04-05T00:00:00.000000Z",
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
    }
}
```

# [Salary Types](#salary-types)

## [Get Salary Types](#get-salary-types)

List Master of Salary Types

```
GET /api/v1/salary-types
```

Response Example :

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data :[
        {
            "id" : "st-1",
        "company_id" : "Kada",
            "name" : "Gaji Pokok"
        },
        {
            "id" : "st-2",
        "company_id" : "Kada",
            "name" : "Tunjangan Transportasi",
        }
    ]
}
```

## [Create Salary Type](#create-salary-type)

Create Salary Type

```
POST /api/salary-types
```

Request Description :

> | Name       | Type        | Description              | Nullable |
> | ---------- | ----------- | ------------------------ | -------- |
> | name       | string      | name of new salary type  | no       |
> | company_id | foreign key | primary key of companies | no       |

Request Example :

```
{
    "name" : "Tujuangan Kehadiran",
    "company_id" : "Kada"
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
        "id" : "st-3",
        "company_id" : "Kada",
        "name" : "Tujuangan Kehadiran"
    },
}
```

## [Update Salary Type](#update-salary-type)

Update Salary Type

```
PUT /api/salary-types/{id}
```

Request Description :

> | Name       | Type        | Description                 | Nullable |
> | ---------- | ----------- | --------------------------- | -------- |
> | id         | primary key | primary key of salary types | no       |
> | name       | string      | name of new salary type     | no       |
> | company_id | foreign key | foreign key of companies    | no       |

Request Example :

```
{
    "name" : "Tujuangan Kehadiran1",
    "company_id : "KADA",
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
        "id" : "st-3",
        "company_id" : "Kada",
        "name" : "Tujuangan Kehadiran1"
    },
}
```

## [Index Salary Employee](#index-salary-employee)

List salary Every Employee

```
GET /api/v1/employees/salary
```

Response Example :

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
            "name" : "wilyanto",
            "company_id" : "Kada",
            "salary_types" : [
                {
                    "id" : "est-1",
                    "salary_type" : {
                        "id" : "ST-1",
                        "name" : "Gaji Pokok",
                    },
                    "amoung" : 1000000,
                },
                {
                    "id" : "est-2",
                    "salary_type" : {
                        "id" : "ST-2",
                        "name" : "Tujangan Transportasi",
                    },
                    "amoung" : 50000,
                }
            ]
        }
    ]
}
```

## [Show Salary Employee](#show-salary-employee)

List salary Every Employee

```
GET /api/v1/employees/{id}/salary
```

> | Name | Type                  | Description             | Nullable |
> | ---- | --------------------- | ----------------------- | -------- |
> | id   | Primary Key (integer) | primary key of employee | no       |

Response Example :

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
            "name" : "wilyanto",
            "salary_types" : [
                {
                    "id" : "est-1",
                    "salary_type" : {
                        "id" : "ST-1",
                        "name" : "Gaji Pokok",
                    },
                    "amoung" : 1000000,
                },
                {
                    "id" : "est-2",
                    "salary_type" : {
                        "id" : "ST-2",
                        "name" : "Tujangan Transportasi",
                    },
                    "amoung" : 50000,
                }
            ]
        }
    ]
}
```

# [Shifts/Attendance](#shiftsattendance)

## [Index Shifts Mobile](#index-shifts-mobile)

List Shifts for user by Mobile

```
GET /api/v1/shifts
```

Request Description

> | Name | Type             | Description            | Nullable |
> | ---- | ---------------- | ---------------------- | -------- |
> | date | timestamps (iso) | date of shifts by user | no       |

Request Example :

```
{
    "date" : "2022-04-07T00:00:00.000000Z",
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
        "id" : "employee-1",
        "name" : "wilyanto",
        "company_id" : "comp-1",
        "date" : "2022-04-07T00:00:00.000000Z",
        "shift": {
            "id" : "shifts-1",
            "started_at" : "07:45",
            "ended_at" : "17:00",
            "break_started_at" : "12:00",
            "break_ended_at" : "13:00"
        }
    }
]
```

## [Index attendance](#index-attendance)

Get List attendance by date

```
GET /api/v1/attendances
```

Request description :

> | Name       | Type             | Description                            | Nullable |
> | ---------- | ---------------- | -------------------------------------- | -------- |
> | started_at | timestamps (iso) | started date list of absence will take | no       |
> | ended_at   | timestamps (iso) | ended date list of absence will take   | yes      |

Request Example :

```
{
    "started_at" : "2022-04-07T00:00:00.000000Z",
    "ended_at" : "2022-04-08T00:00:00.000000Z"
}
```

Response Example :

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : "request success"
    },
    "data" :{
        "employee" : {
            "id" : "employee-2",
            "name" : "Victor Yansen",
        },
        "attendances" : [
            {
                "date" : "2022-04-07T00:00:00.000000Z",
                "attendance" : {
                    "clock_in" : {
                        "checked_at" : "07:48:00",
                        "duty_at" : "07:45:00",
                        "penalty" : -5000,
                    },
                    "start_break" : {
                        "checked_at" : "12:00:00",
                        "penalty" : 0,
                        "duty_at" : "12:10:00",
                    },
                    "end_break" : {
                        "checked_at" : "12:48:00",
                        "duty_at" : "12:50:00",
                        "penalty" : 0
                    },
                    "clock_out" : {
                        "checked_at" : "17:01:00",
                        "duty_at" : "17:00:00",
                        "penalty" : 0
                    }
                }
            },
            {
                "date" : "2022-04-08T00:00:00.000000Z",
                "attendance" : {
                    "clock_in" : {
                        "checked_at" : "07:48:00",
                        "duty_at" : "07:45:00",
                        "penalty" : -5000,
                    },
                    "start_break" : {
                        "checked_at" : "12:00:00",
                        "penalty" : 0,
                        "duty_at" : "12:10:00"
                    },
                    "end_break" : {
                        "checked_at" : "12:48:00",
                        "duty_at" : "12:50:00",
                        "penalty" : 0
                    },
                    "clock_out" : {
                        "checked_at" : "17:01:00",
                        "duty_at" : "17:00:00",
                        "penalty" : 0
                    }
                }
            },
            {
                "date" : "2022-04-09T00:00:00.000000Z",
                "attendance" : {
                    "clock_in" : {
                        "checked_at" : "07:48:00",
                        "duty_at" : "07:45:00",
                        "penalty" : -5000,
                    },
                    "start_break" : {
                        "checked_at" : "12:00:00",
                        "penalty" : 0,
                        "duty_at" : "12:10:00",
                    },
                    "end_break" : {
                        "checked_at" : "12:48:00",
                        "duty_at" : "12:50:00",
                        "penalty" : 0
                    },
                    "clock_out" : {
                        "checked_at" : "17:01:00",
                        "duty_at" : "17:00:00",
                        "penalty" : 0
                    }
                }
            },
        ]
    }
}
```

# [Add Attendance](#add-attendance)

Add new or update attendance

```
POST /api/v1/attendances
```

Request Description :

> | Name | Type    | Description                                         | Nullable |
> | ---- | ------- | --------------------------------------------------- | -------- |
> | time | time    | time user attend                                    | no       |
> | type | enum    | "clock_in", "clock_out", "start_break", "end_break" | no       |
> | file | picture | upload file from camera                             | no       |

Request Example :

```
{
    "time" : 12:00,
    "type" : "clock_in"
    "file" : file
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
        "employee" : {
            "id" : "employee-2",
            "name" : "Victor Yansen",
        }
        attendances : [
            {
                "date" : "2022-04-09T00:00:00.000000Z",
                "attendance" : {
                    "clock_in" : {
                        "checked_at" : "12:00:00",
                        "duty_at" : "07:45:00",
                        "penalty" : -75000,
                    },
                    "start_break" : null,
                    "end_break" : null,
                    "clock_out" : null
                }
            }
        ]
    }
}
```

# [Permissions](#permissions)

## [Index permission](#index-permission)

get List Permissions

```
GET /api/v1/attendances/permissions
```

Response Example :

```
{
     "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data : [
        {
            "id" : "p-1",
            "permission-type: {
                "id" : "pt-1",
                "name" : "cuti",
                "is_paid" : true,
                "company_id" "kada",
            },
            "started_at" : "2022-04-01T00:00:00.000000Z",
            "ended_at" : "2022-04-09T00:00:00.000000Z",
            "note" : "Liburan Dong"
        },
        {
            "id" : "p-2",
            "permission-type: {
                "id" : "pt-2",
                "name" : "Izin",
                "is_paid" : false,
                "company_id" "kada",
            },
            "started_at" : "2022-04-09T00:00:00.000000Z",
            "ended_at" : "2022-04-11T00:00:00.000000Z",
            "note" : "stress"
        }
    ]
}
```

## [show Permissions](#show-permissions)

get permission detail

```
GET /api/v1/attendances/permissions/{id}
```

> | Name | Type        | Description             | Nullable |
> | ---- | ----------- | ----------------------- | -------- |
> | id   | primary key | primary key permissions | no       |

Response Example :

```
{
    "meta" :{
        "success" : true,
        "code" : 200000,
        "message" : request success"
    },
    data : {
        "id" : "p-1",
        "permission-type: {
            "id" : "pt-1",
            "name" : "cuti",
            "is_paid" : true,
            "company_id" "kada",
        },
        "started_at" : "2022-04-01T00:00:00.000000Z",
        "ended_at" : "2022-04-09T00:00:00.000000Z",
        "note" : "Liburan Dong",
        "images" : [
            "image-1",
            "image-2",
            "image-3",
            "iamge-4",
        ],
        "is_accepted" : null
    }
```

## [Create Permissions](#create-permissions)

create permissions

```
POST /api/v1/attendances/permissions
```

Request Description :

> | Name               | Type             | Description                                                           | Nullable |
> | ------------------ | ---------------- | --------------------------------------------------------------------- | -------- |
> | started_at         | timestamps (iso) | timestamp with iso format                                             | no       |
> | ended_at           | timestamps (iso) | timestamp with iso format                                             | no       |
> | permission_type_id | foreign key      | id of permission type, earlier_than + today must more than started_at | no       |
> | note               | longtext         | max 250, min 10                                                       |
> | images             | array            | list of id document, with type permission_type_id                     |

Request Example :

```
{
    "started_at" : "2022-04-01T00:00:00.000000Z",
    "ended_at" : "2022-04-09T00:00:00.000000Z",
    "permission_type_id" : "pt-1",
    "note" : "Izin sakit bla bla",
    "images" : [image-1,image-2,image-3]
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
    data : {
        "id" : "p-1",
        "permission-type: {
            "id" : "pt-1",
            "name" : "cuti",
            "is_paid" : true,
            "company_id" "kada",
        },
        "started_at" : "2022-04-01T00:00:00.000000Z",
        "ended_at" : "2022-04-09T00:00:00.000000Z",
        "note" : "Liburan Dong",
        "images" : [
            "image-1",
            "image-2",
            "image-3",
            "iamge-4",
        ],
        "is_accepted" : null
    }
}
```

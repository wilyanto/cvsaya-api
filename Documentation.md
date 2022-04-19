# Table of contents

-   [Departments](#departments)
    1. [Create](#create-departments)
    2. [Update](#update-departments)
    3. [GET](#get-departments)
        - [Index](#index-departments)
        - [Show](#show-departments)
    4. [Delete](#delete-departments)
-   [Levels](#levels)
    1. Create
    2. Update
    3. [GET](#get-levels)
        - [Index](#index-levels)
        - [Show](#show-levels)
    4. Delete
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

## [GET Departments](#get-departments)

### [Index Departments](#index-departments)

> this api show every departments
>
> > GET "/api/v1/departments"

```
    Request Department :

        Header : {
            "Accepted"  : "application/json"
            "Content-Type" : "application/json"
            "Authorization" : "Bearer TOKEN"

        }
        Body : {
            "company_id" : "kada" or null,
        }

    Response Department :
    Body :{
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

> this api show department by requested id
>
> > GET "/api/v1/departments/dept-1"

```
Request Department :

        Header : {
            "Accepted"  : "application/json"
            "Content-Type" : "application/json"
            "Authorization" : "Bearer TOKEN"

        }

    Response Department :
        Body :
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

> | Name    | Type                  | Description                              |
> | ------- | --------------------- | ---------------------------------------- |
> | Id      | Primary Key (integer) | primary key of departments               |
> | Name    | String                | Name of Department                       |
> | head    | Object                | object of employee as head of department |
> | total   | integer               | total employee every department          |
> | company | Object                | Object of company                        |

## [Create Departments](#create-departments)

> Create new Departments
>
> > POST "/api/v1/departments"

```
Request Department :

    Header : {
        "Accepted"  : "application/json"
        "Content-Type" : "application/json"
        "Authorization" : "Bearer TOKEN"

    }
    Body : {
        "name" : "dept-1",
        "company_id" : "comp-1",
    }

Response Department :
    Body :  {
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

> | Name       | Type                  | Description                              |
> | ---------- | --------------------- | ---------------------------------------- |
> | Id         | Primary Key (integer) | primary key of departments               |
> | Name       | String                | Name of Department                       |
> | head       | Object                | object of employee as head of department |
> | total      | integer               | total employee every department          |
> | company    | Object                | Object of company                        |
> | company_id | integer               | primary key or id of company             |

### [Update Departments](#update-departments)

> Update Department By Id
>
> > PUT "/api/v1/departments/dept-2"

```
Request Department :

    Header : {
        "Accepted"  : "application/json"
        "Content-Type" : "application/json"
        "Authorization" : "Bearer TOKEN"

    }
    Body : {
        "name" : "Resource Human",
        "company_id" : "comp-1",
    }

Response Department :
    Body :{
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

> | Name       | Type                  | Description                              |
> | ---------- | --------------------- | ---------------------------------------- |
> | Id         | Primary Key (integer) | primary key of departments               |
> | Name       | String                | Name of Department                       |
> | head       | Object                | object of employee as head of department |
> | total      | integer               | total employee every department          |
> | company    | Object                | Object of company                        |
> | company_id | integer               | primary key or id of company             |

## [Delete-departments](#delete-departments)

> Delete Department by Id all Position has connected with department will change to null or moved base on your request params
>
> > DELETE "api/v1/departments/dept-1

```
    Request Department :

        Header : {
            "Accepted"  : "application/json"
            "Content-Type" : "application/json"
            "Authorization" : "Bearer TOKEN"

        }
        Body : {
            "positions_id" : "position-1",
        }

    Response Department :
        Body :{
            "meta" :{
                "success" : true,
                "code" : 200000,
                "message" : request success"
            },
            "data" : null
        }
```

> | Name         | Type                   | Description                |
> | ------------ | ---------------------- | -------------------------- |
> | Id           | Primary Key (integer)  | primary key of departments |
> | positions_id | primary key ( integer) | primary key of positions   |

# [Levels](#levels)

## [GET Levels](#get-levels)

### [Index Levels](#index-levels)
> this api show every levels
>
> > GET "/api/v1/levels"

```
    Request :

        Header : {
            "Accepted"  : "application/json"
            "Content-Type" : "application/json"
            "Authorization" : "Bearer TOKEN"

        }
        Body : {
            "company_id" : "kada",
        }

    Response :
    Body :{
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

> this api show level by requested id
>
> > GET "/api/v1/levels/lv-1"

```
Request :

        Header : {
            "Accepted"  : "application/json"
            "Content-Type" : "application/json"
            "Authorization" : "Bearer TOKEN"

        }

    Response :
        Body :
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

> | Name    | Type                  | Description                              |
> | ------- | --------------------- | ---------------------------------------- |
> | Id      | Primary Key (integer) | primary key of departments               |
> | Name    | String                | Name of Department                       |
> | company | Object                | Object of company                        |

## [Create Levels](#create-levels)

> Create new Levels
>
> > POST "/api/v1/levels"

```
Request Department :

    Header : {
        "Accepted"  : "application/json"
        "Content-Type" : "application/json"
        "Authorization" : "Bearer TOKEN"

    }
    Body : {
        "name" : "Level - Office",
        "company_id" : "comp-1",
    }

Response Department :
    Body :  {
        "meta" :{
            "success" : true,
            "code" : 200000,
            "message" : request success"
        },
        data :{
            "id" : "lv-2",
            "name" : "Level - Office",
            "company" : {
                "id" : "comp-1",
                "name" : "KADA",
            },
        }
    }
```

> | Name       | Type                  | Description                              |
> | ---------- | --------------------- | ---------------------------------------- |
> | Id         | Primary Key (integer) | primary key of departments               |
> | Name       | String                | Name of Department                       |
> | company    | Object                | Object of company                        |
> | company_id | integer               | primary key or id of company             |

### [Update Levels](#update-levels)

> Update Department By Id
>
> > PUT "/api/v1/levels/lv-2"

```
Request :

    Header : {
        "Accepted"  : "application/json"
        "Content-Type" : "application/json"
        "Authorization" : "Bearer TOKEN"

    }
    Body : {
        "name" : "Resource Human",
        "company_id" : "comp-1",
    }

Response :
    Body :{
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

> | Name       | Type                  | Description                              |
> | ---------- | --------------------- | ---------------------------------------- |
> | Id         | Primary Key (integer) | primary key of departments               |
> | Name       | String                | Name of Department                       |
> | head       | Object                | object of employee as head of department |
> | total      | integer               | total employee every department          |
> | company    | Object                | Object of company                        |
> | company_id | integer               | primary key or id of company             |

## [Delete-departments](#delete-departments)

> Delete Department by Id all Position has connected with department will change to null or moved base on your request params
>
> > DELETE "api/v1/departments/dept-1

```
    Request Department :

        Header : {
            "Accepted"  : "application/json"
            "Content-Type" : "application/json"
            "Authorization" : "Bearer TOKEN"

        }
        Body : {
            "positions_id" : "kada",
        }

    Response Department :
        Body :{
            "meta" :{
                "success" : true,
                "code" : 200000,
                "message" : request success"
            },
            "data" : null
        }
```

> | Name         | Type                   | Description                |
> | ------------ | ---------------------- | -------------------------- |
> | Id           | Primary Key (integer)  | primary key of departments |
> | positions_id | primary key ( integer) | primary key of positions   |

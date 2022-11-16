# Software Inspection Mobile App API

#### API Endpoints Simplified documentation

#### NOTE:
    1. API base URL: https://bhinspector.2times180.com/api/v1

    2. Expected headers:

        -Accept: application/vnd.api+json (required)
        -Content-Type: application/vnd.api+json  (required)
        -Authorization: Bearer <accessToken>   (required on authenticated routes)

#### ENDPOINTS:
##### 1. Registration: {API_BASE_URL}/noauth/users
    a) Sample Request:
        {
        "data": {
            "type": "users",
            "attributes": {
            "name": "userg1g2",
            "email": "userg1g@email.com",
            "password": "123456",
            "password_confirmation": "123456"
            }
        }
        }

    b) Sample Response
        {
        "data": {
            "id": "b14eea0c-3355-46ce-a606-5c1cfd53a955",
            "type": "users",
            "attributes": {
            "role": "user",
            "name": "userg1g2",
            "email": "userg1g@email.com",
            "updated_at": "2022-11-16T07:43:53.000000Z",
            "created_at": "2022-11-16T07:43:53.000000Z",
            "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZmNmMjUxMGJiZmE2NjQzYjJlMmU5NTMxZDliNjAyZjBmNjNiYTYxODllNzdiMDFjY2IxMWQ2MjU3NjEwYmJjNjBhMzRlZjRkZGJmYTgxMGUiLCJpYXQiOjE2Njg1ODQ2MzQuMDEyNzM2LCJuYmYiOjE2Njg1ODQ2MzQuMDEyNzM5LCJleHAiOjE3MDAxMjA2MzMuOTE3NzI4LCJzdWIiOiJiMTRlZWEwYy0zMzU1LTQ2Y2UtYTYwNi01YzFjZmQ1M2E5NTUiLCJzY29wZXMiOltdfQ.IF7QWJoAkGLKLs1joCHWAz8PT54bXyVdj-tE2M07Ob214m1J8Zo3IL2I4avIVsiggcIh0u-mOctSuBf4aucGC-_GG7ewZ4bkqAz2d-zl2s7TxSWr0m2q__KK9uXrOsUG1kwscogSmrVopm2oU8HZcWxB-tZEcyfbVZ2m5jxudofHYdTYJC8xFFTF2MTJ9v0u6zX7jtL2gDW2bY9WWk05rrWfi5Nb3ZWk6o3dnasxP5Gkkvgh-A-hoGVe5V-MeokU022qLQviN3H2r9CWey7alg6eZ8Tpt5D5jW-LfKAlyheJ7cwFTQ9WAHtKlea50YKwwFCJrIImPuOo3O-Pqhb_zDoFfXm8XFGmpbxjbExpCITxkwtr-GrW9aU8FSctN1EGC99UrSHM739EyVslb4hCAgszfaONb1h6mdU_gqMvEfcVxdYuJjY6KDtg9elUd83KPcBn2JmvpKmjN6aRjTiR40Tz2bZTMwh2ql8muwstjRgAu-XPFUtBypXHZbfrzQkreu_1oHcnZqVUjgK82s1lIoszkDTLn8jQ-iNLdCPLfcpuy9M6Giv6ATlaR3AU-SZDpEgyfuzSAeJzcgOigEa3l3NBIQk-2cJTnuLYx-ewr0tCgzvsm15xpx479WhMi1NBwamsvD3g2USq07K3me_dDiownMc9jdE3daZ5n3JAGCo"
            },
            "relationships": {
                *
            }
        }
    }

#####2. Login: {API_BASE_URL}/noauth/auth
    a) Request Sample:
       {
            "data": {
                "type": "auth",
                "attributes": {
                "email": "userg1g@email.com",
                "password": "123456"
                }
            }
        }
        
    b) Sample Response
        {
        "data": {
            "id": "b14eea0c-3355-46ce-a606-5c1cfd53a955",
            "type": "users",
            "attributes": {
            "name": "userg1g2",
            "email": "userg1g@email.com",
            "role": "user",
            "avatar": null,
            "created_at": "2022-11-16T07:43:53.000000Z",
            "updated_at": "2022-11-16T07:43:53.000000Z",
            "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMjQ1ZjYyMmNlMGFhMDA0ZTRkZWIxZWU5ZWEwYTRkYTkyMzJiNTc2YzFlNzcwNTNjNjE5MTY5YWIxYzM4YmUzYmRmZTExZjJlZWJmMjYwOGYiLCJpYXQiOjE2Njg1ODQ5MDIuMzI5MTgsIm5iZiI6MTY2ODU4NDkwMi4zMjkxODIsImV4cCI6MTcwMDEyMDkwMi4yNTI0NzksInN1YiI6ImIxNGVlYTBjLTMzNTUtNDZjZS1hNjA2LTVjMWNmZDUzYTk1NSIsInNjb3BlcyI6W119.A34rDCqK0V2K-QWPZ-qJ4wRVMLiEFZuldE_Ys7ptPix4-yIwy4BxSOKUy3-crF0NohIlZGjz1CTPSLAD8P4XSbDK0iXZiIOKNiWxLjoN2TFYM7LhSNnU8frsDg2DhPXypD1jLCpaXxRsJ9Rc_u9oGztU1oliCGWeZtag5kidUZ5UBwNv6HNZykMy-WXc9i2fCgi57Ht8GTcDfCNCUJU6l_BL4OKFRbz24JOv727RvZD-5tOC3STq3U_83c-p9_uxUACZ_X-qO_vuwUAibjU18B6fegAgzOot7Sx0PwMZzklL8fghmuKux79LL25hbQIw6L-ooq2KleAh5oS1buzHCRusC2lkdyavPpw1ouWGQrghqBAq2rqmoxWtigwUD2c9SpXyl_5SMYL4O7pXJ-y7TFEsrWFyZ_IChcYak62_HvuftVc353iZIFlJfgz8xVoM-8QFppBN4oJb2NuMqTB1rZsVdRk3aRMJNY8M1GPRKIDLHD7jVkaaM114YwEHZ-znObmYyqiAcFHS-NkF9-fUjcE_cTtWVhJcOLcMNTipaaX2t775T8xg8x45tTELT6ceZCL9LSjx1MPM79-gv8FGyHLoqkGULgl3Fvb6rFqTqETjB9jzAT2WpGoLAVNRUKOTbmHt7z1CjXiUq62ZVkOlZbHN-doSlrbAmsRBimA6LP0"
            },
            "relationships": {
                 *
            }
        }
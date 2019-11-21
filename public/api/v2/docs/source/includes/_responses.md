# Responses

```json
Positive Response: 
{
    "code" : "Status Code Success (200 or 201)",
    "status" : "Status message (OK)",
    "message" : "Description of message if any",
    "items" : []
}
```

```json
Negative Response: 
{
    "code" : "Status Code if Error (>=400)",
    "status" : "Status message (ERROR)",
    "message" : "Description of message if any",
    "errors" : []
}
```

<aside class="notice">Default responses untuk setiap request ke api V2.</aside>

Code | Meaning
---------- | -------
200 | OK -- Your request is successful
201 | Created -- Your request has been fulfilled, resulting in the creation of a new resource
400 | Bad Request -- Your request sucks
401 | Unauthorized -- Your API key is wrong
403 | Forbidden -- The requested is hidden for administrators only
404 | Not Found -- The specified request could not be found
405 | Method Not Allowed -- You tried to access a request with an invalid method
406 | Not Acceptable -- You requested a format that isn't json
500 | Internal Server Error -- We had a problem with our server. Try again later.
503 | Service Unavailable -- We're temporarially offline for maintanance. Please try again later.
    Status Code: 200

It is an OK code. This status code means that the client's request was carried out successfully by REST API. 200 Status
code will always contain a response body and it is mandatory according to the standards. The response is dependent on
the type of requests such as for POST request the body will contain the response that was desired as a result of the
action in the request. Header fields are received for HEAD type of requests.

    Status Code: 201

This is a typical HTTP 'created'' code because this code results as a consequence of resource creation on the server
side by the controller after the client's request has been accepted. The response body of this 201 code contains the URI
for the newly created resource and they could be many. Yet in the header field, ultimately it should be the most
specific URI to the resource that needs to be referenced.
If the server fails to create the resource immediately and there is a chance that it could take too long, then the
server must return 202 code.

    Status Code: 202

It is a response code that states the request has been accepted. Status Code 202 is generally used when the requested
action may take a longer than usual time for completion. This kind of standard is beneficial to both the server and
client as the connection between the both now needn't be persistent. That means the client need not hang on to the
server connection until the process completes. Since the code returns while the action is in the half way or awaiting, a
pointer to status monitoring or an approximate time by which the user can expect the desired action be fulfilled is sent
in the body of the response.

    Status Code: 204

Being in the 2xx code series, this particular code also indicates that the server has successfully accepted the request
and has fulfilled the request.What makes this code stand out is that the REST API would return an empty response body
yet with updated meta information regarding the present client's document active view, if exists, as a set of
entity-headers.

    Status Code: 301

As discussed above,all the 3xx series needs further more steps to complete the request. Here, this code when received
means the resource that the client has been requesting for has been moved permanently and is available with a new URI
request. This movement could be due to slight redesigning in the REST API's resource model. The response body for this
code would contain the newly assigned URI for the requested resource. API versioning for the new ones can help you avoid
in receiving this response code.

    Status Code: 302

This is a URL redirection way with this status code. The resource that was requested has been found but on the client
side, response would contain the URL. This URL in the header field is the page to which the user would be redirected.
This code was inappropriately implemented by many of the web browsers, where the request type gets changed to GET
regardless of the initial request type. To resolve the reactions that are expected from the client, additional codes 303
and 307 have been issued for the servers to clear the confusion.

    Status Code: 303

This code finds its use when all we desire is that the REST API should indicate that the task by controller has been
completed yet instead of sending the response along with the body, send as a reference to new URI. This time, it doesn't
forcefully redirect the client but provides a temporary URI which the client can access using a GET request.
It has to be kept in mind that this code response shouldn't be cached though the subsequent GET request that user may
use to ping the server can be cached.

    Status Code: 304

This code is almost similar to the 204 status code. The only difference being that 204 is issued when there is nothing
to be sent in the response body while the Status Code 304 is sent when there is no content modified as the request
headers received by the REST API would contain either If-Modified-Since or If-None-Match.
In these circumstances, the server wouldn't send any response as it presumes the client does have a copy of the resource
from the previously downloaded response and this actually saves bandwidth

    Status Code: 307

It is a temporary redirect code that instructs the user to follow the link that the REST API sends in the response's
header location field instead of the REST API itself processing the request. The user needs to henceforth still use the
original URI for future requests. Only if the request type is not HEAD, the response should include a short note about
the hyperlink along with the link to the new URI. Response such as this must be sent only if the request type was either
GET or HEAD. In other cases, the redirect decision should lie with user.

    Status Code: 400

The most generic code out of the 4xx series which indicates that the request received is a bad one and the client
shouldn't put forward the similar kind of request. Bad request may involve invalid request type or misleading parameters
or any other violations. When there are no appropriate codes in the 4xx series that can be used, this is the one which
has to be sent.

    Status Code: 401

An unauthorized access to resource without proper credentials leads to this response code. The client must be responded
with the challenge present in processing the request and must guide the user what could be done to proceed. Hence user
may repeat this request but this time with a proper authentication details.

    Status Code: 403

This is a forbidden status code which means that irrespective of the user sending proper authentication details or not,
user doesn't have sufficient permissions to access the requested resource. Further repetition would not induce any
successful effect.

    Status Code: 404

A code that is usually sent when the requested resource is unavailable or not found on the server. The request can be
repeated for any number of times yet for a failed response. Server may choose 410 instead of Status Code 404 when it
somehow knows that the requested resource was available previously on the requested URI but is no more present
permanently.

    Status Code: 405

If the resource residing on the server should be accessed only through particular request method, say GET or POST only
but not PUT/DELETE, the Status Code 405 method not accepted code is sent along with the resource support information
through allow header.
Example - Allow: HEAD

    Status Code: 406

A not acceptable http code that results when the client requests for one media type through Accept request header, as
response but the API is unable to generate anything as such.For example the client may request for a format
application/json but the API can only process and send the response as application/xml format, then the API needs to
send a Status Code 406 code.
If the user is not satisfied with the response that the API sends, then the user agent must terminate receiving the data
and modify the request.

    Status Code: 412

If the request from the client contains conditions or more specifically what is called as preconditions in the request
headers and request has to be processed only if the conditions were satisfied, then a failure to do so should issue a
412 Precondition failed response code.

    Status Code: 415

An Unsupported Media Type indicative status code that occurs when the media type supplied by the client can't be
processed by the API. Say for example, the client sends the request with application/pdf in the Content-Type header
while the API can process only application/xml, then the server will send a 415 response.

    Status Code: 500

It is a very generic response code which indicates that there was an Internal server error. Most likely this code
indicates that there was an exception raised while processing the request. Since the fault lies on the server side,
client may try the request again hoping to get a proper response.

Status Code: 501
The server when fails to recognize the request method or it has no idea what to do with the request, a 501 not
implemented code is issued.

    Status Code: 418

The hypertext transfer protocol 418 i am a teapot error response code indicates that the server refuses to brew coffee
as a result of it's a pot,
